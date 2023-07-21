<?php
/*******************************************************************************
 *
 * $Id: CMDBRequest.php 79018 2013-09-19 00:06:03Z rcallaha $
 * $Date: 2013-09-18 20:06:03 -0400 (Wed, 18 Sep 2013) $
 * $Author: rcallaha $
 * $Revision: 79018 $
 * $HeadURL: https://svn.ultradns.net/svn/sts_tools/sts-lib/trunk/STS/CMDB/CMDBRequest.php $
 *
 *******************************************************************************
 */

namespace STS\CMDB;

class CMDBRequest
{
	protected $sysId;
	protected $sysClassName;

	protected $number;

	protected $requestCategory; // Access Management
	protected $requestSubcategory; // Access Termination

	protected $requestedFor;
	protected $requestedForId; // sys id

	protected $active;
	protected $approval;
	protected $requestState;

	protected $openedAt;
	protected $openedBy;
	protected $openedById; // sys id
	protected $dueDate;
	protected $closedAt;

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
		foreach (CMDBRequestTable::getNameMapping() as $prop) {
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
		foreach (CMDBRequestTable::getNameMapping() as $prop) {
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
     * @param $requestState
     */
    public function setRequestState($requestState)
	{
        $this->updateChanges(func_get_arg(0));
		$this->requestState = $requestState;
	}

    /**
     * @return mixed
     */
    public function getRequestState()
	{
		return $this->requestState;
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
     * @param $requestedFor
     */
    public function setRequestedFor($requestedFor)
	{
        $this->updateChanges(func_get_arg(0));
		$this->requestedFor = $requestedFor;
	}

    /**
     * @return mixed
     */
    public function getRequestedFor()
	{
		return $this->requestedFor;
	}

    /**
     * @param $requestedForId
     */
    public function setRequestedForId($requestedForId)
	{
        $this->updateChanges(func_get_arg(0));
		$this->requestedForId = $requestedForId;
	}

    /**
     * @return mixed
     */
    public function getRequestedForId()
	{
		return $this->requestedForId;
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

}
