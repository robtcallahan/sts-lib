<?php
/*******************************************************************************
 *
 * $Id: CMDBGroup.php 79833 2013-10-14 14:01:00Z rcallaha $
 * $Date: 2013-10-14 10:01:00 -0400 (Mon, 14 Oct 2013) $
 * $Author: rcallaha $
 * $Revision: 79833 $
 * $HeadURL: https://svn.ultradns.net/svn/sts_tools/sts-lib/trunk/STS/CMDB/CMDBGroup.php $
 *
 *******************************************************************************
 */

namespace STS\CMDB;

class CMDBGroup
{
    protected $sysId;
    protected $name;
    protected $active;

    protected $managerId;
    protected $manager;
    protected $email;

	protected $sysCreatedBy;
	protected $sysCreatedOn;
    protected $sysUpdatedBy;
    protected $sysUpdatedOn;

    /**
     * Keeps track of properties that have their values changed
     *
     * @var array
     */
    protected $changes = array();

    /**
     * @return string
     */
    public function __toString()
	{
		$return = "";
		foreach (CMDBGroupTable::getNameMapping() as $prop)
		{
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
		foreach (CMDBGroupTable::getNameMapping() as $prop) {
			$obj->$prop = $this->$prop;
		}
		return $obj;
	}


	// *******************************************************************************
	// * Getters and Setters
	// *****************************************************************************

	public function get($prop)
	{
		return $this->$prop;
	}

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

	public function setActive($active)
	{
        $this->updateChanges(func_get_arg(0));
		$this->active = $active;
	}

	public function getActive()
	{
		return $this->active;
	}

	public function setEmail($email)
	{
        $this->updateChanges(func_get_arg(0));
		$this->email = $email;
	}

	public function getEmail()
	{
		return $this->email;
	}

	public function setManager($manager)
	{
        $this->updateChanges(func_get_arg(0));
		$this->manager = $manager;
	}

	public function getManager()
	{
		return $this->manager;
	}

	public function setManagerId($managerId)
	{
        $this->updateChanges(func_get_arg(0));
		$this->managerId = $managerId;
	}

	public function getManagerId()
	{
		return $this->managerId;
	}

	public function setName($name)
	{
        $this->updateChanges(func_get_arg(0));
		$this->name = $name;
	}

	public function getName()
	{
		return $this->name;
	}

	public function setSysCreatedBy($sysCreatedBy)
	{
        $this->updateChanges(func_get_arg(0));
		$this->sysCreatedBy = $sysCreatedBy;
	}

	public function getSysCreatedBy()
	{
		return $this->sysCreatedBy;
	}

	public function setSysCreatedOn($sysCreatedOn)
	{
        $this->updateChanges(func_get_arg(0));
		$this->sysCreatedOn = $sysCreatedOn;
	}

	public function getSysCreatedOn()
	{
		return $this->sysCreatedOn;
	}

	public function setSysId($sysId)
	{
        $this->updateChanges(func_get_arg(0));
		$this->sysId = $sysId;
	}

	public function getSysId()
	{
		return $this->sysId;
	}

	public function setSysUpdatedBy($sysUpdatedBy)
	{
        $this->updateChanges(func_get_arg(0));
		$this->sysUpdatedBy = $sysUpdatedBy;
	}

	public function getSysUpdatedBy()
	{
		return $this->sysUpdatedBy;
	}

	public function setSysUpdatedOn($sysUpdatedOn)
	{
        $this->updateChanges(func_get_arg(0));
		$this->sysUpdatedOn = $sysUpdatedOn;
	}

	public function getSysUpdatedOn()
	{
		return $this->sysUpdatedOn;
	}
}
