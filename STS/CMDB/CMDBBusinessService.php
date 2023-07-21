<?php
/*******************************************************************************
 *
 * $Id: CMDBBusinessService.php 76979 2013-07-18 19:27:00Z rcallaha $
 * $Date: 2013-07-18 15:27:00 -0400 (Thu, 18 Jul 2013) $
 * $Author: rcallaha $
 * $Revision: 76979 $
 * $HeadURL: https://svn.ultradns.net/svn/sts_tools/sts-lib/trunk/STS/CMDB/CMDBBusinessService.php $
 *
 *******************************************************************************
 */

namespace STS\CMDB;

class CMDBBusinessService
{
	protected $sysId;
	protected $sysClassName;

	protected $name;

    protected $operationalStatus;
    protected $operationalStatusId;

    protected $businessServiceGrouping;
    protected $businessServiceGroupingId;

    protected $changeNotification;

	protected $incidentOwners;
    protected $incidentOwnersId;

    protected $incidentExecutives;
	protected $incidentExecutivesId;

    protected $incidentNotification;

    protected $operationalSensitivity;
    protected $operationalSensitivityId;

    protected $product;
	protected $productId;
	protected $productLeader;
    protected $productLeaderId;

    protected $systemsAdminLeader;
    protected $systemsAdminLeaderId;
	protected $operationsLeader;
    protected $operationsLeaderId;

	protected $sysCreatedBy;
	protected $sysCreatedOn;
	protected $sysUpdatedBy;
	protected $sysUpdatedOn;

    protected $changes = array();


	public function __toString()
	{
		$return = "";
		foreach (CMDBBusinessServiceTable::getNameMapping() as $prop) {
			$return .= sprintf("%-25s => %s\n", $prop, $this->$prop);
		}
		return $return;
	}

