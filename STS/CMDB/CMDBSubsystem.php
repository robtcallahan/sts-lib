<?php
/*******************************************************************************
 *
 * $Id: CMDBSubsystem.php 79019 2013-09-19 01:14:27Z rcallaha $
 * $Date: 2013-09-18 21:14:27 -0400 (Wed, 18 Sep 2013) $
 * $Author: rcallaha $
 * $Revision: 79019 $
 * $HeadURL: https://svn.ultradns.net/svn/sts_tools/sts-lib/trunk/STS/CMDB/CMDBSubsystem.php $
 *
 *******************************************************************************
 */

namespace STS\CMDB;

class CMDBSubsystem
{
	protected $sysId;
	protected $name;

    protected $operationalStatus;
    protected $operationalStatusId;

	protected $businessServiceId;
	protected $businessService;

	protected $owningSupportManagerId;
	protected $owningSupportManager;
	protected $operationsSupportGroupId;
	protected $operationsSupportGroup;

    protected $systemsAdminManager;
    protected $systemsAdminManagerId;
    protected $systemsAdminGroup;
    protected $systemsAdminGroupId;

    protected $cmDirector;
    protected $cmDirectorId;

	protected $subsystemCategory;
	protected $serviceBusinessClass;
	protected $serviceSlaClass;

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
		foreach (CMDBSubsystemTable::getNameMapping() as $prop) {
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
		foreach (CMDBSubsystemTable::getNameMapping() as $prop) {
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
     * @param mixed $cmDirector
     */
    public function setCmDirector($cmDirector)
    {
        $this->updateChanges(func_get_arg(0));
        $this->cmDirector = $cmDirector;
    }

    /**
     * @return mixed
     */
    public function getCmDirector()
    {
        return $this->cmDirector;
    }

    /**
     * @param mixed $cmDirectorId
     */
    public function setCmDirectorId($cmDirectorId)
    {
        $this->updateChanges(func_get_arg(0));
        $this->cmDirectorId = $cmDirectorId;
    }

    /**
     * @return mixed
     */
    public function getCmDirectorId()
    {
        return $this->cmDirectorId;
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
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $operationsSupportGroup
     */
    public function setOperationsSupportGroup($operationsSupportGroup)
    {
        $this->updateChanges(func_get_arg(0));
        $this->operationsSupportGroup = $operationsSupportGroup;
    }

    /**
     * @return mixed
     */
    public function getOperationsSupportGroup()
    {
        return $this->operationsSupportGroup;
    }

    /**
     * @param mixed $operationsSupportGroupId
     */
    public function setOperationsSupportGroupId($operationsSupportGroupId)
    {
        $this->updateChanges(func_get_arg(0));
        $this->operationsSupportGroupId = $operationsSupportGroupId;
    }

    /**
     * @return mixed
     */
    public function getOperationsSupportGroupId()
    {
        return $this->operationsSupportGroupId;
    }

    /**
     * @param mixed $owningSupportManager
     */
    public function setOwningSupportManager($owningSupportManager)
    {
        $this->updateChanges(func_get_arg(0));
        $this->owningSupportManager = $owningSupportManager;
    }

    /**
     * @return mixed
     */
    public function getOwningSupportManager()
    {
        return $this->owningSupportManager;
    }

    /**
     * @param mixed $owningSupportManagerId
     */
    public function setOwningSupportManagerId($owningSupportManagerId)
    {
        $this->updateChanges(func_get_arg(0));
        $this->owningSupportManagerId = $owningSupportManagerId;
    }

    /**
     * @return mixed
     */
    public function getOwningSupportManagerId()
    {
        return $this->owningSupportManagerId;
    }

    /**
     * @param mixed $serviceBusinessClass
     */
    public function setServiceBusinessClass($serviceBusinessClass)
    {
        $this->updateChanges(func_get_arg(0));
        $this->serviceBusinessClass = $serviceBusinessClass;
    }

    /**
     * @return mixed
     */
    public function getServiceBusinessClass()
    {
        return $this->serviceBusinessClass;
    }

    /**
     * @param mixed $serviceSlaClass
     */
    public function setServiceSlaClass($serviceSlaClass)
    {
        $this->updateChanges(func_get_arg(0));
        $this->serviceSlaClass = $serviceSlaClass;
    }

    /**
     * @return mixed
     */
    public function getServiceSlaClass()
    {
        return $this->serviceSlaClass;
    }

    /**
     * @param mixed $subsystemCategory
     */
    public function setSubsystemCategory($subsystemCategory)
    {
        $this->updateChanges(func_get_arg(0));
        $this->subsystemCategory = $subsystemCategory;
    }

    /**
     * @return mixed
     */
    public function getSubsystemCategory()
    {
        return $this->subsystemCategory;
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
     * @return mixed
     */
    public function getSysCreatedBy()
    {
        return $this->sysCreatedBy;
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
     * @return mixed
     */
    public function getSysCreatedOn()
    {
        return $this->sysCreatedOn;
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
     * @return mixed
     */
    public function getSysId()
    {
        return $this->sysId;
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
     * @return mixed
     */
    public function getSysUpdatedBy()
    {
        return $this->sysUpdatedBy;
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
     * @return mixed
     */
    public function getSysUpdatedOn()
    {
        return $this->sysUpdatedOn;
    }

    /**
     * @param mixed $systemsAdminGroup
     */
    public function setSystemsAdminGroup($systemsAdminGroup)
    {
        $this->updateChanges(func_get_arg(0));
        $this->systemsAdminGroup = $systemsAdminGroup;
    }

    /**
     * @return mixed
     */
    public function getSystemsAdminGroup()
    {
        return $this->systemsAdminGroup;
    }

    /**
     * @param mixed $systemsAdminGroupId
     */
    public function setSystemsAdminGroupId($systemsAdminGroupId)
    {
        $this->updateChanges(func_get_arg(0));
        $this->systemsAdminGroupId = $systemsAdminGroupId;
    }

    /**
     * @return mixed
     */
    public function getSystemsAdminGroupId()
    {
        return $this->systemsAdminGroupId;
    }

    /**
     * @param mixed $systemsAdminManager
     */
    public function setSystemsAdminManager($systemsAdminManager)
    {
        $this->updateChanges(func_get_arg(0));
        $this->systemsAdminManager = $systemsAdminManager;
    }

    /**
     * @return mixed
     */
    public function getSystemsAdminManager()
    {
        return $this->systemsAdminManager;
    }

    /**
     * @param mixed $systemsAdminManagerId
     */
    public function setSystemsAdminManagerId($systemsAdminManagerId)
    {
        $this->updateChanges(func_get_arg(0));
        $this->systemsAdminManagerId = $systemsAdminManagerId;
    }

    /**
     * @return mixed
     */
    public function getSystemsAdminManagerId()
    {
        return $this->systemsAdminManagerId;
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
     * @return mixed
     */
    public function getOperationalStatus()
    {
        return $this->operationalStatus;
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
     * @return mixed
     */
    public function getOperationalStatusId()
    {
        return $this->operationalStatusId;
    }

}
