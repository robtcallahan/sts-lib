<?php
/*******************************************************************************
 *
 * $Id: CMDBRequestItem.php 79018 2013-09-19 00:06:03Z rcallaha $
 * $Date: 2013-09-18 20:06:03 -0400 (Wed, 18 Sep 2013) $
 * $Author: rcallaha $
 * $Revision: 79018 $
 * $HeadURL: https://svn.ultradns.net/svn/sts_tools/sts-lib/trunk/STS/CMDB/CMDBRequestItem.php $
 *
 *******************************************************************************
 */

namespace STS\CMDB;

class CMDBRequestItem
{
	protected $sysId;
	protected $sysClassName;

	protected $number;

	protected $requestId;
	protected $request;
	protected $requestCategory;
	protected $requestSubcategory;

    protected $businessService;
    protected $businessServiceId;
    protected $subsystem;
    protected $subsystemId;

	protected $active;
	protected $approval;
	protected $assignedToId;
	protected $assignedTo;
	protected $catItemId;
	protected $catItem;

	protected $openedAt;
	protected $openedBy;
	protected $openedById;
	protected $dueDate;
	protected $closedAt;
	protected $closedBy;
	protected $closedById;

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
		foreach (CMDBRequestItemTable::getNameMapping() as $prop) {
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
		foreach (CMDBRequestItemTable::getNameMapping() as $prop) {
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
     * @param $active
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
     * @param $approval
     */
    public function setApproval($approval)
	{
        $this->updateChanges(func_get_arg(0));
		$this->approval = $approval;
	}

    /**
     * @return mixed
     */
    public function getApproval()
	{
		return $this->approval;
	}

    /**
     * @param $assignedTo
     */
    public function setAssignedTo($assignedTo)
	{
        $this->updateChanges(func_get_arg(0));
		$this->assignedTo = $assignedTo;
	}

    /**
     * @return mixed
     */
    public function getAssignedTo()
	{
		return $this->assignedTo;
	}

    /**
     * @param $assignedToId
     */
    public function setAssignedToId($assignedToId)
	{
        $this->updateChanges(func_get_arg(0));
		$this->assignedToId = $assignedToId;
	}

    /**
     * @return mixed
     */
    public function getAssignedToId()
	{
		return $this->assignedToId;
	}

    /**
     * @param $catItem
     */
    public function setCatItem($catItem)
	{
        $this->updateChanges(func_get_arg(0));
		$this->catItem = $catItem;
	}

    /**
     * @return mixed
     */
    public function getCatItem()
	{
		return $this->catItem;
	}

    /**
     * @param $catItemId
     */
    public function setCatItemId($catItemId)
	{
        $this->updateChanges(func_get_arg(0));
		$this->catItemId = $catItemId;
	}

    /**
     * @return mixed
     */
    public function getCatItemId()
	{
		return $this->catItemId;
	}

    /**
     * @param $closedAt
     */
    public function setClosedAt($closedAt)
	{
        $this->updateChanges(func_get_arg(0));
		$this->closedAt = $closedAt;
	}

    /**
     * @return mixed
     */
    public function getClosedAt()
	{
		return $this->closedAt;
	}

    /**
     * @param $closedBy
     */
    public function setClosedBy($closedBy)
	{
        $this->updateChanges(func_get_arg(0));
		$this->closedBy = $closedBy;
	}

    /**
     * @return mixed
     */
    public function getClosedBy()
	{
		return $this->closedBy;
	}

    /**
     * @param $closedById
     */
    public function setClosedById($closedById)
	{
        $this->updateChanges(func_get_arg(0));
		$this->closedById = $closedById;
	}

    /**
     * @return mixed
     */
    public function getClosedById()
	{
		return $this->closedById;
	}

    /**
     * @param $dueDate
     */
    public function setDueDate($dueDate)
	{
        $this->updateChanges(func_get_arg(0));
		$this->dueDate = $dueDate;
	}

    /**
     * @return mixed
     */
    public function getDueDate()
	{
		return $this->dueDate;
	}

    /**
     * @param $number
     */
    public function setNumber($number)
	{
        $this->updateChanges(func_get_arg(0));
		$this->number = $number;
	}

    /**
     * @return mixed
     */
    public function getNumber()
	{
		return $this->number;
	}

    /**
     * @param $openedAt
     */
    public function setOpenedAt($openedAt)
	{
        $this->updateChanges(func_get_arg(0));
		$this->openedAt = $openedAt;
	}

    /**
     * @return mixed
     */
    public function getOpenedAt()
	{
		return $this->openedAt;
	}

    /**
     * @param $openedBy
     */
    public function setOpenedBy($openedBy)
	{
        $this->updateChanges(func_get_arg(0));
		$this->openedBy = $openedBy;
	}

    /**
     * @return mixed
     */
    public function getOpenedBy()
	{
		return $this->openedBy;
	}

    /**
     * @param $openedById
     */
    public function setOpenedById($openedById)
	{
        $this->updateChanges(func_get_arg(0));
		$this->openedById = $openedById;
	}

    /**
     * @return mixed
     */
    public function getOpenedById()
	{
		return $this->openedById;
	}

    /**
     * @param $request
     */
    public function setRequest($request)
	{
        $this->updateChanges(func_get_arg(0));
		$this->request = $request;
	}

    /**
     * @return mixed
     */
    public function getRequest()
	{
		return $this->request;
	}

    /**
     * @param $requestCategory
     */
    public function setRequestCategory($requestCategory)
	{
        $this->updateChanges(func_get_arg(0));
		$this->requestCategory = $requestCategory;
	}

    /**
     * @return mixed
     */
    public function getRequestCategory()
	{
		return $this->requestCategory;
	}

    /**
     * @param $requestId
     */
    public function setRequestId($requestId)
	{
        $this->updateChanges(func_get_arg(0));
		$this->requestId = $requestId;
	}

    /**
     * @return mixed
     */
    public function getRequestId()
	{
		return $this->requestId;
	}

    /**
     * @param $requestSubcategory
     */
    public function setRequestSubcategory($requestSubcategory)
	{
        $this->updateChanges(func_get_arg(0));
		$this->requestSubcategory = $requestSubcategory;
	}

    /**
     * @return mixed
     */
    public function getRequestSubcategory()
	{
		return $this->requestSubcategory;
	}

    /**
     * @param $sysClassName
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

    /**
     * @param mixed $businessService
     */
    public function setBusinessService($businessService)
    {
        $this->updateChanges(func_get_arg(0));
        $this->businessService = $businessService;
    }

    /**
     * @return mixed
     */
    public function getBusinessService()
    {
        return $this->businessService;
    }

    /**
     * @param mixed $businessServiceId
     */
    public function setBusinessServiceId($businessServiceId)
    {
        $this->updateChanges(func_get_arg(0));
        $this->businessServiceId = $businessServiceId;
    }

    /**
     * @return mixed
     */
    public function getBusinessServiceId()
    {
        return $this->businessServiceId;
    }

    /**
     * @param mixed $subsystem
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
     * @param mixed $subsystemId
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

}
