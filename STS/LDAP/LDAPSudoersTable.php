<?php
/*******************************************************************************
 *
 * $Id: LDAPSudoersTable.php 76358 2013-06-27 02:13:37Z rcallaha $
 * $Date: 2013-06-26 22:13:37 -0400 (Wed, 26 Jun 2013) $
 * $Author: rcallaha $
 * $Revision: 76358 $
 * $HeadURL: https://svn.ultradns.net/svn/sts_tools/sts-lib/trunk/STS/LDAP/LDAPSudoersTable.php $
 *
 *******************************************************************************
 */

namespace STS\LDAP;

use STS\UTIL\SysLog;

class LDAPSudoersTable extends LDAPDAO
{
    private static $attributes = array(
        'cn',
        'description',
        'sudoCommand',
        'sudoHost',
        'sudoOption',
        'sudoRunAs',
        'sudoUser'
    );

    public function __construct($config=null)
    {
        parent::__construct($config);
        $this->baseDN = "ou=SUDOers,o=Neustar";
    }

    /**
     * @param $uid
     * @return LDAPSudoers
     */
    public function searchByCN($uid)
    {
        $filter = "cn={$uid}";
        $this->sysLog->debug("filter=" . $filter);

        $result = $this->get($this->baseDN, $filter, self::$attributes);
	    return $this->_set($result);
    }

    /**
     * @param $uid
     * @return LDAPSudoers[]
     */
    public function searchBySudoUser($uid)
    {
        $filter = "sudoUser={$uid}";
        $this->sysLog->debug("filter=" . $filter);

        $results = $this->getRecords($this->baseDN, $filter, self::$attributes);
        $groups = array();
        for ($i=0; $i<count($results); $i++) {
            $groups[] = $this->_set($results[$i]);
        }
        return $groups;
    }

    /**
     * @param $groupName
     * @param $uid
     * @return LDAPSudoers
     */
    public function searchForSudoUserInGroup($groupName, $uid)
    {
        $filter = "(&(cn={$groupName})(sudoUser={$uid}))";
        $this->sysLog->debug("filter=" . $filter);

        $result = $this->get($this->baseDN, $filter, self::$attributes);
	    return $this->_set($result);
    }

    /**
     * @param LDAPSudoers $sudoers
     * @return LDAPSudoers
     */
    public function add(LDAPSudoers $sudoers)
    {
        $dn = "cn={$sudoers->getCn()},{$this->baseDN}";
        $this->sysLog->debug("dn=" . $dn);

        $sudoCommands = $sudoers->getSudoCommand();
        $sudoHosts = $sudoers->getSudoHost();
        $sudoUsers = $sudoers->getSudoUser();

        $info = array(
            "cn" => $sudoers->getCn(),
            "description" => "{$sudoers->getDescription()}",
            "sudoCommand" => "{$sudoCommands[0]}",
            "sudoHost" => $sudoHosts[0],
            "sudoUser" => $sudoUsers[0],
            "objectClass" => array(
                "top", "sudoRole"
            )
        );
        parent::add($dn, $info);
        return $this->searchByCN($sudoers->getCn());
    }

    /**
     * @param LDAPSudoers $sudoers
     */
    public function delete(LDAPSudoers $sudoers)
    {
        $dn = "cn={$sudoers->getCn()},{$this->baseDN}";
        $this->sysLog->debug("dn=" . $dn);

        parent::delete($dn);
    }

    /**
     * @param $groupName
     * @param $uid
     */
    public function addUserToGroup($groupName, $uid)
    {
        $dn = "cn={$groupName},{$this->baseDN}";
        $this->sysLog->debug("dn=" . $dn);

        $entry = array(
            "sudoUser" => "{$uid}"
        );
        $this->modAdd($dn, $entry);
    }

    /**
     * @param $groupName
     * @param $uid
     */
    public function deleteUserFromGroup($groupName, $uid)
    {
        $dn = "cn={$groupName},{$this->baseDN}";
        $this->sysLog->debug("dn=" . $dn);

        $entry = array(
            "sudoUser" => "{$uid}"
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
	 * @return LDAPSudoers
	 */
	private function _set($result = null)
	{
		$this->sysLog->debug();

		$o = new LDAPSudoers();
		if ($result) {
			foreach (self::$attributes as $prop) {
				if (property_exists($result, $prop)) {
                    if (is_array($result->$prop)) {
                        $ar = array();
                        $propArray = $result->$prop;
                        for ($i=0; $i<count($propArray); $i++) {
                            $ar[$i] = $propArray[$i];
                        }
                        $o->set($prop, $ar);
                    } else {
                        $o->set($prop, $result->$prop);
                    }
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
