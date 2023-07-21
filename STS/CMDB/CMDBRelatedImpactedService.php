<?php
/*******************************************************************************
 *
 * $Id: CMDBRelatedImpactedService.php 79018 2013-09-19 00:06:03Z rcallaha $
 * $Date: 2013-09-18 20:06:03 -0400 (Wed, 18 Sep 2013) $
 * $Author: rcallaha $
 * $Revision: 79018 $
 * $HeadURL: https://svn.ultradns.net/svn/sts_tools/sts-lib/trunk/STS/CMDB/CMDBRelatedImpactedService.php $
 *
 *******************************************************************************
 */

namespace STS\CMDB;

class CMDBRelatedImpactedService
{
	protected $sysId;

	protected $changeId;
	protected $change;
	protected $serviceId;
	protected $service;
	protected $subsystemId;
	protected $subsystem;

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
		foreach (CMDBRelatedImpactedServiceTable::getNameMapping() as $prop) {
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
		foreach (CMDBRelatedImpactedServiceTable::getNameMapping() as $prop) {
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
     * @param $change
     */
    public function setChange($change)
	{
        $this->updateChanges(func_get_arg(0));
		$this->change = $change;
	}

    /**
     * @return mixed
     */
    public function getChange()
	{
		return $this->change;
	}

    /**
     * @param $changeId
     */
    public function setChangeId($changeId)
	{
        $this->updateChanges(func_get_arg(0));
		$this->changeId = $changeId;
	}

    /**
     * @return mixed
     */
    public function getChangeId()
	{
		return $this->changeId;
	}

    /**
     * @param $service
     */
    public function setService($service)
	{
        $this->updateChanges(func_get_arg(0));
		$this->service = $service;
	}

    /**
     * @return mixed
     */
    public function getService()
	{
		return $this->service;
	}

    /**
     * @param $serviceId
     */
    public function setServiceId($serviceId)
	{
        $this->updateChanges(func_get_arg(0));
		$this->serviceId = $serviceId;
	}

    /**
     * @return mixed
     */
    public function getServiceId()
	{
		return $this->serviceId;
	}

    /**
     * @param $subsystem
     */
    public function setSubsystem($subsystem)
	{
        $this->updateChanges(func_get_arg(0));
		$this->subsystem = $subsystem;
	}

    /**
     * @return mixed
     */
    public function getSubsystem()
	{
		return $this->subsystem;
	}

    /**
     * @param $subsystemId
     */
    public function setSubsystemId($subsystemId)
	{
        $this->updateChanges(func_get_arg(0));
		$this->subsystemId = $subsystemId;
	}

    /**
     * @return mixed
     */
    public function getSubsystemId()
	{
		return $this->subsystemId;
	}

    /**
     * @param $sysCreatedBy
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
     * @param $sysCreatedOn
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
     * @param $sysId
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
     * @param $sysUpdatedBy
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
     * @param $sysUpdatedOn
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

}
