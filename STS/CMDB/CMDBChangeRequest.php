<?php
/*******************************************************************************
 *
 * $Id: CMDBChangeRequest.php 79018 2013-09-19 00:06:03Z rcallaha $
 * $Date: 2013-09-18 20:06:03 -0400 (Wed, 18 Sep 2013) $
 * $Author: rcallaha $
 * $Revision: 79018 $
 * $HeadURL: https://svn.ultradns.net/svn/sts_tools/sts-lib/trunk/STS/CMDB/CMDBChangeRequest.php $
 *
 *******************************************************************************
 */

namespace STS\CMDB;

class CMDBChangeRequest
{
	protected $sysId;
	protected $sysClassName;

	protected $number;

	protected $assignmentGroupId;
	protected $assignmentGroup;
	protected $assignedToId;
	protected $assignedTo;
	protected $changeOwnerId;
	protected $changeOwner;

	protected $serviceId;
	protected $service;
	protected $businessServicesId;
	protected $businessServices;

	protected $additionalCisId;
	protected $additionalCis;
	protected $ciNotListed;

	protected $requestedById;
	protected $requestedBy;

	protected $approval;
	protected $category;
	protected $subCategory;
	protected $type;

	protected $shortDescription;

	protected $startDate;
	protected $endDate;

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
		foreach (CMDBChangeRequestTable::getNameMapping() as $prop) {
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
		foreach (CMDBChangeRequestTable::getNameMapping() as $prop) {
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
     * @param $additionalCis
     */
    public function setAdditionalCis($additionalCis)
	{
        $this->updateChanges(func_get_arg(0));
		$this->additionalCis = $additionalCis;
	}

    /**
     * @return mixed
     */
    public function getAdditionalCis()
	{
		return $this->additionalCis;
	}

    /**
     * @param $additionalCisId
     */
    public function setAdditionalCisId($additionalCisId)
	{
        $this->updateChanges(func_get_arg(0));
		$this->additionalCisId = $additionalCisId;
	}

    /**
     * @return mixed
     */
    public function getAdditionalCisId()
	{
		return $this->additionalCisId;
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
     * @param $businessServices
     */
    public function setBusinessServices($businessServices)
	{
        $this->updateChanges(func_get_arg(0));
		$this->businessServices = $businessServices;
	}

    /**
     * @return mixed
     */
    public function getBusinessServices()
	{
		return $this->businessServices;
	}

    /**
     * @param $businessServicesId
     */
    public function setBusinessServicesId($businessServicesId)
	{
        $this->updateChanges(func_get_arg(0));
		$this->businessServicesId = $businessServicesId;
	}

    /**
     * @return mixed
     */
    public function getBusinessServicesId()
	{
		return $this->businessServicesId;
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
     * @param $changeOwner
     */
    public function setChangeOwner($changeOwner)
	{
        $this->updateChanges(func_get_arg(0));
		$this->changeOwner = $changeOwner;
	}

    /**
     * @return mixed
     */
    public function getChangeOwner()
	{
		return $this->changeOwner;
	}

    /**
     * @param $changeOwnerId
     */
    public function setChangeOwnerId($changeOwnerId)
	{
        $this->updateChanges(func_get_arg(0));
		$this->changeOwnerId = $changeOwnerId;
	}

    /**
     * @return mixed
     */
    public function getChangeOwnerId()
	{
		return $this->changeOwnerId;
	}

    /**
     * @param $ciNotListed
     */
    public function setCiNotListed($ciNotListed)
	{
        $this->updateChanges(func_get_arg(0));
		$this->ciNotListed = $ciNotListed;
	}

    /**
     * @return mixed
     */
    public function getCiNotListed()
	{
		return $this->ciNotListed;
	}

    /**
     * @param $endDate
     */
    public function setEndDate($endDate)
	{
        $this->updateChanges(func_get_arg(0));
		$this->endDate = $endDate;
	}

    /**
     * @return mixed
     */
    public function getEndDate()
	{
		return $this->endDate;
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
     * @param $requestedBy
     */
    public function setRequestedBy($requestedBy)
	{
        $this->updateChanges(func_get_arg(0));
		$this->requestedBy = $requestedBy;
	}

    /**
     * @return mixed
     */
    public function getRequestedBy()
	{
		return $this->requestedBy;
	}

    /**
     * @param $requestedById
     */
    public function setRequestedById($requestedById)
	{
        $this->updateChanges(func_get_arg(0));
		$this->requestedById = $requestedById;
	}

    /**
     * @return mixed
     */
    public function getRequestedById()
	{
		return $this->requestedById;
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
     * @param $startDate
     */
    public function setStartDate($startDate)
	{
        $this->updateChanges(func_get_arg(0));
		$this->startDate = $startDate;
	}

    /**
     * @return mixed
     */
    public function getStartDate()
	{
		return $this->startDate;
	}

    /**
     * @param $subCategory
     */
    public function setSubCategory($subCategory)
	{
        $this->updateChanges(func_get_arg(0));
		$this->subCategory = $subCategory;
	}

    /**
     * @return mixed
     */
    public function getSubCategory()
	{
		return $this->subCategory;
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
     * @param $type
     */
    public function setType($type)
	{
        $this->updateChanges(func_get_arg(0));
		$this->type = $type;
	}

    /**
     * @return mixed
     */
    public function getType()
	{
		return $this->type;
	}

}
