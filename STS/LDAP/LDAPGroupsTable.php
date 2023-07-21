<?php
/*******************************************************************************
 *
 * $Id: LDAPGroupsTable.php 76358 2013-06-27 02:13:37Z rcallaha $
 * $Date: 2013-06-26 22:13:37 -0400 (Wed, 26 Jun 2013) $
 * $Author: rcallaha $
 * $Revision: 76358 $
 * $HeadURL: https://svn.ultradns.net/svn/sts_tools/sts-lib/trunk/STS/LDAP/LDAPGroupsTable.php $
 *
 *******************************************************************************
 */

namespace STS\LDAP;

use STS\UTIL\SysLog;

class LDAPGroupsTable extends LDAPDAO
{
	private static $attributes = array(
        'cn',
        'description');

    public function __construct($config=null)
    {
	    parent::__construct($config);
		$this->baseDN = "ou=Groups,o=Neustar";
        $this->staffDN = "ou=Neustar,ou=Staff,o=Neustar";
    }

    /**
     * @param $uid
     * @return LDAPGroups[]
     */
    public function searchByUniqueMember($uid)
    {
        $filter = "uniqueMember=uid={$uid},{$this->staffDN}";
        $this->sysLog->debug("filter=" . $filter);

        $results = $this->search($this->baseDN, $filter, self::$attributes);
        $groups = array();
        for ($i=0; $i<count($results); $i++) {
            $groups[] = $this->_set($results[$i]);
        }
        return $groups;
    }

    /**
     * @param $groupName
     * @param $uid
     * @return LDAPGroups
     */
    public function searchForUniqueMemberInGroup($groupName, $uid)
    {
        $filter = "(&(cn={$groupName})(uniqueMember=uid={$uid},{$this->staffDN}))";
        $this->sysLog->debug("filter=" . $filter);

	    $result = $this->get($this->baseDN, $filter, self::$attributes);
	    return $this->_set($result);
    }

    /**
     * @param $groupName
     * @param $uid
     */
    public function addUniqueMember($groupName, $uid)
    {
        $dn = "cn={$groupName},{$this->baseDN}";
        $this->sysLog->debug("dn=" . $dn);

        $entry = array(
            "uniqueMember" => "uid={$uid},{$this->staffDN}"
        );
        $this->modAdd($dn, $entry);
    }

    /**
     * @param $groupName
     * @param $uid
     */
    public function deleteUniqueMember($groupName, $uid)
    {
        $dn = "cn={$groupName},{$this->baseDN}";
        $this->sysLog->debug("dn=" . $dn);

        $entry = array(
            "uniqueMember" => "uid={$uid},{$this->staffDN}"
        );
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
	 * @return LDAPGroups
	 */
	private function _set($result = null)
	{
		$this->sysLog->debug();

		$o = new LDAPGroups();
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
