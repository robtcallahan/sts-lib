<?php
/*******************************************************************************
 *
 * $Id: CMDBServer.php 82444 2014-01-03 14:28:59Z rcallaha $
 * $Date: 2014-01-03 09:28:59 -0500 (Fri, 03 Jan 2014) $
 * $Author: rcallaha $
 * $Revision: 82444 $
 * $HeadURL: https://svn.ultradns.net/svn/sts_tools/sts-lib/trunk/STS/CMDB/CMDBServer.php $
 *
 *******************************************************************************
 */

namespace STS\CMDB;

class CMDBServer
{
    protected $sysId;
    protected $sysClassName;

    protected $name;
    protected $classification;
    protected $serialNumber;
    protected $assetTag;

    protected $businessService;
    protected $businessServiceId;
    protected $businessServices;
    protected $businessServicesIds;

    protected $subsystemListId;
    protected $subsystemList;

    protected $installStatus;
    protected $installStatusId;
    protected $firewallStatus;
    protected $hardwareStatus;
    protected $operationalStatus;

    protected $environment;
    protected $environmentId;

    protected $hostedOnId;
    protected $hostedOn;

    protected $deviceType;
    protected $deviceTypeId;
    protected $locationType;
    protected $locationTypeId;

    protected $isVirtual;

    protected $location;
    protected $locationId;
    protected $rackId;
    protected $rack;
    protected $numberOfRackUnits;
    protected $rackPosition;

    protected $manufacturerId;
    protected $manufacturer;
    protected $modelNumber;

    protected $comments;
    protected $shortDescription;

    protected $distributionSwitch;

    protected $maintContractStartDate;
    protected $maintContractEndDate;

    protected $ipAddress;

    protected $cpuManufacturerId;
    protected $cpuManufacturer;
    protected $cpuCount;
    protected $cpuCoreCount;
    protected $cpuName;
    protected $cpuSpeed;
    protected $cpuType;

    protected $ram;
    protected $diskSpace;

    protected $os;
    protected $osVersion;
    protected $osServicePack;
    protected $powerpathVersion;

    protected $watts;

    protected $lastBackupDate;
    protected $backupDirectories;

    protected $inEHealth;

    protected $installDate;
    protected $biosDate;
    protected $lastDdmiUpdate;
    protected $lastDiscovered;

    protected $dataSource;
    protected $discoverySource;
    protected $attributes;
    protected $syncUpdate;

    protected $sysCreatedBy;
    protected $sysCreatedOn;
    protected $sysUpdatedBy;
    protected $sysUpdatedOn;

    protected $changes = array();


    public function __toString()
    {
        $return = "";
        foreach (CMDBServerTable::getNameMapping() as $prop) {
            $return .= sprintf("%-25s => %s\n", $prop, $this->$prop);
        }
        return $return;
    }

