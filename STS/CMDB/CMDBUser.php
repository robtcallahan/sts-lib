<?php
/*******************************************************************************
 *
 * $Id: CMDBUser.php 79018 2013-09-19 00:06:03Z rcallaha $
 * $Date: 2013-09-18 20:06:03 -0400 (Wed, 18 Sep 2013) $
 * $Author: rcallaha $
 * $Revision: 79018 $
 * $HeadURL: https://svn.ultradns.net/svn/sts_tools/sts-lib/trunk/STS/CMDB/CMDBUser.php $
 *
 *******************************************************************************
 */

namespace STS\CMDB;

class CMDBUser
{
	protected $sysId;
	protected $sysClassName;
	protected $active; // true/false
	protected $source; // ldap:uid=gsalinge,ou=neustar,ou=staff,o=neustar

	protected $name;

	protected $firstName;
	protected $lastName;
	protected $userName;

	protected $title;
	protected $email;
	protected $employeeNumber;

	protected $managerId;
	protected $manager;
	protected $departmentId;
	protected $department;

	protected $phone;
	protected $homePhone;
	protected $mobilePhone;
	protected $otherPhone;
	protected $pagerDirectProviderEmail;

	protected $description; // Employee - STERLING
	protected $displayName; // Salinger, Geoff
	protected $nickName; // Geoff

	protected $sysCreatedBy;
	protected $sysCreatedOn;
	protected $sysUpdatedBy;
	protected $sysUpdatedOn;

    protected $changes = array();