	public function toObject()
	{
		$obj = (object) array();
		foreach (CMDBBusinessServiceTable::getNameMapping() as $prop) {
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

    /**
     * @return mixed
     */
    public function getBusinessServiceGrouping()
    {
        return $this->businessServiceGrouping;
    }

    /**
     * @return mixed
     */
    public function getBusinessServiceGroupingId()
    {
        return $this->businessServiceGroupingId;
    }

    /**
     * @return mixed
     */
    public function getChangeNotification()
    {
        return $this->changeNotification;
    }

    /**
     * @return mixed
     */
    public function getIncidentExecutives()
    {
        return $this->incidentExecutives;
    }

    /**
     * @return mixed
     */
    public function getIncidentExecutivesId()
    {
        return $this->incidentExecutivesId;
    }

    /**
     * @return mixed
     */
    public function getIncidentNotification()
    {
        return $this->incidentNotification;
    }

    /**
     * @return mixed
     */
    public function getIncidentOwners()
    {
        return $this->incidentOwners;
    }

    /**
     * @return mixed
     */
    public function getIncidentOwnersId()
    {
        return $this->incidentOwnersId;
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
    public function getOperationalSensitivity()
    {
        return $this->operationalSensitivity;
    }

    /**
     * @return mixed
     */
    public function getOperationalSensitivityId()
    {
        return $this->operationalSensitivityId;
    }

    /**
     * @return mixed
     */
    public function getOperationalStatus()
    {
        return $this->operationalStatus;
    }

    /**
     * @return mixed
     */
    public function getOperationalStatusId()
    {
        return $this->operationalStatusId;
    }

    /**
     * @return mixed
     */
    public function getOperationsLeader()
    {
        return $this->operationsLeader;
    }

    /**
     * @return mixed
     */
    public function getOperationsLeaderId()
    {
        return $this->operationsLeaderId;
    }

    /**
     * @return mixed
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * @return mixed
     */
    public function getProductId()
    {
        return $this->productId;
    }

    /**
     * @return mixed
     */
    public function getProductLeader()
    {
        return $this->productLeader;
    }

    /**
     * @return mixed
     */
    public function getProductLeaderId()
    {
        return $this->productLeaderId;
    }

    /**
     * @return mixed
     */
    public function getSysClassName()
    {
        return $this->sysClassName;
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
     * @return mixed
     */
    public function getSystemsAdminLeader()
    {
        return $this->systemsAdminLeader;
    }

    /**
     * @return mixed
     */
    public function getSystemsAdminLeaderId()
    {
        return $this->systemsAdminLeaderId;
    }

    /**
     * @param mixed $businessServiceGrouping
     */
    public function setBusinessServiceGrouping($businessServiceGrouping)
    {
        $this->updateChanges(func_get_arg(0));
        $this->businessServiceGrouping = $businessServiceGrouping;
    }

    /**
     * @param mixed $businessServiceGroupingId
     */
    public function setBusinessServiceGroupingId($businessServiceGroupingId)
    {
        $this->updateChanges(func_get_arg(0));
        $this->businessServiceGroupingId = $businessServiceGroupingId;
    }

    /**
     * @param mixed $changeNotification
     */
    public function setChangeNotification($changeNotification)
    {
        $this->updateChanges(func_get_arg(0));
        $this->changeNotification = $changeNotification;
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
     * @param mixed $incidentExecutives
     */
    public function setIncidentExecutives($incidentExecutives)
    {
        $this->updateChanges(func_get_arg(0));
        $this->incidentExecutives = $incidentExecutives;
    }

    /**
     * @param mixed $incidentExecutivesId
     */
    public function setIncidentExecutivesId($incidentExecutivesId)
    {
        $this->updateChanges(func_get_arg(0));
        $this->incidentExecutivesId = $incidentExecutivesId;
    }

    /**
     * @param mixed $incidentNotification
     */
    public function setIncidentNotification($incidentNotification)
    {
        $this->updateChanges(func_get_arg(0));
        $this->incidentNotification = $incidentNotification;
    }

    /**
     * @param mixed $incidentOwners
     */
    public function setIncidentOwners($incidentOwners)
    {
        $this->updateChanges(func_get_arg(0));
        $this->incidentOwners = $incidentOwners;
    }

    /**
     * @param mixed $incidentOwnersId
     */
    public function setIncidentOwnersId($incidentOwnersId)
    {
        $this->updateChanges(func_get_arg(0));
        $this->incidentOwnersId = $incidentOwnersId;
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
     * @param mixed $operationalSensitivity
     */
    public function setOperationalSensitivity($operationalSensitivity)
    {
        $this->updateChanges(func_get_arg(0));
        $this->operationalSensitivity = $operationalSensitivity;
    }

    /**
     * @param mixed $operationalSensitivityId
     */
    public function setOperationalSensitivityId($operationalSensitivityId)
    {
        $this->updateChanges(func_get_arg(0));
        $this->operationalSensitivityId = $operationalSensitivityId;
    }

    /**
     * @param mixed $operationalStatus
     */
    public function setOperationalStatus($operationalStatus)
    {
        $this->updateChanges(func_get_arg(0));
        $this->operationalStatus = $operationalStatus;
    }

    /**
     * @param mixed $operationalStatusId
     */
    public function setOperationalStatusId($operationalStatusId)
    {
        $this->updateChanges(func_get_arg(0));
        $this->operationalStatusId = $operationalStatusId;
    }

    /**
     * @param mixed $operationsLeader
     */
    public function setOperationsLeader($operationsLeader)
    {
        $this->updateChanges(func_get_arg(0));
        $this->operationsLeader = $operationsLeader;
    }

    /**
     * @param mixed $operationsLeaderId
     */
    public function setOperationsLeaderId($operationsLeaderId)
    {
        $this->updateChanges(func_get_arg(0));
        $this->operationsLeaderId = $operationsLeaderId;
    }

    /**
     * @param mixed $product
     */
    public function setProduct($product)
    {
        $this->updateChanges(func_get_arg(0));
        $this->product = $product;
    }

    /**
     * @param mixed $productId
     */
    public function setProductId($productId)
    {
        $this->updateChanges(func_get_arg(0));
        $this->productId = $productId;
    }

    /**
     * @param mixed $productLeader
     */
    public function setProductLeader($productLeader)
    {
        $this->updateChanges(func_get_arg(0));
        $this->productLeader = $productLeader;
    }

    /**
     * @param mixed $productLeaderId
     */
    public function setProductLeaderId($productLeaderId)
    {
        $this->updateChanges(func_get_arg(0));
        $this->productLeaderId = $productLeaderId;
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

    /**
     * @param mixed $systemsAdminLeader
     */
    public function setSystemsAdminLeader($systemsAdminLeader)
    {
        $this->updateChanges(func_get_arg(0));
        $this->systemsAdminLeader = $systemsAdminLeader;
    }

    /**
     * @param mixed $systemsAdminLeaderId
     */
    public function setSystemsAdminLeaderId($systemsAdminLeaderId)
    {
        $this->updateChanges(func_get_arg(0));
        $this->systemsAdminLeaderId = $systemsAdminLeaderId;
    }

}