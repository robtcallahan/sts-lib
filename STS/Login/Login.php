<?php
/*******************************************************************************
 *
 * $Id: Login.php 74756 2013-04-26 17:08:54Z rcallaha $
 * $Date: 2013-04-26 13:08:54 -0400 (Fri, 26 Apr 2013) $
 * $Author: rcallaha $
 * $Revision: 74756 $
 * $HeadURL: https://svn.ultradns.net/svn/sts_tools/sts-lib/trunk/STS/Login/Login.php $
 *
 *******************************************************************************
 */

namespace STS\Login;

class Login
{
	protected $userId; // foreign key to id in user table
	protected $numLogins; // integer
	protected $lastLogin; // timestamp
	protected $ipAddr; // varchar
	protected $userAgent; // varchar

	protected $id;

	protected $firstName;
	protected $lastName;
	protected $nickName;
	protected $userName;

	protected $empId;
	protected $title;
	protected $dept;
	protected $office;
	protected $email;

	protected $officePhone;
	protected $mobilePhone;

	protected $accessCode;

	public function __toString()
	{
		$return = "";
		foreach (UserTable::getColumnNames() as $prop) {
			$return .= sprintf("%-25s => %s\n", $prop, $this->$prop);
		}
		return $return;
	}

	public function toObject()
	{
		$obj = (object) array();
		foreach (UserTable::getColumnNames() as $prop) {
			$obj->$prop = $this->$prop;
		}
		return $obj;
	}


	// *******************************************************************************
	// Getters and Setters
	// *******************************************************************************

	public function get($prop)
	{
		return $this->$prop;
	}

	public function set($prop, $value)
	{
		return $this->$prop = $value;
	}

	public function setIpAddr($ipAddr)
	{
		$this->ipAddr = $ipAddr;
	}

	public function getIpAddr()
	{
		return $this->ipAddr;
	}

	public function setLastLogin($lastLogin)
	{
		$this->lastLogin = $lastLogin;
	}

	public function getLastLogin()
	{
		return $this->lastLogin;
	}

	public function setNumLogins($numLogins)
	{
		$this->numLogins = $numLogins;
	}

	public function getNumLogins()
	{
		return $this->numLogins;
	}

	public function setUserAgent($userAgent)
	{
		$this->userAgent = $userAgent;
	}

	public function getUserAgent()
	{
		return $this->userAgent;
	}

	public function setUserId($userId)
	{
		$this->userId = $userId;
	}

	public function getUserId()
	{
		return $this->userId;
	}

	public function setAccessCode($accessCode)
	{
		$this->accessCode = $accessCode;
	}

	public function getAccessCode()
	{
		return $this->accessCode;
	}

	public function setDept($dept)
	{
		$this->dept = $dept;
	}

	public function getDept()
	{
		return $this->dept;
	}

	public function setEmail($email)
	{
		$this->email = $email;
	}

	public function getEmail()
	{
		return $this->email;
	}

	public function setEmpId($empId)
	{
		$this->empId = $empId;
	}

	public function getEmpId()
	{
		return $this->empId;
	}

	public function setFirstName($firstName)
	{
		$this->firstName = $firstName;
	}

	public function getFirstName()
	{
		return $this->firstName;
	}

	public function setId($id)
	{
		$this->id = $id;
	}

	public function getId()
	{
		return $this->id;
	}

	public function setLastName($lastName)
	{
		$this->lastName = $lastName;
	}

	public function getLastName()
	{
		return $this->lastName;
	}

	public function setMobilePhone($mobilePhone)
	{
		$this->mobilePhone = $mobilePhone;
	}

	public function getMobilePhone()
	{
		return $this->mobilePhone;
	}

	public function setNickName($nickName)
	{
		$this->nickName = $nickName;
	}

	public function getNickName()
	{
		return $this->nickName;
	}

	public function setOffice($office)
	{
		$this->office = $office;
	}

	public function getOffice()
	{
		return $this->office;
	}

	public function setOfficePhone($officePhone)
	{
		$this->officePhone = $officePhone;
	}

	public function getOfficePhone()
	{
		return $this->officePhone;
	}

	public function setTitle($title)
	{
		$this->title = $title;
	}

	public function getTitle()
	{
		return $this->title;
	}

	public function setUserName($userName)
	{
		$this->userName = $userName;
	}

	public function getUserName()
	{
		return $this->userName;
	}
}
