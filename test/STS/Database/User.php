<?php
/*******************************************************************************
 *
 * $Id: User.php 74756 2013-04-26 17:08:54Z rcallaha $
 * $Date: 2013-04-26 13:08:54 -0400 (Fri, 26 Apr 2013) $
 * $Author: rcallaha $
 * $Revision: 74756 $
 * $HeadURL: https://svn.ultradns.net/svn/sts_tools/sts-lib/trunk/STS/Login/User.php $
 *
 *******************************************************************************
 */


class User
{
	// column names
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
		return $this->$prop = $value;
	}

    /**
     * @param $accessCode
     * @return $this
     */
    public function setAccessCode($accessCode)
	{
		$this->accessCode = $accessCode;
        return $this;
	}

    /**
     * @return mixed
     */
    public function getAccessCode()
	{
		return $this->accessCode;
	}

    /**
     * @param $dept
     * @return $this
     */
    public function setDept($dept)
	{
		$this->dept = $dept;
        return $this;
	}

    /**
     * @return mixed
     */
    public function getDept()
	{
		return $this->dept;
	}

    /**
     * @param $email
     * @return $this
     */
    public function setEmail($email)
	{
		$this->email = $email;
        return $this;
	}

    /**
     * @return mixed
     */
    public function getEmail()
	{
		return $this->email;
	}

    /**
     * @param $empId
     * @return $this
     */
    public function setEmpId($empId)
	{
		$this->empId = $empId;
        return $this;
	}

    /**
     * @return mixed
     */
    public function getEmpId()
	{
		return $this->empId;
	}

    /**
     * @param $firstName
     * @return $this
     */
    public function setFirstName($firstName)
	{
		$this->firstName = $firstName;
        return $this;
	}

    /**
     * @return mixed
     */
    public function getFirstName()
	{
		return $this->firstName;
	}

    /**
     * @param $id
     * @return $this
     */
    public function setId($id)
	{
		$this->id = $id;
        return $this;
	}

    /**
     * @return mixed
     */
    public function getId()
	{
		return $this->id;
	}

    /**
     * @param $lastName
     * @return $this
     */
    public function setLastName($lastName)
	{
		$this->lastName = $lastName;
        return $this;
	}

    /**
     * @return mixed
     */
    public function getLastName()
	{
		return $this->lastName;
	}

    /**
     * @param $mobilePhone
     * @return $this
     */
    public function setMobilePhone($mobilePhone)
	{
		$this->mobilePhone = $mobilePhone;
        return $this;
	}

    /**
     * @return mixed
     */
    public function getMobilePhone()
	{
		return $this->mobilePhone;
	}

    /**
     * @param $nickName
     * @return $this
     */
    public function setNickName($nickName)
	{
		$this->nickName = $nickName;
        return $this;
	}

    /**
     * @return mixed
     */
    public function getNickName()
	{
		return $this->nickName;
	}

    /**
     * @param $office
     * @return $this
     */
    public function setOffice($office)
	{
		$this->office = $office;
        return $this;
	}

    /**
     * @return mixed
     */
    public function getOffice()
	{
		return $this->office;
	}

    /**
     * @param $officePhone
     * @return $this
     */
    public function setOfficePhone($officePhone)
	{
		$this->officePhone = $officePhone;
        return $this;
	}

    /**
     * @return mixed
     */
    public function getOfficePhone()
	{
		return $this->officePhone;
	}

    /**
     * @param $title
     * @return $this
     */
    public function setTitle($title)
	{
		$this->title = $title;
        return $this;
	}

    /**
     * @return mixed
     */
    public function getTitle()
	{
		return $this->title;
	}

    /**
     * @param $userName
     * @return $this
     */
    public function setUserName($userName)
	{
		$this->userName = $userName;
        return $this;
	}

    /**
     * @return mixed
     */
    public function getUserName()
	{
		return $this->userName;
	}
}
