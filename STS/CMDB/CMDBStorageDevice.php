<?php
/*******************************************************************************
 *
 * $Id: CMDBStorageDevice.php 82444 2014-01-03 14:28:59Z rcallaha $
 * $Date: 2014-01-03 09:28:59 -0500 (Fri, 03 Jan 2014) $
 * $Author: rcallaha $
 * $Revision: 82444 $
 * $HeadURL: https://svn.ultradns.net/svn/sts_tools/sts-lib/trunk/STS/CMDB/CMDBStorageDevice.php $
 *
 *******************************************************************************
 */

namespace STS\CMDB;

class CMDBStorageDevice
{
    protected $sysId;
    protected $sysClassName;

    protected $name;
    protected $assetTag;

    protected $deviceType;
    protected $deviceTypeId;

    protected $installStatus;
    protected $installStatusId;

    protected $businessService;
    protected $businessServiceId;
    protected $businessServices;
    protected $businessServicesIds;

    protected $subsystemList;
    protected $subsystemListId;

    protected $environment;
    protected $environmentId;

    protected $locationType;
    protected $locationTypeId;
    protected $location;
    protected $locationId;
    protected $rack;
    protected $rackId;
    protected $rackPosition;
    protected $numberOfRackUnits;

    protected $manufacturer;
    protected $manufacturerId;
    protected $modelNumber;
    protected $serialNumber;

    protected $dataSource;

    protected $sysCreatedBy;
    protected $sysCreatedOn;
    protected $sysUpdatedBy;
    protected $sysUpdatedOn;

    protected $changes = array();


    public function __toString()
    {
        $return = "";
        foreach (CMDBStorageDeviceTable::getNameMapping() as $prop) {
            $return .= sprintf("%-25s => %s\n", $prop, $this->$prop);
        }
        return $return;
    }

    public function toObject()
    {
        $obj = (object)array();
        foreach (CMDBStorageDeviceTable::getNameMapping() as $prop) {
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
            function ($matches) {
                return strtolower($matches[1]);
            },
            $callerMethod
        );

        // update the changes array to keep track of this properties orig and new values
        if ($value != $this->$prop) {
            if (!array_key_exists($prop, $this->changes)) {
                $this->changes[$prop] = (object)array(
                    'originalValue' => $this->$prop,
                    'modifiedValue' => $value
                );
            } else {
                $this->changes[$prop]->modifiedValue = $value;
            }
        }
    }

    public function setAssetTag($assetTag)
    {
        $this->updateChanges(func_get_arg(0));
        $this->assetTag = $assetTag;
        return $this;
    }

    public function getAssetTag()
    {
        return $this->assetTag;
    }

    public function setBusinessService($businessService)
    {
        $this->updateChanges(func_get_arg(0));
        $this->businessService = $businessService;
        return $this;
    }

    public function getBusinessService()
    {
        return $this->businessService;
    }

    public function setBusinessServiceId($businessServiceId)
    {
        $this->updateChanges(func_get_arg(0));
        $this->businessServiceId = $businessServiceId;
        return $this;
    }

    public function getBusinessServiceId()
    {
        return $this->businessServiceId;
    }

    public function setEnvironment($environment)
    {
        $this->updateChanges(func_get_arg(0));
        $this->environment = $environment;
        return $this;
    }

    public function getEnvironment()
    {
        return $this->environment;
    }

    public function setLocation($location)
    {
        $this->updateChanges(func_get_arg(0));
        $this->location = $location;
        return $this;
    }

    public function getLocation()
    {
        return $this->location;
    }

    public function setManufacturer($manufacturer)
    {
        $this->updateChanges(func_get_arg(0));
        $this->manufacturer = $manufacturer;
        return $this;
    }

    public function getManufacturer()
    {
        return $this->manufacturer;
    }

    public function setManufacturerId($manufacturerId)
    {
        $this->updateChanges(func_get_arg(0));
        $this->manufacturerId = $manufacturerId;
        return $this;
    }

    public function getManufacturerId()
    {
        return $this->manufacturerId;
    }

    public function setModelNumber($modelNumber)
    {
        $this->updateChanges(func_get_arg(0));
        $this->modelNumber = $modelNumber;
        return $this;
    }

    public function getModelNumber()
    {
        return $this->modelNumber;
    }