    public function toObject()
    {
        $obj = (object)array();
        foreach (CMDBServerTable::getNameMapping() as $prop) {
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

    public function set($prop, $value) {
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
            $this->$prop = $value;
        }
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

    public function setBackupDirectories($backupDirectories)
    {
        $this->updateChanges(func_get_arg(0));
        $this->backupDirectories = $backupDirectories;
        return $this;
    }

    public function getBackupDirectories()
    {
        return $this->backupDirectories;
    }

    public function setBiosDate($biosDate)
    {
        $this->updateChanges(func_get_arg(0));
        $this->biosDate = $biosDate;
        return $this;
    }

    public function getBiosDate()
    {
        return $this->biosDate;
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

    public function setClassification($classification)
    {
        $this->updateChanges(func_get_arg(0));
        $this->classification = $classification;
        return $this;
    }

    public function getClassification()
    {
        return $this->classification;
    }

    public function setComments($comments)
    {
        $this->updateChanges(func_get_arg(0));
        $this->comments = $comments;
        return $this;
    }

    public function getComments()
    {
        return $this->comments;
    }

    public function setCpuCoreCount($cpuCoreCount)
    {
        $this->updateChanges(func_get_arg(0));
        $this->cpuCoreCount = $cpuCoreCount;
        return $this;
    }

    public function getCpuCoreCount()
    {
        return $this->cpuCoreCount;
    }

    public function setCpuCount($cpuCount)
    {
        $this->updateChanges(func_get_arg(0));
        $this->cpuCount = $cpuCount;
        return $this;
    }

    public function getCpuCount()
    {
        return $this->cpuCount;
    }

    public function setCpuManufacturer($cpuManufacturer)
    {
        $this->updateChanges(func_get_arg(0));
        $this->cpuManufacturer = $cpuManufacturer;
        return $this;
    }

    public function getCpuManufacturer()
    {
        return $this->cpuManufacturer;
    }

    public function setCpuManufacturerId($cpuManufacturerId)
    {
        $this->updateChanges(func_get_arg(0));
        $this->cpuManufacturerId = $cpuManufacturerId;
        return $this;
    }

    public function getCpuManufacturerId()
    {
        return $this->cpuManufacturerId;
    }

    public function setCpuName($cpuName)
    {
        $this->updateChanges(func_get_arg(0));
        $this->cpuName = $cpuName;
        return $this;
    }

    public function getCpuName()
    {
        return $this->cpuName;
    }

    public function setCpuSpeed($cpuSpeed)
    {
        $this->updateChanges(func_get_arg(0));
        $this->cpuSpeed = $cpuSpeed;
        return $this;
    }

    public function getCpuSpeed()
    {
        return $this->cpuSpeed;
    }

    public function setCpuType($cpuType)
    {
        $this->updateChanges(func_get_arg(0));
        $this->cpuType = $cpuType;
        return $this;
    }

    public function getCpuType()
    {
        return $this->cpuType;
    }

    public function setDistributionSwitch($distributionSwitch)
    {
        $this->updateChanges(func_get_arg(0));
        $this->distributionSwitch = $distributionSwitch;
        return $this;
    }

    public function getDistributionSwitch()
    {
        return $this->distributionSwitch;
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

    public function setFirewallStatus($firewallStatus)
    {
        $this->updateChanges(func_get_arg(0));
        $this->firewallStatus = $firewallStatus;
        return $this;
    }

    public function getFirewallStatus()
    {
        return $this->firewallStatus;
    }

    public function setHardwareStatus($hardwareStatus)
    {
        $this->updateChanges(func_get_arg(0));
        $this->hardwareStatus = $hardwareStatus;
        return $this;
    }

    public function getHardwareStatus()
    {
        return $this->hardwareStatus;
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
     * Backwards compatability to host type. Changed to device type so all CIs are now consistent
     * @param $hostType
     * @return $this
     */
    public function setHostType($hostType)
    {
        return $this->setDeviceType($hostType);
    }

    /**
     * @return mixed
     */
    public function getDeviceType()
    {
        return $this->deviceType;
    }

    /**
     * Backwards compatability to host type. Changed to device type so all CIs are now consistent
     * @return mixed
     */
    public function getHostType()
    {
        return $this->getDeviceType();
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
     * Backwards compatability to host type. Changed to device type so all CIs are now consistent
     * @param $hostTypeId
     * @return $this
     */
    public function setHostTypeId($hostTypeId)
    {
        return $this->setDeviceTypeId($hostTypeId);
    }

    /**
     * @return mixed
     */
    public function getDeviceTypeId()
    {
        return $this->deviceTypeId;
    }

    /**
     * Backwards compatability to host type. Changed to device type so all CIs are now consistent
     * @return mixed
     */
    public function getHostTypeId()
    {
        return $this->getDeviceTypeId();
    }

    public function setHostedOn($hostedOn)
    {
        $this->updateChanges(func_get_arg(0));
        $this->hostedOn = $hostedOn;
        return $this;
    }

    public function getHostedOn()
    {
        return $this->hostedOn;
    }

    public function setHostedOnId($hostedOnId)
    {
        $this->updateChanges(func_get_arg(0));
        $this->hostedOnId = $hostedOnId;
        return $this;
    }

    public function getHostedOnId()
    {
        return $this->hostedOnId;
    }

    public function setInEHealth($inEHealth)
    {
        $this->updateChanges(func_get_arg(0));
        $this->inEHealth = $inEHealth;
        return $this;
    }

    public function getInEHealth()
    {
        return $this->inEHealth;
    }

    public function setInstallStatus($installStatus)
    {
        $this->updateChanges(func_get_arg(0));
        $this->installStatus = $installStatus;
        return $this;
    }

    public function getInstallStatus()
    {
        return $this->installStatus;
    }

    public function setLastBackupDate($lastBackupDate)
    {
        $this->updateChanges(func_get_arg(0));
        $this->lastBackupDate = $lastBackupDate;
        return $this;
    }

    public function getLastBackupDate()
    {
        return $this->lastBackupDate;
    }

    public function setLastDdmiUpdate($lastDdmiUpdate)
    {
        $this->updateChanges(func_get_arg(0));
        $this->lastDdmiUpdate = $lastDdmiUpdate;
        return $this;
    }

    public function getLastDdmiUpdate()
    {
        return $this->lastDdmiUpdate;
    }

    public function setLastDiscovered($lastDiscovered)
    {
        $this->updateChanges(func_get_arg(0));
        $this->lastDiscovered = $lastDiscovered;
        return $this;
    }

    public function getLastDiscovered()
    {
        return $this->lastDiscovered;
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

    public function setMaintContractEndDate($maintContractEndDate)
    {
        $this->updateChanges(func_get_arg(0));
        $this->maintContractEndDate = $maintContractEndDate;
        return $this;
    }

    public function getMaintContractEndDate()
    {
        return $this->maintContractEndDate;
    }

    public function setMaintContractStartDate($maintContractStartDate)
    {
        $this->updateChanges(func_get_arg(0));
        $this->maintContractStartDate = $maintContractStartDate;
        return $this;
    }

    public function getMaintContractStartDate()
    {
        return $this->maintContractStartDate;
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

    public function setOperationalStatus($operationalStatus)
    {
        $this->updateChanges(func_get_arg(0));
        $this->operationalStatus = $operationalStatus;
        return $this;
    }

    public function getOperationalStatus()
    {
        return $this->operationalStatus;
    }

    public function setOs($os)
    {
        $this->updateChanges(func_get_arg(0));
        $this->os = $os;
        return $this;
    }

    public function getOs()
    {
        return $this->os;
    }

    public function setOsServicePack($osServicePack)
    {
        $this->updateChanges(func_get_arg(0));
        $this->osServicePack = $osServicePack;
        return $this;
    }

    public function getOsServicePack()
    {
        return $this->osServicePack;
    }

    public function setOsVersion($osVersion)
    {
        $this->updateChanges(func_get_arg(0));
        $this->osVersion = $osVersion;
        return $this;
    }

    public function getOsVersion()
    {
        return $this->osVersion;
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

    public function setRam($ram)
    {
        $this->updateChanges(func_get_arg(0));
        $this->ram = $ram;
        return $this;
    }

    public function getRam()
    {
        return $this->ram;
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

    public function setShortDescription($shortDescription)
    {
        $this->updateChanges(func_get_arg(0));
        $this->shortDescription = $shortDescription;
        return $this;
    }

    public function getShortDescription()
    {
        return $this->shortDescription;
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

    public function setWatts($watts)
    {
        $this->updateChanges(func_get_arg(0));
        $this->watts = $watts;
        return $this;
    }

    public function getWatts()
    {
        return $this->watts;
    }

    public function setInstallStatusId($installStatusId)
    {
        $this->updateChanges(func_get_arg(0));
        $this->installStatusId = $installStatusId;
        return $this;
    }

    public function getInstallStatusId()
    {
        return $this->installStatusId;
    }

    public function setPowerpathVersion($powerpathVersion)
    {
        $this->updateChanges(func_get_arg(0));
        $this->powerpathVersion = $powerpathVersion;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPowerpathVersion()
    {
        return $this->powerpathVersion;
    }

    public function setIpAddress($ipAddress)
    {
        $this->updateChanges(func_get_arg(0));
        $this->ipAddress = $ipAddress;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIpAddress()
    {
        return $this->ipAddress;
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
     * @param mixed $isVirtual
     * @return $this
     */
    public function setIsVirtual($isVirtual) {
        $this->updateChanges(func_get_arg(0));
        $this->isVirtual = $isVirtual;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIsVirtual() {
        return $this->isVirtual;
    }

    /**
     * @param mixed $diskSpace
     * @return $this
     */
    public function setDiskSpace($diskSpace) {
        $this->updateChanges(func_get_arg(0));
        $this->diskSpace = $diskSpace;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDiskSpace() {
        return $this->diskSpace;
    }

    /**
     * @param mixed $attributes
     * @return $this
     */
    public function setAttributes($attributes) {
        $this->updateChanges(func_get_arg(0));
        $this->attributes = $attributes;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAttributes() {
        return $this->attributes;
    }

    /**
     * @param mixed $installDate
     * @return $this
     */
    public function setInstallDate($installDate) {
        $this->updateChanges(func_get_arg(0));
        $this->installDate = $installDate;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getInstallDate() {
        return $this->installDate;
    }

    /**
     * @return mixed
     */
    public function getDiscoverySource() {
        return $this->discoverySource;
    }

    /**
     * @param mixed $discoverySource
     * @return $this
     */
    public function setDiscoverySource($discoverySource) {
        $this->updateChanges(func_get_arg(0));
        $this->discoverySource = $discoverySource;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSyncUpdate() {
        return $this->syncUpdate;
    }

    /**
     * @param mixed $syncUpdate
     * @return $this
     */
    public function setSyncUpdate($syncUpdate) {
        $this->updateChanges(func_get_arg(0));
        $this->syncUpdate = $syncUpdate;
        return $this;
    }

}