    /**
     * @return string
     */
    public function __toString()
	{
		$return = "";
		foreach (CMDBUserTable::getNameMapping() as $prop) {
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
		foreach (CMDBUserTable::getNameMapping() as $prop) {
			$obj->$prop = $this->$prop;
		}
		return $obj;
	}


	// *******************************************************************************
	// * Getters and Setters
	// *****************************************************************************

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
     * @param mixed $active
     */
    public function setActive($active)
    {
        $this->updateChanges(func_get_arg(0));
        $this->active = $active;
    }

    /**
     * @return mixed
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * @param mixed $department
     */
    public function setDepartment($department)
    {
        $this->updateChanges(func_get_arg(0));
        $this->department = $department;
    }

    /**
     * @return mixed
     */
    public function getDepartment()
    {
        return $this->department;
    }

    /**
     * @param mixed $departmentId
     */
    public function setDepartmentId($departmentId)
    {
        $this->updateChanges(func_get_arg(0));
        $this->departmentId = $departmentId;
    }

    /**
     * @return mixed
     */
    public function getDepartmentId()
    {
        return $this->departmentId;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->updateChanges(func_get_arg(0));
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $displayName
     */
    public function setDisplayName($displayName)
    {
        $this->updateChanges(func_get_arg(0));
        $this->displayName = $displayName;
    }

    /**
     * @return mixed
     */
    public function getDisplayName()
    {
        return $this->displayName;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->updateChanges(func_get_arg(0));
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $employeeNumber
     */
    public function setEmployeeNumber($employeeNumber)
    {
        $this->updateChanges(func_get_arg(0));
        $this->employeeNumber = $employeeNumber;
    }

    /**
     * @return mixed
     */
    public function getEmployeeNumber()
    {
        return $this->employeeNumber;
    }

    /**
     * @param mixed $firstName
     */
    public function setFirstName($firstName)
    {
        $this->updateChanges(func_get_arg(0));
        $this->firstName = $firstName;
    }

    /**
     * @return mixed
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param mixed $homePhone
     */
    public function setHomePhone($homePhone)
    {
        $this->updateChanges(func_get_arg(0));
        $this->homePhone = $homePhone;
    }

    /**
     * @return mixed
     */
    public function getHomePhone()
    {
        return $this->homePhone;
    }

    /**
     * @param mixed $lastName
     */
    public function setLastName($lastName)
    {
        $this->updateChanges(func_get_arg(0));
        $this->lastName = $lastName;
    }

    /**
     * @return mixed
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param mixed $manager
     */
    public function setManager($manager)
    {
        $this->updateChanges(func_get_arg(0));
        $this->manager = $manager;
    }

    /**
     * @return mixed
     */
    public function getManager()
    {
        return $this->manager;
    }

    /**
     * @param mixed $managerId
     */
    public function setManagerId($managerId)
    {
        $this->updateChanges(func_get_arg(0));
        $this->managerId = $managerId;
    }

    /**
     * @return mixed
     */
    public function getManagerId()
    {
        return $this->managerId;
    }

    /**
     * @param mixed $mobilePhone
     */
    public function setMobilePhone($mobilePhone)
    {
        $this->updateChanges(func_get_arg(0));
        $this->mobilePhone = $mobilePhone;
    }

    /**
     * @return mixed
     */
    public function getMobilePhone()
    {
        return $this->mobilePhone;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->updateChanges(func_get_arg(0));
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $nickName
     */
    public function setNickName($nickName)
    {
        $this->updateChanges(func_get_arg(0));
        $this->nickName = $nickName;
    }

    /**
     * @return mixed
     */
    public function getNickName()
    {
        return $this->nickName;
    }

    /**
     * @param mixed $otherPhone
     */
    public function setOtherPhone($otherPhone)
    {
        $this->updateChanges(func_get_arg(0));
        $this->otherPhone = $otherPhone;
    }

    /**
     * @return mixed
     */
    public function getOtherPhone()
    {
        return $this->otherPhone;
    }

    /**
     * @param mixed $pagerDirectProviderEmail
     */
    public function setPagerDirectProviderEmail($pagerDirectProviderEmail)
    {
        $this->updateChanges(func_get_arg(0));
        $this->pagerDirectProviderEmail = $pagerDirectProviderEmail;
    }

    /**
     * @return mixed
     */
    public function getPagerDirectProviderEmail()
    {
        return $this->pagerDirectProviderEmail;
    }

    /**
     * @param mixed $phone
     */
    public function setPhone($phone)
    {
        $this->updateChanges(func_get_arg(0));
        $this->phone = $phone;
    }

    /**
     * @return mixed
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param mixed $source
     */
    public function setSource($source)
    {
        $this->updateChanges(func_get_arg(0));
        $this->source = $source;
    }

    /**
     * @return mixed
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * @param mixed $sysClassName
     */
    public function setSysClassName($sysClassName)
    {
        $this->updateChanges(func_get_arg(0));
        $this->sysClassName = $sysClassName;
    }

    /**
     * @return mixed
     */
    public function getSysClassName()
    {
        return $this->sysClassName;
    }

    /**
     * @param mixed $sysCreatedBy
     */
    public function setSysCreatedBy($sysCreatedBy)
    {
        $this->updateChanges(func_get_arg(0));
        $this->sysCreatedBy = $sysCreatedBy;
    }

    /**
     * @return mixed
     */
    public function getSysCreatedBy()
    {
        return $this->sysCreatedBy;
    }

    /**
     * @param mixed $sysCreatedOn
     */
    public function setSysCreatedOn($sysCreatedOn)
    {
        $this->updateChanges(func_get_arg(0));
        $this->sysCreatedOn = $sysCreatedOn;
    }

    /**
     * @return mixed
     */
    public function getSysCreatedOn()
    {
        return $this->sysCreatedOn;
    }

    /**
     * @param mixed $sysId
     */
    public function setSysId($sysId)
    {
        $this->updateChanges(func_get_arg(0));
        $this->sysId = $sysId;
    }

    /**
     * @return mixed
     */
    public function getSysId()
    {
        return $this->sysId;
    }

    /**
     * @param mixed $sysUpdatedBy
     */
    public function setSysUpdatedBy($sysUpdatedBy)
    {
        $this->updateChanges(func_get_arg(0));
        $this->sysUpdatedBy = $sysUpdatedBy;
    }

    /**
     * @return mixed
     */
    public function getSysUpdatedBy()
    {
        return $this->sysUpdatedBy;
    }

    /**
     * @param mixed $sysUpdatedOn
     */
    public function setSysUpdatedOn($sysUpdatedOn)
    {
        $this->updateChanges(func_get_arg(0));
        $this->sysUpdatedOn = $sysUpdatedOn;
    }

    /**
     * @return mixed
     */
    public function getSysUpdatedOn()
    {
        return $this->sysUpdatedOn;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->updateChanges(func_get_arg(0));
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $userName
     */
    public function setUserName($userName)
    {
        $this->updateChanges(func_get_arg(0));
        $this->userName = $userName;
    }

    /**
     * @return mixed
     */
    public function getUserName()
    {
        return $this->userName;
    }

}
