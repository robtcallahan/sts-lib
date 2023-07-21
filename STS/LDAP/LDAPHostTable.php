<?php
/*******************************************************************************
 *
 * $Id: LDAPHostTable.php 76358 2013-06-27 02:13:37Z rcallaha $
 * $Date: 2013-06-26 22:13:37 -0400 (Wed, 26 Jun 2013) $
 * $Author: rcallaha $
 * $Revision: 76358 $
 * $HeadURL: https://svn.ultradns.net/svn/sts_tools/sts-lib/trunk/STS/LDAP/LDAPHostTable.php $
 *
 *******************************************************************************
 */

namespace STS\LDAP;

class LDAPHostTable extends LDAPDAO
{
	private static $attributes = array(
        'cn'
    );

    public function __construct($config=null)
    {
	    parent::__construct($config);
		$this->baseDN = "ou=netgroup,o=neustar";
        $this->attrs = array("cn");
    }

	/**
	 * @param $name
	 * @return LDAPHost[]
	 */
	public function searchByName($name)
    {
        $filter = "(cn={$name})";
        $this->sysLog->debug("filter=" . $filter);

        $results = $this->search($this->baseDN, $filter, $this->attrs);
        return $this->_set($results[0]);
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
	 * @return LDAPHost
	 */
	private function _set($result = null)
	{
		$this->sysLog->debug();

		$o = new LDAPHost();
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
