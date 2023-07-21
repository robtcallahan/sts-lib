<?php
/*******************************************************************************
 *
 * $Id: CMDBRack.php 79456 2013-10-01 19:06:08Z rcallaha $
 * $Date: 2013-10-01 15:06:08 -0400 (Tue, 01 Oct 2013) $
 * $Author: rcallaha $
 * $Revision: 79456 $
 * $HeadURL: https://svn.ultradns.net/svn/sts_tools/sts-lib/trunk/STS/CMDB/CMDBRack.php $
 *
 *******************************************************************************
 */

namespace STS\CMDB;

class CMDBRack
{
	protected $sysId;
	protected $sysClassName;

	protected $name;

	protected $locationId;
	protected $location;

	protected $rackUnits;
	protected $rackUnitsInUse;
	protected $rackSizeId;
	protected $rackSize;
	protected $typeOfPower;
	protected $voltage;

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
		foreach (CMDBRackTable::getNameMapping() as $prop) {
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
		foreach (CMDBRackTable::getNameMapping() as $prop) {
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
		$this->$prop = $value;
        return $this;
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
     * @param $sysUpdatedOn
     * @return $this
     */
    public function setSysUpdatedOn($sysUpdatedOn)
	{
        $this->updateChanges(func_get_arg(0));
		$this->sysUpdatedOn = $sysUpdatedOn;
        return $this;
	}

    /**
     * @return mixed
     */
    public function getSysUpdatedOn()
	{
		return $this->sysUpdatedOn;
	}

    /**
     * @param $location
     * @return $this
     */
    public function setLocation($location)
	{
        $this->updateChanges(func_get_arg(0));
		$this->location = $location;
        return $this;
	}

    /**
     * @return mixed
     */
    public function getLocation()
	{
		return $this->location;
	}

    /**
     * @param $name
     * @return $this
     */
    public function setName($name)
	{
        $this->updateChanges(func_get_arg(0));
		$this->name = $name;
        return $this;
	}

    /**
     * @return mixed
     */
    public function getName()
	{
		return $this->name;
	}

    /**
     * @param $sysClassName
     * @return $this
     */
    public function setSysClassName($sysClassName)
	{
        $this->updateChanges(func_get_arg(0));
		$this->sysClassName = $sysClassName;
        return $this;
	}

    /**
     * @return mixed
     */
    public function getSysClassName()
	{
		return $this->sysClassName;
	}

    /**
     * @param $sysCreatedBy
     * @return $this
     */
    public function setSysCreatedBy($sysCreatedBy)
	{
        $this->updateChanges(func_get_arg(0));
		$this->sysCreatedBy = $sysCreatedBy;
        return $this;
	}

    /**
     * @return mixed
     */
    public function getSysCreatedBy()
	{
		return $this->sysCreatedBy;
	}

    /**
     * @param $sysCreatedOn
     * @return $this
     */
    public function setSysCreatedOn($sysCreatedOn)
	{
        $this->updateChanges(func_get_arg(0));
		$this->sysCreatedOn = $sysCreatedOn;
        return $this;
	}

    /**
     * @return mixed
     */
    public function getSysCreatedOn()
	{
		return $this->sysCreatedOn;
	}

    /**
     * @param $sysId
     * @return $this
     */
    public function setSysId($sysId)
	{
        $this->updateChanges(func_get_arg(0));
		$this->sysId = $sysId;
        return $this;
	}

    /**
     * @return mixed
     */
    public function getSysId()
	{
		return $this->sysId;
	}

    /**
     * @param $sysUpdatedBy
     * @return $this
     */
    public function setSysUpdatedBy($sysUpdatedBy)
	{
        $this->updateChanges(func_get_arg(0));
		$this->sysUpdatedBy = $sysUpdatedBy;
        return $this;
	}

    /**
     * @return mixed
     */
    public function getSysUpdatedBy()
	{
		return $this->sysUpdatedBy;
	}

    /**
     * @param $locationId
     * @return $this
     */
    public function setLocationId($locationId)
	{
        $this->updateChanges(func_get_arg(0));
		$this->locationId = $locationId;
        return $this;
	}

    /**
     * @return mixed
     */
    public function getLocationId()
	{
		return $this->locationId;
	}

    /**
     * @param $rackSize
     * @return $this
     */
    public function setRackSize($rackSize)
	{
        $this->updateChanges(func_get_arg(0));
		$this->rackSize = $rackSize;
        return $this;
	}

    /**
     * @return mixed
     */
    public function getRackSize()
	{
		return $this->rackSize;
	}

    /**
     * @param $rackSizeId
     * @return $this
     */
    public function setRackSizeId($rackSizeId)
	{
        $this->updateChanges(func_get_arg(0));
		$this->rackSizeId = $rackSizeId;
        return $this;
	}

    /**
     * @return mixed
     */
    public function getRackSizeId()
	{
		return $this->rackSizeId;
	}

    /**
     * @param $rackUnits
     * @return $this
     */
    public function setRackUnits($rackUnits)
	{
        $this->updateChanges(func_get_arg(0));
		$this->rackUnits = $rackUnits;
        return $this;
	}

    /**
     * @return mixed
     */
    public function getRackUnits()
	{
		return $this->rackUnits;
	}

    /**
     * @param $rackUnitsInUse
     * @return $this
     */
    public function setRackUnitsInUse($rackUnitsInUse)
	{
        $this->updateChanges(func_get_arg(0));
		$this->rackUnitsInUse = $rackUnitsInUse;
        return $this;
	}

    /**
     * @return mixed
     */
    public function getRackUnitsInUse()
	{
		return $this->rackUnitsInUse;
	}

    /**
     * @param $typeOfPower
     * @return $this
     */
    public function setTypeOfPower($typeOfPower)
	{
        $this->updateChanges(func_get_arg(0));
		$this->typeOfPower = $typeOfPower;
        return $this;
	}

    /**
     * @return mixed
     */
    public function getTypeOfPower()
	{
		return $this->typeOfPower;
	}

    /**
     * @param $voltage
     * @return $this
     */
    public function setVoltage($voltage)
	{
        $this->updateChanges(func_get_arg(0));
		$this->voltage = $voltage;
        return $this;
	}

    /**
     * @return mixed
     */
    public function getVoltage()
	{
		return $this->voltage;
	}
}
