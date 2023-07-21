<?php
/*******************************************************************************
 *
 * $Id: CMDBUserGroup.php 79018 2013-09-19 00:06:03Z rcallaha $
 * $Date: 2013-09-18 20:06:03 -0400 (Wed, 18 Sep 2013) $
 * $Author: rcallaha $
 * $Revision: 79018 $
 * $HeadURL: https://svn.ultradns.net/svn/sts_tools/sts-lib/trunk/STS/CMDB/CMDBUserGroup.php $
 *
 *******************************************************************************
 */

namespace STS\CMDB;

class CMDBUserGroup
{
	protected $sysId;
	protected $name;

    protected $active;

    protected $manager;
    protected $managerId;

    protected $email;

    protected $netcoolGidName;
    protected $netcoolOwnerGid;
    protected $oncallTeam;

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
		foreach (CMDBUserGroupTable::getNameMapping() as $prop) {
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
		foreach (CMDBUserGroupTable::getNameMapping() as $prop) {
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
     * @return mixed
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return mixed
     */
    public function getManager()
    {
        return $this->manager;
    }

    /**
     * @return mixed
     */
    public function getManagerId()
    {
        return $this->managerId;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getNetcoolGidName()
    {
        return $this->netcoolGidName;
    }

    /**
     * @return mixed
     */
    public function getNetcoolOwnerGid()
    {
        return $this->netcoolOwnerGid;
    }

    /**
     * @return mixed
     */
    public function getOncallTeam()
    {
        return $this->oncallTeam;
    }

    /**
     * @return mixed
     */
    public function getSysCreatedBy()
    {
        return $this->sysCreatedBy;
    }

    /**
     * @return mixed
     */
    public function getSysCreatedOn()
    {
        return $this->sysCreatedOn;
    }

    /**
     * @return mixed
     */
    public function getSysId()
    {
        return $this->sysId;
    }

    /**
     * @return mixed
     */
    public function getSysUpdatedBy()
    {
        return $this->sysUpdatedBy;
    }

    /**
     * @return mixed
     */
    public function getSysUpdatedOn()
    {
        return $this->sysUpdatedOn;
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
     * @param array $changes
     */
    public function setChanges($changes)
    {
        $this->updateChanges(func_get_arg(0));
        $this->changes = $changes;
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
     * @param mixed $manager
     */
    public function setManager($manager)
    {
        $this->updateChanges(func_get_arg(0));
        $this->manager = $manager;
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
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->updateChanges(func_get_arg(0));
        $this->name = $name;
    }

    /**
     * @param mixed $netcoolGidName
     */
    public function setNetcoolGidName($netcoolGidName)
    {
        $this->updateChanges(func_get_arg(0));
        $this->netcoolGidName = $netcoolGidName;
    }

    /**
     * @param mixed $netcoolOwnerGid
     */
    public function setNetcoolOwnerGid($netcoolOwnerGid)
    {
        $this->updateChanges(func_get_arg(0));
        $this->netcoolOwnerGid = $netcoolOwnerGid;
    }

    /**
     * @param mixed $oncallTeam
     */
    public function setOncallTeam($oncallTeam)
    {
        $this->updateChanges(func_get_arg(0));
        $this->oncallTeam = $oncallTeam;
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
     * @param mixed $sysCreatedOn
     */
    public function setSysCreatedOn($sysCreatedOn)
    {
        $this->updateChanges(func_get_arg(0));
        $this->sysCreatedOn = $sysCreatedOn;
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
     * @param mixed $sysUpdatedBy
     */
    public function setSysUpdatedBy($sysUpdatedBy)
    {
        $this->updateChanges(func_get_arg(0));
        $this->sysUpdatedBy = $sysUpdatedBy;
    }

    /**
     * @param mixed $sysUpdatedOn
     */
    public function setSysUpdatedOn($sysUpdatedOn)
    {
        $this->updateChanges(func_get_arg(0));
        $this->sysUpdatedOn = $sysUpdatedOn;
    }

}
