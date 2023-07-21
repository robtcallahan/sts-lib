<?php
/*******************************************************************************
 *
 * $Id: LDAPStaffTable.php 76358 2013-06-27 02:13:37Z rcallaha $
 * $Date: 2013-06-26 22:13:37 -0400 (Wed, 26 Jun 2013) $
 * $Author: rcallaha $
 * $Revision: 76358 $
 * $HeadURL: https://svn.ultradns.net/svn/sts_tools/sts-lib/trunk/STS/LDAP/LDAPStaffTable.php $
 *
 *******************************************************************************
 */

namespace STS\LDAP;

use STS\UTIL\SysLog;

class LDAPStaffTable extends LDAPDAO
{
	private static $attributes = array(
		'uid',
        'cn',
        'sn',
        'description',
        'employeeNumber',
        'givenName',
        'mail',
        'gecos',
        'loginShell',
        'homeDirectory',
        'gidNumber',
        'uidNumber',
        'neuStatus',
        'neuRole',
        'objectClass'
	);

    public function __construct($config=null)
    {
	    parent::__construct($config);
		$this->baseDN = "ou=Neustar,ou=Staff,o=Neustar";
    }

	/**
	 * @param $uid
	 * @return LDAPStaff
	 */
	public function searchByUid($uid)
    {
        $filter = "(uid={$uid})";
        $this->sysLog->debug("filter=" . $filter);

        $result = $this->get($this->baseDN, $filter, self::$attributes);
	    return $this->_set($result);
    }

	/**
	 * @param LDAPStaff $staff
	 * @return LDAPStaff
	 */
	public function addStaff(LDAPStaff $staff)
    {
        $dn = "uid={$staff->getUid()},{$this->baseDN}";
        $this->sysLog->debug("dn=" . $dn);

        $info = array(
            "uid" => $staff->getUid(),
            "cn" => "{$staff->getSn()}, {$staff->getGivenName()}",
            "sn" => $staff->getSn(),
            "givenName" => $staff->getGivenName(),
            "description" => $staff->getDescription(),
            "employeeNumber" => $staff->getEmployeeNumber(),
            "mail" => $staff->getMail(),
            "objectClass" => array(
                "top", "person", "organizationalPerson", "inetorgperson"
            )
        );
        parent::add($dn, $info);
        return $this->searchByUid($staff->getUid());
    }

	/**
	 * @param LDAPStaff $staff
	 */
	public function deleteStaff(LDAPStaff $staff)
    {
        $dn = "uid={$staff->getUid()},{$this->baseDN}";
        $this->sysLog->debug("dn=" . $dn);

        parent::delete($dn);
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
	 * @return LDAPStaff
	 */
	private function _set($result = null)
	{
		$this->sysLog->debug();

		$o = new LDAPStaff();
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
