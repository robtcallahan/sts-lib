<?php
/*******************************************************************************
 *
 * $Id: LDAPStaff.php 76358 2013-06-27 02:13:37Z rcallaha $
 * $Date: 2013-06-26 22:13:37 -0400 (Wed, 26 Jun 2013) $
 * $Author: rcallaha $
 * $Revision: 76358 $
 * $HeadURL: https://svn.ultradns.net/svn/sts_tools/sts-lib/trunk/STS/LDAP/LDAPStaff.php $
 *
 *******************************************************************************
 */

namespace STS\LDAP;

class LDAPStaff
{
    private $uid;
    private $cn;
    private $sn;
    private $givenName;
    private $description;
    private $employeeNumber;
    private $mail;
    private $gecos;
    private $loginShell;
    private $homeDirectory;
    private $gidNumber;
    private $uidNumber;
    private $neuStatus;
    private $neuRole;
    private $objectClass;

    /**
     * @return string
     */
    public function __toString()
	{
		$return = "";
		foreach (LDAPStaffTable::getAttributes() as $prop) {
			$return .= sprintf("%-25s => %s\n", $prop, $this->$prop);
		}
		return $return;
	}

    /**
     * @return object
     */
    public function toObject()
	{
		$obj = (object) array();
		foreach (LDAPStaffTable::getAttributes() as $prop) {
			$obj->$prop = $this->$prop;
		}
		return $obj;
	}


	// *******************************************************************************
	// Getters and Setters
	// *******************************************************************************

    /**
     * @param $prop
     * @return mixed
     */
    public function get($prop)
	{
		return $this->$prop;
	}

    /**
     * @param $prop
     * @param $value
     * @return mixed
     */
    public function set($prop, $value)
	{
		$this->$prop = $value;
        return $this;
	}

    /**
     * @param $cn
     * @return $this
     */
    public function setCn($cn)
	{
		$this->cn = $cn;
        return $this;
	}

    /**
     * @return mixed
     */
    public function getCn()
	{
		return $this->cn;
	}

    /**
     * @param $description
     * @return $this
     */
    public function setDescription($description)
	{
		$this->description = $description;
        return $this;
	}

    /**
     * @return mixed
     */
    public function getDescription()
	{
		return $this->description;
	}

    /**
     * @param $employeeNumber
     * @return $this
     */
    public function setEmployeeNumber($employeeNumber)
	{
		$this->employeeNumber = $employeeNumber;
        return $this;
	}

    /**
     * @return mixed
     */
    public function getEmployeeNumber()
	{
		return $this->employeeNumber;
	}

    /**
     * @param $givenName
     * @return $this
     */
    public function setGivenName($givenName)
	{
		$this->givenName = $givenName;
        return $this;
	}

    /**
     * @return mixed
     */
    public function getGivenName()
	{
		return $this->givenName;
	}

    /**
     * @param $mail
     * @return $this
     */
    public function setMail($mail)
	{
		$this->mail = $mail;
        return $this;
	}

    /**
     * @return mixed
     */
    public function getMail()
	{
		return $this->mail;
	}

    /**
     * @param $sn
     * @return $this
     */
    public function setSn($sn)
	{
		$this->sn = $sn;
        return $this;
	}

    /**
     * @return mixed
     */
    public function getSn()
	{
		return $this->sn;
	}

    /**
     * @param $uid
     * @return $this
     */
    public function setUid($uid)
	{
		$this->uid = $uid;
        return $this;
	}

    /**
     * @return mixed
     */
    public function getUid()
	{
		return $this->uid;
	}

    /**
     * @param mixed $gecos
     * @return $this
     */
    public function setGecos($gecos)
    {
        $this->gecos = $gecos;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getGecos()
    {
        return $this->gecos;
    }

    /**
     * @param mixed $gidNumber
     * @return $this
     */
    public function setGidNumber($gidNumber)
    {
        $this->gidNumber = $gidNumber;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getGidNumber()
    {
        return $this->gidNumber;
    }

    /**
     * @param mixed $homeDirectory
     * @return $this
     */
    public function setHomeDirectory($homeDirectory)
    {
        $this->homeDirectory = $homeDirectory;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getHomeDirectory()
    {
        return $this->homeDirectory;
    }

    /**
     * @param mixed $loginShell
     * @return $this
     */
    public function setLoginShell($loginShell)
    {
        $this->loginShell = $loginShell;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLoginShell()
    {
        return $this->loginShell;
    }

    /**
     * @param mixed $neuRole
     * @return $this
     */
    public function setNeuRole($neuRole)
    {
        $this->neuRole = $neuRole;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNeuRole()
    {
        return $this->neuRole;
    }

    /**
     * @param mixed $neuStatus
     * @return $this
     */
    public function setNeuStatus($neuStatus)
    {
        $this->neuStatus = $neuStatus;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNeuStatus()
    {
        return $this->neuStatus;
    }

    /**
     * @param mixed $objectClass
     * @return $this
     */
    public function setObjectClass($objectClass)
    {
        $this->objectClass = $objectClass;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getObjectClass()
    {
        return $this->objectClass;
    }

    /**
     * @param mixed $uidNumber
     * @return $this
     */
    public function setUidNumber($uidNumber)
    {
        $this->uidNumber = $uidNumber;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUidNumber()
    {
        return $this->uidNumber;
    }

}
