<?php
/*******************************************************************************
 *
 * $Id: CMDBRelationshipType.php 79018 2013-09-19 00:06:03Z rcallaha $
 * $Date: 2013-09-18 20:06:03 -0400 (Wed, 18 Sep 2013) $
 * $Author: rcallaha $
 * $Revision: 79018 $
 * $HeadURL: https://svn.ultradns.net/svn/sts_tools/sts-lib/trunk/STS/CMDB/CMDBRelationshipType.php $
 *
 *******************************************************************************
 */

namespace STS\CMDB;

class CMDBRelationshipType
{
	protected $sysId;
	protected $sysClassName;

	protected $name;

	protected $parentDescriptor;
	protected $childDescriptor;

	protected $sysCreatedBy;
	protected $sysCreatedOn;
	protected $sysUpdatedBy;
	protected $sysUpdatedOn;

    protected $changes = array();

	public function __toString()
	{
		$return = "";
		foreach (CMDBRelationshipTypeTable::getNameMapping() as $prop) {
			$return .= sprintf("%-25s => %s\n", $prop, $this->$prop);
		}
		return $return;
	}

	public function toObject()
	{
		$obj = (object) array();
		foreach (CMDBRelationshipTypeTable::getNameMapping() as $prop) {
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

    public function getChanges()
    {
        return $this->changes;
    }

    public function clearChanges()
    {
        $this->changes = array();
    }

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

	public function setChildDescriptor($childDescriptor)
	{
        $this->updateChanges(func_get_arg(0));
		$this->childDescriptor = $childDescriptor;
	}

	public function getChildDescriptor()
	{
		return $this->childDescriptor;
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

	public function setParentDescriptor($parentDescriptor)
	{
        $this->updateChanges(func_get_arg(0));
		$this->parentDescriptor = $parentDescriptor;
	}

	public function getParentDescriptor()
	{
		return $this->parentDescriptor;
	}

	public function setSysClassName($sysClassName)
	{
        $this->updateChanges(func_get_arg(0));
		$this->sysClassName = $sysClassName;
	}

	public function getSysClassName()
	{
		return $this->sysClassName;
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