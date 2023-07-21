<?php
/*******************************************************************************
 *
 * $Id: LDAPGroupTable.php 76358 2013-06-27 02:13:37Z rcallaha $
 * $Date: 2013-06-26 22:13:37 -0400 (Wed, 26 Jun 2013) $
 * $Author: rcallaha $
 * $Revision: 76358 $
 * $HeadURL: https://svn.ultradns.net/svn/sts_tools/sts-lib/trunk/STS/LDAP/LDAPGroupTable.php $
 *
 *******************************************************************************
 */

namespace STS\LDAP;

use STS\UTIL\SysLog;

class LDAPGroupTable extends LDAPDAO
{
	private static $attributes = array(
        'cn',
        'gidNumber',
        'description');

    public function __construct($config=null)
    {
	    parent::__construct($config);
		$this->baseDN = "ou=Group,o=Neustar";
        $this->attrs = array("cn", "gidNumber", "description");
    }

	/**
	 * @param $uid
	 * @return LDAPGroup[]
	 */
	public function searchByMemberUid($uid)
    {
        $filter = "memberUid={$uid}";
        $this->sysLog->debug("filter=" . $filter);

        $results = $this->search($this->baseDN, $filter, $this->attrs);
        $groups = array();
        for ($i=0; $i<count($results); $i++) {
            $groups[] = $this->_set($results[$i]);
        }
        return $groups;
    }

    /**
     * @param $groupName
     * @param $uid
     * @return LDAPGroup
     */
    public function searchForMemberUidInGroup($groupName, $uid)
    {
        $filter = "(&(cn={$groupName})(memberUid={$uid}))";
        $this->sysLog->debug("filter=" . $filter);

        $result = $this->get($this->baseDN, $filter, $this->attrs);
	    return $this->_set($result);
    }

	/**
	 * @param $groupName
	 * @param $uid
	 */
	public function addMemberUid($groupName, $uid)
    {
        $dn = "cn={$groupName},{$this->baseDN}";
        $this->sysLog->debug("dn=" . $dn);

        $entry = array(
            "memberUid" => $uid
        );
        $this->modAdd($dn, $entry);
    }

	/**
	 * @param $groupName
	 * @param $uid
	 */
	public function deleteMemberUid($groupName, $uid)
    {
        $dn = "cn={$groupName},{$this->baseDN}";
        $this->sysLog->debug("dn=" . $dn);

        $entry = array(
            "memberUid" => $uid
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
	 * @param $attributes
	 * @return void
	 */
	public static function setColumnNames($attributes)
	{
		self::$attributes = $attributes;
	}

	/**
	 * @return array
	 */
	public static function getColumnNames()
	{
		return self::$attributes;
	}

	/**
	 * @param null $result
	 * @return LDAPGroup
	 */
	private function _set($result = null)
	{
		$this->sysLog->debug();

		$o = new LDAPGroup();
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
