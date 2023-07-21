<?php
/*******************************************************************************
 *
 * $Id: LDAPNetgroupTable.php 76358 2013-06-27 02:13:37Z rcallaha $
 * $Date: 2013-06-26 22:13:37 -0400 (Wed, 26 Jun 2013) $
 * $Author: rcallaha $
 * $Revision: 76358 $
 * $HeadURL: https://svn.ultradns.net/svn/sts_tools/sts-lib/trunk/STS/LDAP/LDAPNetgroupTable.php $
 *
 *******************************************************************************
 */

namespace STS\LDAP;

use STS\UTIL\SysLog;

class LDAPNetgroupTable extends LDAPDAO
{
    private static $attributes = array(
        'cn',
        'description',
        'neuOwner',
        'nisNetgroupTriple',
        'memberNisNetgroup',
        'objectClass',
    );

    public function __construct($config=null)
    {
        parent::__construct($config);
        $this->baseDN = "ou=Netgroup,o=Neustar";
    }

    /**
     * @param $uid
     * @return LDAPNetgroup[]
     */
    public function getByUid($uid)
    {
        $filter = "nisNetgroupTriple=\(-,{$uid},\)";
        $this->sysLog->debug("filter=" . $filter);

        #$results = $this->getRecords($this->baseDN, $filter, self::$attributes);
        $results = $this->getRecords($this->baseDN, $filter);
        $groups = array();
        for ($i=0; $i<count($results); $i++) {
            $groups[] = $this->_set($results[$i]);
        }
        return $groups;
    }

    /**
     * @param $uid
     * @return LDAPNetgroup[]
     */
    public function getByUserName($uid)
    {
        $filter = "nisNetgroupTriple=\(-,{$uid},\)";
        $this->sysLog->debug("filter=" . $filter);

        $results = $this->getRecords($this->baseDN, $filter, self::$attributes);
        $groups = array();
        foreach ($results as $result) {
            if (!property_exists($result, 'memberNisNetgroup')) {
                $groups[] = $this->_set($result);
            }
        }
        return $groups;
    }

    /**
     * @param $hostname
     * @return LDAPNetgroup
     */
    public function getByHostName($hostname) {
        $filter = "(&(cn={$hostname})(objectClass=neuHost))";
        $results = $this->getRecords($this->baseDN, $filter, self::$attributes);
        if (is_array($results) && count($results) == 1) {
            return $this->_set($results[0]);
        } else {
            return new LDAPNetgroup();
        }
    }


    /*
    public function getForUserGroup($userGroup) {
        $filter = "(&(cn={$userGroup})(!objectClass=neuHost))";
        $results = $this->getRecords($this->baseDN, $filter, self::$attributes);
        return $results;
    }
    */

    /**
     * @return LDAPNetgroup[]
     */
    public function getPrimaryGroups()
    {
        // set up our baseDN and filter
        $baseDN = "ou=glass,o=neustar";
        $filter = "(&(objectClass=*)(cn=*))";

        // perform the search
        $results = $this->getRecords($baseDN, $filter, self::$attributes);

        // transform the results into LDAPNetgroup class objects
        $groups = array();
        foreach ($results as $result) {
            $groups[] = $this->_set($result);
        }
        return $groups;
    }

    /**
     * @param $groupName
     * @return array
     */
    public function getPrimarySubGroups($groupName)
    {
        // set up our baseDN and filter
        $baseDN = "ou=glass,o=neustar";
        $filter = "(&(objectClass=*)(cn={$groupName}))";

        // perform the search
        $results = $this->getRecords($baseDN, $filter, self::$attributes);

        $subGroups = array();
        foreach ($results as $result) {
            if (property_exists($result, 'memberNisNetgroup')) {
                // add anything that is a memberNisNetgroup
                if (is_array($result->memberNisNetgroup)) {
                    foreach ($result->memberNisNetgroup as $ng) {
                        $subGroups[] = $ng;
                    }
                } else {
                   $subGroups[] = $result->memberNisNetgroup;
                }
            }
        }
        return $subGroups;
    }

    /**
     * @param $groupName
     * @param $uid
     * @return LDAPNetgroup
     */
    public function searchForUidInGroup($groupName, $uid)
    {
        $filter = "(&(cn={$groupName})(nisNetgroupTriple=\(-,{$uid},\)))";
        $this->sysLog->debug("filter=" . $filter);

        $result = $this->get($this->baseDN, $filter, self::$attributes);
	    return $this->_set($result);
    }

    /**
     * @param $groupName
     * @param $uid
     */
    public function addUid($groupName, $uid)
    {
        $dn = "cn={$groupName},{$this->baseDN}";
        $this->sysLog->debug("dn=" . $dn);

        $entry = array(
            "nisNetgroupTriple" => "(-,{$uid},)"
        );
        $this->modAdd($dn, $entry);
    }

    /**
     * @param $groupName
     * @param $uid
     */
    public function deleteUid($groupName, $uid)
    {
        $dn = "cn={$groupName},{$this->baseDN}";
        $this->sysLog->debug("dn=" . $dn);

        $entry = array(
            "nisNetgroupTriple" => "(-,{$uid},)"
        );
        $this->modDelete($dn, $entry);
    }



    public function deleteHost($hostName) {
        $dn = "cn={$hostName},{$this->baseDN}";

        $entry = array(
            "nisNetgroupTriple" => "(-,{$hostName},)"
        );
        $this->modDelete($dn, $entry);
    }

    public function addHostToUserGroup($hostName, $groupName) {
        $dn = "cn=$hostName,ou=netgroup,o=neustar";
        $entry = Array('membernisnetgroup' => "$groupName");
        $this->modAdd($dn, $entry);
    }

    public function deleteHostFromUserGroup($hostName, $groupName) {
        $dn = "cn=$hostName,ou=netgroup,o=neustar";
        $entry = Array('membernisnetgroup' => "$groupName");
        $this->modDelete($dn, $entry);
    }

    public function addHostToHostGroup($hostName, $groupName) {
        $dn = "cn=$groupName,ou=netgroup,o=neustar";
        $entry = Array('nisnetgrouptriple' => "($hostName,,)");
        $this->modAdd($dn, $entry);
    }

    public function deleteHostFromHostGroup($hostName, $groupName) {
        $dn = "cn=$groupName,ou=netgroup,o=neustar";
        $entry = Array('nisnetgrouptriple' => "($hostName,,)");
        $this->modDelete($dn, $entry);
    }



	// *******************************************************************************
	// Getters and Setters
	// *******************************************************************************

	/**
	 * @param $logLevel
	 * @return void
	 */
	public function setLogLevel($logLevel)
	{
		$this->logLevel = $logLevel;
	}

	/**
	 * @return mixed
	 */
	public function getLogLevel()
	{
		return $this->logLevel;
	}

	/**
	 * @return array
	 */
	public static function getAttributes()
	{
		return self::$attributes;
	}

	/**
	 * @param null $result
	 * @return LDAPNetgroup
	 */
	private function _set($result = null)
	{
		$this->sysLog->debug();

		$o = new LDAPNetgroup();
		if ($result) {
			foreach (self::$attributes as $prop) {
				if (property_exists($result, $prop)) {
					$o->set($prop, $result->$prop);
				} else {
					$o->set($prop, null);
				}
			}
		}
		else {
			foreach (self::$attributes as $prop) {
				$o->set($prop, null);
			}
		}
		return $o;
	}
}