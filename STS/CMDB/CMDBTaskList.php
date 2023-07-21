<?php
/*******************************************************************************
 *
 * $Id: CMDBTaskList.php 79018 2013-09-19 00:06:03Z rcallaha $
 * $Date: 2013-09-18 20:06:03 -0400 (Wed, 18 Sep 2013) $
 * $Author: rcallaha $
 * $Revision: 79018 $
 * $HeadURL: https://svn.ultradns.net/svn/sts_tools/sts-lib/trunk/STS/CMDB/CMDBTaskList.php $
 *
 *******************************************************************************
 */

namespace STS\CMDB;

class CMDBTaskList
{
	protected $sysId;
	protected $sysClassName;

	protected $number;
	protected $shortDescription;

	protected $active;
	protected $stateId;
	protected $state;

	protected $approval;
	protected $assignmentGroupId; // sys id (275fa1250a0a3cac01202ddcf65bd1d5)
	protected $assignmentGroup; // text   (eg., Core Hosting)
	protected $actionTaken;
	protected $category; // Access Termination

	protected $requestItemId; // sys id
	protected $requestItem;

	protected $openedAt;
	protected $openedById; // sys id
	protected $openedBy;
	protected $dueDate;
	protected $workEnd;
	protected $closedAt;
	protected $closedById; // sys id
	protected $closedBy;

	protected $workNotes;

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
		foreach (CMDBTaskListTable::getNameMapping() as $prop) {
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
		foreach (CMDBTaskListTable::getNameMapping() as $prop) {
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
     * @param $actionTaken
     */
    public function setActionTaken($actionTaken)
	{
        $this->updateChanges(func_get_arg(0));
		$this->actionTaken = $actionTaken;
	}

    /**
     * @return mixed
     */
    public function getActionTaken()
	{
		return $this->actionTaken;
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
     * @param $assignmentGroup
     */
    public function setAssignmentGroup($assignmentGroup)
	{
        $this->updateChanges(func_get_arg(0));
		$this->assignmentGroup = $assignmentGroup;
	}

    /**
     * @return mixed
     */
    public function getAssignmentGroup()
	{
		return $this->assignmentGroup;
	}

    /**
     * @param $assignmentGroupId
     */
    public function setAssignmentGroupId($assignmentGroupId)
	{
        $this->updateChanges(func_get_arg(0));
		$this->assignmentGroupId = $assignmentGroupId;
	}

    /**
     * @return mixed
     */
    public function getAssignmentGroupId()
	{
		return $this->assignmentGroupId;
	}

    /**
     * @param $category
     */
    public function setCategory($category)
	{
        $this->updateChanges(func_get_arg(0));
		$this->category = $category;
	}

    /**
     * @return mixed
     */
    public function getCategory()
	{
		return $this->category;
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
     * @param $requestItem
     */
    public function setRequestItem($requestItem)
	{
        $this->updateChanges(func_get_arg(0));
		$this->requestItem = $requestItem;
	}

    /**
     * @return mixed
     */
    public function getRequestItem()
	{
		return $this->requestItem;
	}

    /**
     * @param $requestItemId
     */
    public function setRequestItemId($requestItemId)
	{
        $this->updateChanges(func_get_arg(0));
		$this->requestItemId = $requestItemId;
	}

    /**
     * @return mixed
     */
    public function getRequestItemId()
	{
		return $this->requestItemId;
	}

    /**
     * @param $shortDescription
     */
    public function setShortDescription($shortDescription)
	{
        $this->updateChanges(func_get_arg(0));
		$this->shortDescription = $shortDescription;
	}

    /**
     * @return mixed
     */
    public function getShortDescription()
	{
		return $this->shortDescription;
	}

    /**
     * @param $state
     */
    public function setState($state)
	{
        $this->updateChanges(func_get_arg(0));
		$this->state = $state;
	}

    /**
     * @return mixed
     */
    public function getState()
	{
		return $this->state;
	}

    /**
     * @param $stateId
     */
    public function setStateId($stateId)
	{
        $this->updateChanges(func_get_arg(0));
		$this->stateId = $stateId;
	}

    /**
     * @return mixed
     */
    public function getStateId()
	{
		return $this->stateId;
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
     * @param $workEnd
     */
    public function setWorkEnd($workEnd)
	{
        $this->updateChanges(func_get_arg(0));
		$this->workEnd = $workEnd;
	}

    /**
     * @return mixed
     */
    public function getWorkEnd()
	{
		return $this->workEnd;
	}

    /**
     * @param $workNotes
     */
    public function setWorkNotes($workNotes)
	{
        $this->updateChanges(func_get_arg(0));
		$this->workNotes = $workNotes;
	}

    /**
     * @return mixed
     */
    public function getWorkNotes()
	{
		return $this->workNotes;
	}

}

