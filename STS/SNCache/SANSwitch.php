<?php

namespace STS\SNCache;

class SANSwitch
{
    protected $sysId;
    protected $sysClassName;

    protected $name;
    protected $assetTag;

    protected $installStatus;
    protected $installStatusId;

    protected $deviceTypeId;
    protected $deviceType;

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


    /**
     * @return string
     */
    public function __toString()
   	{
           $return = "";
           foreach (get_class_vars(__CLASS__) as $prop => $x) {
               if (property_exists($this, $prop)) {
                   $return .= sprintf("%-25s => %s\n", $prop, $this->$prop);
               }
           }
           return $return;
   	}

    /**
     * @return object
     */
    public function toObject()
   	{
           $obj = (object)array();
           foreach (get_class_vars(__CLASS__) as $prop => $x) {
               if (property_exists($this, $prop)) {
                   $obj->$prop = $this->$prop;
               }
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

    /**
     * @param $assetTag
     * @return $this
     */
    public function setAssetTag($assetTag)
    {
        $this->updateChanges(func_get_arg(0));
        $this->assetTag = $assetTag;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAssetTag()
    {
        return $this->assetTag;
    }

    /**
     * @param $businessService
     * @return $this
     */
    public function setBusinessService($businessService)
    {
        $this->updateChanges(func_get_arg(0));
        $this->businessService = $businessService;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getBusinessService()
    {
        return $this->businessService;
    }

    /**
     * @param $businessServiceId
     * @return $this
     */
    public function setBusinessServiceId($businessServiceId)
    {
        $this->updateChanges(func_get_arg(0));
        $this->businessServiceId = $businessServiceId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getBusinessServiceId()
    {
        return $this->businessServiceId;
    }

    /**
     * @param $environment
     * @return $this
     */
    public function setEnvironment($environment)
    {
        $this->updateChanges(func_get_arg(0));
        $this->environment = $environment;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEnvironment()
    {
        return $this->environment;
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
     * @param $manufacturer
     * @return $this
     */
    public function setManufacturer($manufacturer)
    {
        $this->updateChanges(func_get_arg(0));
        $this->manufacturer = $manufacturer;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getManufacturer()
    {
        return $this->manufacturer;
    }

    /**
     * @param $manufacturerId
     * @return $this
     */
    public function setManufacturerId($manufacturerId)
    {
        $this->updateChanges(func_get_arg(0));
        $this->manufacturerId = $manufacturerId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getManufacturerId()
    {
        return $this->manufacturerId;
    }

    /**
     * @param $modelNumber
     * @return $this
     */
    public function setModelNumber($modelNumber)
    {
        $this->updateChanges(func_get_arg(0));
        $this->modelNumber = $modelNumber;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getModelNumber()
    {
        return $this->modelNumber;
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
     * @param $serialNumber
     * @return $this
     */
    public function setSerialNumber($serialNumber)
    {
        $this->updateChanges(func_get_arg(0));
        $this->serialNumber = $serialNumber;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSerialNumber()
    {
        return $this->serialNumber;
    }

    /**
     * @param $subsystemList
     * @return $this
     */
    public function setSubsystemList($subsystemList)
    {
        $this->updateChanges(func_get_arg(0));
        $this->subsystemList = $subsystemList;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSubsystemList()
    {
        return $this->subsystemList;
    }

    /**
     * @param $subsystemListId
     * @return $this
     */
    public function setSubsystemListId($subsystemListId)
    {
        $this->updateChanges(func_get_arg(0));
        $this->subsystemListId = $subsystemListId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSubsystemListId()
    {
        return $this->subsystemListId;
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
     * @param $id
     * @return $this
     */
    public function setId($id)
    {
        $this->updateChanges(func_get_arg(0));
        $this->sysId = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->sysId;
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
     * @param $rackPosition
     * @return $this
     */
    public function setRackPosition($rackPosition)
    {
        $this->updateChanges(func_get_arg(0));
        $this->rackPosition = $rackPosition;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRackPosition()
    {
        return $this->rackPosition;
    }

    /**
     * @param $numberOfRackUnits
     * @return $this
     */
    public function setNumberOfRackUnits($numberOfRackUnits)
    {
        $this->updateChanges(func_get_arg(0));
        $this->numberOfRackUnits = $numberOfRackUnits;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNumberOfRackUnits()
    {
        return $this->numberOfRackUnits;
    }

    /**
     * @param $rack
     * @return $this
     */
    public function setRack($rack)
    {
        $this->updateChanges(func_get_arg(0));
        $this->rack = $rack;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRack()
    {
        return $this->rack;
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
     * @param $rackId
     * @return $this
     */
    public function setRackId($rackId)
    {
        $this->updateChanges(func_get_arg(0));
        $this->rackId = $rackId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRackId()
    {
        return $this->rackId;
    }

    /**
     * @param $installStatus
     * @return $this
     */
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

    /**
     * @param $installStatusId
     * @return $this
     */
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

    /**
     * @param $locationType
     * @return $this
     */
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

    /**
     * @param $locationTypeId
     * @return $this
     */
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

    /**
     * @param $businessServices
     * @return $this
     */
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

    /**
     * @param $businessServicesIds
     * @return $this
     */
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
     * @param $deviceType
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
     * @param $deviceTypeId
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
