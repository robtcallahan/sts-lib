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

namespace STS\Database;

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

    // meta array to keep track of changed properties
    protected $changes = array();


    /**
     * @return string
     */
    public function __toString()
	{
		$return = "";
		foreach (UserTable::getColumnNames() as $prop) {
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
		foreach (UserTable::getColumnNames() as $prop) {
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
		return $this->$prop = $value;
	}

    /**
     * @return array
     */
    public function getChanges()
    {
        return $this->changes;
    }

    /**
     *
     */
    public function clearChanges()
    {
        $this->changes = array();
    }

    /**
     * @param $value
     */
    private function updateChanges($value)
    {
        $trace = debug_backtrace();

        // get the calling method name, eg., setSysId
        $callerMethod = $trace[1]["function"];

        // perform a replace to remove "set" from the method name and change first letter to lowercase
        // so, setSysId becomes sysId. This will be the property name that needs to be added to the changes array
        $prop = preg_replace_callback(
            "/^set(\w)/",
            function($matches) {
                return strtolower($matches[1]);
            },
            $callerMethod
        );

        // update the changes array to keep track of this properties orig and new values
        if (!array_key_exists($prop, $this->changes)) {
            $this->changes[$prop] = (object) array(
                'originalValue' => $this->$prop,
                'modifiedValue' => $value
            );
        } else {
            $this->changes[$prop]->modifiedValue = $value;
        }
    }

    /**
     * @param $accessCode
     * @return $this
     */
    public function setAccessCode($accessCode)
	{
        $this->updateChanges(func_get_arg(0));
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
        $this->updateChanges(func_get_arg(0));
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
        $this->updateChanges(func_get_arg(0));
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
        $this->updateChanges(func_get_arg(0));
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
        $this->updateChanges(func_get_arg(0));
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
        $this->updateChanges(func_get_arg(0));
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
        $this->updateChanges(func_get_arg(0));
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
        $this->updateChanges(func_get_arg(0));
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
        $this->updateChanges(func_get_arg(0));
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
        $this->updateChanges(func_get_arg(0));
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
        $this->updateChanges(func_get_arg(0));
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
        $this->updateChanges(func_get_arg(0));
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
        $this->updateChanges(func_get_arg(0));
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