    public function setName($name)
    {
        $this->updateChanges(func_get_arg(0));
        $this->name = $name;
        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setSerialNumber($serialNumber)
    {
        $this->updateChanges(func_get_arg(0));
        $this->serialNumber = $serialNumber;
        return $this;
    }

    public function getSerialNumber()
    {
        return $this->serialNumber;
    }

    public function setSubsystemList($subsystemList)
    {
        $this->updateChanges(func_get_arg(0));
        $this->subsystemList = $subsystemList;
        return $this;
    }

    public function getSubsystemList()
    {
        return $this->subsystemList;
    }

    public function setSubsystemListId($subsystemListId)
    {
        $this->updateChanges(func_get_arg(0));
        $this->subsystemListId = $subsystemListId;
        return $this;
    }

    public function getSubsystemListId()
    {
        return $this->subsystemListId;
    }

    public function setSysClassName($sysClassName)
    {
        $this->updateChanges(func_get_arg(0));
        $this->sysClassName = $sysClassName;
        return $this;
    }

    public function getSysClassName()
    {
        return $this->sysClassName;
    }

    public function setSysCreatedBy($sysCreatedBy)
    {
        $this->updateChanges(func_get_arg(0));
        $this->sysCreatedBy = $sysCreatedBy;
        return $this;
    }

    public function getSysCreatedBy()
    {
        return $this->sysCreatedBy;
    }

    public function setSysCreatedOn($sysCreatedOn)
    {
        $this->updateChanges(func_get_arg(0));
        $this->sysCreatedOn = $sysCreatedOn;
        return $this;
    }

    public function getSysCreatedOn()
    {
        return $this->sysCreatedOn;
    }

    public function setSysId($sysId)
    {
        $this->updateChanges(func_get_arg(0));
        $this->sysId = $sysId;
        return $this;
    }

    public function getSysId()
    {
        return $this->sysId;
    }

    public function setId($Id)
    {
        $this->updateChanges(func_get_arg(0));
        $this->sysId = $Id;
        return $this;
    }

    public function getId()
    {
        return $this->sysId;
    }

    public function setSysUpdatedBy($sysUpdatedBy)
    {
        $this->updateChanges(func_get_arg(0));
        $this->sysUpdatedBy = $sysUpdatedBy;
        return $this;
    }

    public function getSysUpdatedBy()
    {
        return $this->sysUpdatedBy;
    }

    public function setSysUpdatedOn($sysUpdatedOn)
    {
        $this->updateChanges(func_get_arg(0));
        $this->sysUpdatedOn = $sysUpdatedOn;
        return $this;
    }

    public function getSysUpdatedOn()
    {
        return $this->sysUpdatedOn;
    }

    public function setRack($rack)
    {
        $this->updateChanges(func_get_arg(0));
        $this->rack = $rack;
        return $this;
    }

    public function getRack()
    {
        return $this->rack;
    }

    public function setNumberOfRackUnits($numberOfRackUnits)
    {
        $this->updateChanges(func_get_arg(0));
        $this->numberOfRackUnits = $numberOfRackUnits;
        return $this;
    }

    public function getNumberOfRackUnits()
    {
        return $this->numberOfRackUnits;
    }

    public function setRackPosition($rackPosition)
    {
        $this->updateChanges(func_get_arg(0));
        $this->rackPosition = $rackPosition;
        return $this;
    }

    public function getRackPosition()
    {
        return $this->rackPosition;
    }

    public function setLocationId($locationId)
    {
        $this->updateChanges(func_get_arg(0));
        $this->locationId = $locationId;
        return $this;
    }

    public function getLocationId()
    {
        return $this->locationId;
    }

    /**
     * @param $rackId
     * @return $this
     */
    public function setRackId($rackId)
    {
        $this->updateChanges(func_get_arg(0));
        $this->rackId = $rackId;
        return $this;
    }

    public function getRackId()
    {
        return $this->rackId;
    }

    public function setInstallStatus($installStatus)
    {
        $this->updateChanges(func_get_arg(0));
        $this->installStatus = $installStatus;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getInstallStatus()
    {
        return $this->installStatus;
    }

    public function setInstallStatusId($installStatusId)
    {
        $this->updateChanges(func_get_arg(0));
        $this->installStatusId = $installStatusId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getInstallStatusId()
    {
        return $this->installStatusId;
    }

    public function setLocationType($locationType)
    {
        $this->updateChanges(func_get_arg(0));
        $this->locationType = $locationType;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLocationType()
    {
        return $this->locationType;
    }

    public function setLocationTypeId($locationTypeId)
    {
        $this->updateChanges(func_get_arg(0));
        $this->locationTypeId = $locationTypeId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLocationTypeId()
    {
        return $this->locationTypeId;
    }

    public function setBusinessServices($businessServices)
    {
        $this->updateChanges(func_get_arg(0));
        $this->businessServices = $businessServices;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getBusinessServices()
    {
        return $this->businessServices;
    }

    public function setBusinessServicesIds($businessServicesIds)
    {
        $this->updateChanges(func_get_arg(0));
        $this->businessServicesIds = $businessServicesIds;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getBusinessServicesIds()
    {
        return $this->businessServicesIds;
    }

    /**
     * @param mixed $deviceType
     * @return $this
     */
    public function setDeviceType($deviceType)
    {
        $this->updateChanges(func_get_arg(0));
        $this->deviceType = $deviceType;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDeviceType()
    {
        return $this->deviceType;
    }

    /**
     * @param mixed $deviceTypeId
     * @return $this
     */
    public function setDeviceTypeId($deviceTypeId)
    {
        $this->updateChanges(func_get_arg(0));
        $this->deviceTypeId = $deviceTypeId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDeviceTypeId()
    {
        return $this->deviceTypeId;
    }

    /**
     * @param mixed $dataSource
     * @return $this
     */
    public function setDataSource($dataSource)
    {
        $this->updateChanges(func_get_arg(0));
        $this->dataSource = $dataSource;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDataSource()
    {
        return $this->dataSource;
    }

    /**
     * @param mixed $environmentId
     * @return $this
     */
    public function setEnvironmentId($environmentId)
    {
        $this->updateChanges(func_get_arg(0));
        $this->environmentId = $environmentId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEnvironmentId()
    {
        return $this->environmentId;
    }

}
