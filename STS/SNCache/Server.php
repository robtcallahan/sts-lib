<?php

namespace STS\SNCache;

class Server
{
    protected $sysId;
    protected $sysClassName;

    protected $name;
    protected $classification;
    protected $serialNumber;
    protected $assetTag;

    protected $businessServices;
    protected $businessServiceIds;
    protected $subsystemList;
    protected $subsystemListIds;

    protected $installStatus;
    protected $installStatusId;
    protected $environment;
    protected $environmentId;

    protected $hostedOn;
    protected $hostedOnId;

    protected $distributionSwitch;

    protected $deviceType;
    protected $deviceTypeId;

    protected $location;
    protected $locationId;
    protected $rack;
    protected $rackId;
    protected $numberOfRackUnits;
    protected $rackPosition;

    protected $ipAddress;

    protected $cpuManufacturer;
    protected $cpuManufacturerId;
    protected $cpuCount;
    protected $cpuCoreCount;
    protected $cpuSpeed;
    protected $cpuType;

    protected $manufacturer;
    protected $manufacturerId;
    protected $modelNumber;

    protected $ram;
    protected $diskSpace;

    protected $os;
    protected $osVersion;
    protected $osServicePack;

    protected $powerpathVersion;
    protected $biosDate;

    protected $comments;
    protected $shortDescription;

    protected $installDate;

    protected $lastBackupDate;
    protected $backupDirectories;

    protected $dataSource;
    protected $discoverySource;
    protected $lastDiscovered;

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
            if (property_exists($this, $prop) && $prop != "changes") {
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
    // Getters and Setters
    // *******************************************************************************

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
        $this->$prop = $value;
        return $this;
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

    /**
     * @param mixed $assetTag
     * @return $this
     */
    public function setAssetTag($assetTag) {
        $this->updateChanges(func_get_arg(0));
        $this->assetTag = $assetTag;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAssetTag() {
        return $this->assetTag;
    }

    /**
     * @param mixed $businessServiceIds
     * @return $this
     */
    public function setBusinessServiceIds($businessServiceIds) {
        $this->updateChanges(func_get_arg(0));
        $this->businessServiceIds = $businessServiceIds;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getBusinessServiceIds() {
        return $this->businessServiceIds;
    }

    /**
     * @param mixed $businessServices
     * @return $this
     */
    public function setBusinessServices($businessServices) {
        $this->updateChanges(func_get_arg(0));
        $this->businessServices = $businessServices;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getBusinessServices() {
        return $this->businessServices;
    }

    /**
     * @param mixed $classification
     * @return $this
     */
    public function setClassification($classification) {
        $this->updateChanges(func_get_arg(0));
        $this->classification = $classification;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getClassification() {
        return $this->classification;
    }

    /**
     * @param mixed $comments
     * @return $this
     */
    public function setComments($comments) {
        $this->updateChanges(func_get_arg(0));
        $this->comments = $comments;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getComments() {
        return $this->comments;
    }

    /**
     * @param mixed $cpuCoreCount
     * @return $this
     */
    public function setCpuCoreCount($cpuCoreCount) {
        $this->updateChanges(func_get_arg(0));
        $this->cpuCoreCount = $cpuCoreCount;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCpuCoreCount() {
        return $this->cpuCoreCount;
    }

    /**
     * @param mixed $cpuCount
     * @return $this
     */
    public function setCpuCount($cpuCount) {
        $this->updateChanges(func_get_arg(0));
        $this->cpuCount = $cpuCount;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCpuCount() {
        return $this->cpuCount;
    }

    /**
     * @param mixed $cpuManufacturer
     * @return $this
     */
    public function setCpuManufacturer($cpuManufacturer) {
        $this->updateChanges(func_get_arg(0));
        $this->cpuManufacturer = $cpuManufacturer;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCpuManufacturer() {
        return $this->cpuManufacturer;
    }

    /**
     * @param mixed $cpuManufacturerId
     * @return $this
     */
    public function setCpuManufacturerId($cpuManufacturerId) {
        $this->updateChanges(func_get_arg(0));
        $this->cpuManufacturerId = $cpuManufacturerId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCpuManufacturerId() {
        return $this->cpuManufacturerId;
    }

    /**
     * @param mixed $cpuSpeed
     * @return $this
     */
    public function setCpuSpeed($cpuSpeed) {
        $this->updateChanges(func_get_arg(0));
        $this->cpuSpeed = $cpuSpeed;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCpuSpeed() {
        return $this->cpuSpeed;
    }

    /**
     * @param mixed $cpuType
     * @return $this
     */
    public function setCpuType($cpuType) {
        $this->updateChanges(func_get_arg(0));
        $this->cpuType = $cpuType;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCpuType() {
        return $this->cpuType;
    }

    /**
     * @param mixed $deviceType
     * @return $this
     */
    public function setDeviceType($deviceType) {
        $this->updateChanges(func_get_arg(0));
        $this->deviceType = $deviceType;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDeviceType() {
        return $this->deviceType;
    }

    /**
     * @param mixed $deviceTypeId
     * @return $this
     */
    public function setDeviceTypeId($deviceTypeId) {
        $this->updateChanges(func_get_arg(0));
        $this->deviceTypeId = $deviceTypeId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDeviceTypeId() {
        return $this->deviceTypeId;
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
     * @param mixed $distributionSwitch
     * @return $this
     */
    public function setDistributionSwitch($distributionSwitch) {
        $this->updateChanges(func_get_arg(0));
        $this->distributionSwitch = $distributionSwitch;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDistributionSwitch() {
        return $this->distributionSwitch;
    }

    /**
     * @param mixed $environment
     * @return $this
     */
    public function setEnvironment($environment) {
        $this->updateChanges(func_get_arg(0));
        $this->environment = $environment;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEnvironment() {
        return $this->environment;
    }

    /**
     * @param mixed $environmentId
     * @return $this
     */
    public function setEnvironmentId($environmentId) {
        $this->updateChanges(func_get_arg(0));
        $this->environmentId = $environmentId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEnvironmentId() {
        return $this->environmentId;
    }

    /**
     * @param mixed $hostedOn
     * @return $this
     */
    public function setHostedOn($hostedOn) {
        $this->updateChanges(func_get_arg(0));
        $this->hostedOn = $hostedOn;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getHostedOn() {
        return $this->hostedOn;
    }

    /**
     * @param mixed $hostedOnId
     * @return $this
     */
    public function setHostedOnId($hostedOnId) {
        $this->updateChanges(func_get_arg(0));
        $this->hostedOnId = $hostedOnId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getHostedOnId() {
        return $this->hostedOnId;
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
     * @param mixed $installStatus
     * @return $this
     */
    public function setInstallStatus($installStatus) {
        $this->updateChanges(func_get_arg(0));
        $this->installStatus = $installStatus;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getInstallStatus() {
        return $this->installStatus;
    }

    /**
     * @param mixed $installStatusId
     * @return $this
     */
    public function setInstallStatusId($installStatusId) {
        $this->updateChanges(func_get_arg(0));
        $this->installStatusId = $installStatusId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getInstallStatusId() {
        return $this->installStatusId;
    }

    /**
     * @param mixed $ipAddress
     * @return $this
     */
    public function setIpAddress($ipAddress) {
        $this->updateChanges(func_get_arg(0));
        $this->ipAddress = $ipAddress;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIpAddress() {
        return $this->ipAddress;
    }

    /**
     * @param mixed $location
     * @return $this
     */
    public function setLocation($location) {
        $this->updateChanges(func_get_arg(0));
        $this->location = $location;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLocation() {
        return $this->location;
    }

    /**
     * @param mixed $locationId
     * @return $this
     */
    public function setLocationId($locationId) {
        $this->updateChanges(func_get_arg(0));
        $this->locationId = $locationId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLocationId() {
        return $this->locationId;
    }

    /**
     * @param mixed $manufacturer
     * @return $this
     */
    public function setManufacturer($manufacturer) {
        $this->updateChanges(func_get_arg(0));
        $this->manufacturer = $manufacturer;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getManufacturer() {
        return $this->manufacturer;
    }

    /**
     * @param mixed $manufacturerId
     * @return $this
     */
    public function setManufacturerId($manufacturerId) {
        $this->updateChanges(func_get_arg(0));
        $this->manufacturerId = $manufacturerId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getManufacturerId() {
        return $this->manufacturerId;
    }

    /**
     * @param mixed $modelNumber
     * @return $this
     */
    public function setModelNumber($modelNumber) {
        $this->updateChanges(func_get_arg(0));
        $this->modelNumber = $modelNumber;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getModelNumber() {
        return $this->modelNumber;
    }

    /**
     * @param mixed $name
     * @return $this
     */
    public function setName($name) {
        $this->updateChanges(func_get_arg(0));
        $this->name = $name;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @param mixed $numberOfRackUnits
     * @return $this
     */
    public function setNumberOfRackUnits($numberOfRackUnits) {
        $this->updateChanges(func_get_arg(0));
        $this->numberOfRackUnits = $numberOfRackUnits;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNumberOfRackUnits() {
        return $this->numberOfRackUnits;
    }

    /**
     * @param mixed $os
     * @return $this
     */
    public function setOs($os) {
        $this->updateChanges(func_get_arg(0));
        $this->os = $os;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getOs() {
        return $this->os;
    }

    /**
     * @param mixed $osServicePack
     * @return $this
     */
    public function setOsServicePack($osServicePack) {
        $this->updateChanges(func_get_arg(0));
        $this->osServicePack = $osServicePack;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getOsServicePack() {
        return $this->osServicePack;
    }

    /**
     * @param mixed $osVersion
     * @return $this
     */
    public function setOsVersion($osVersion) {
        $this->updateChanges(func_get_arg(0));
        $this->osVersion = $osVersion;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getOsVersion() {
        return $this->osVersion;
    }

    /**
     * @param mixed $rack
     * @return $this
     */
    public function setRack($rack) {
        $this->updateChanges(func_get_arg(0));
        $this->rack = $rack;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRack() {
        return $this->rack;
    }

    /**
     * @param mixed $rackId
     * @return $this
     */
    public function setRackId($rackId) {
        $this->updateChanges(func_get_arg(0));
        $this->rackId = $rackId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRackId() {
        return $this->rackId;
    }

    /**
     * @param mixed $rackPosition
     * @return $this
     */
    public function setRackPosition($rackPosition) {
        $this->updateChanges(func_get_arg(0));
        $this->rackPosition = $rackPosition;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRackPosition() {
        return $this->rackPosition;
    }

    /**
     * @param mixed $ram
     * @return $this
     */
    public function setRam($ram) {
        $this->updateChanges(func_get_arg(0));
        $this->ram = $ram;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRam() {
        return $this->ram;
    }

    /**
     * @param mixed $serialNumber
     * @return $this
     */
    public function setSerialNumber($serialNumber) {
        $this->updateChanges(func_get_arg(0));
        $this->serialNumber = $serialNumber;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSerialNumber() {
        return $this->serialNumber;
    }

    /**
     * @param mixed $shortDescription
     * @return $this
     */
    public function setShortDescription($shortDescription) {
        $this->updateChanges(func_get_arg(0));
        $this->shortDescription = $shortDescription;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getShortDescription() {
        return $this->shortDescription;
    }

    /**
     * @param mixed $subsystemList
     * @return $this
     */
    public function setSubsystemList($subsystemList) {
        $this->updateChanges(func_get_arg(0));
        $this->subsystemList = $subsystemList;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSubsystemList() {
        return $this->subsystemList;
    }

    /**
     * @param mixed $subsystemListIds
     * @return $this
     */
    public function setSubsystemListIds($subsystemListIds) {
        $this->updateChanges(func_get_arg(0));
        $this->subsystemListIds = $subsystemListIds;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSubsystemListIds() {
        return $this->subsystemListIds;
    }

    /**
     * @param mixed $sysClassName
     * @return $this
     */
    public function setSysClassName($sysClassName) {
        $this->updateChanges(func_get_arg(0));
        $this->sysClassName = $sysClassName;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSysClassName() {
        return $this->sysClassName;
    }

    /**
     * @param mixed $sysCreatedBy
     * @return $this
     */
    public function setSysCreatedBy($sysCreatedBy) {
        $this->updateChanges(func_get_arg(0));
        $this->sysCreatedBy = $sysCreatedBy;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSysCreatedBy() {
        return $this->sysCreatedBy;
    }

    /**
     * @param mixed $sysCreatedOn
     * @return $this
     */
    public function setSysCreatedOn($sysCreatedOn) {
        $this->updateChanges(func_get_arg(0));
        $this->sysCreatedOn = $sysCreatedOn;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSysCreatedOn() {
        return $this->sysCreatedOn;
    }

    /**
     * @param mixed $sysId
     * @return $this
     */
    public function setSysId($sysId) {
        $this->updateChanges(func_get_arg(0));
        $this->sysId = $sysId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSysId() {
        return $this->sysId;
    }

    /**
     * @param mixed $sysUpdatedBy
     * @return $this
     */
    public function setSysUpdatedBy($sysUpdatedBy) {
        $this->updateChanges(func_get_arg(0));
        $this->sysUpdatedBy = $sysUpdatedBy;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSysUpdatedBy() {
        return $this->sysUpdatedBy;
    }

    /**
     * @param mixed $sysUpdatedOn
     * @return $this
     */
    public function setSysUpdatedOn($sysUpdatedOn) {
        $this->updateChanges(func_get_arg(0));
        $this->sysUpdatedOn = $sysUpdatedOn;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSysUpdatedOn() {
        return $this->sysUpdatedOn;
    }

    /**
     * @return mixed
     */
    public function getDataSource() {
        return $this->dataSource;
    }

    /**
     * @param mixed $dataSource
     * @return $this
     */
    public function setDataSource($dataSource) {
        $this->updateChanges(func_get_arg(0));
        $this->dataSource = $dataSource;
        return $this;
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
    public function getLastBackupDate() {
        return $this->lastBackupDate;
    }

    /**
     * @param mixed $lastBackupDate
     * @return $this
     */
    public function setLastBackupDate($lastBackupDate) {
        $this->updateChanges(func_get_arg(0));
        $this->lastBackupDate = $lastBackupDate;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getBackupDirectories() {
        return $this->backupDirectories;
    }

    /**
     * @param mixed $backupDirectories
     * @return $this
     */
    public function setBackupDirectories($backupDirectories) {
        $this->updateChanges(func_get_arg(0));
        $this->backupDirectories = $backupDirectories;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPowerpathVersion() {
        return $this->powerpathVersion;
    }

    /**
     * @param mixed $powerpathVersion
     * @return $this
     */
    public function setPowerpathVersion($powerpathVersion) {
        $this->updateChanges(func_get_arg(0));
        $this->powerpathVersion = $powerpathVersion;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getBiosDate() {
        return $this->biosDate;
    }

    /**
     * @param mixed $biosDate
     * @return $this
     */
    public function setBiosDate($biosDate) {
        $this->updateChanges(func_get_arg(0));
        $this->biosDate = $biosDate;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLastDiscovered() {
        return $this->lastDiscovered;
    }

    /**
     * @param mixed $lastDiscovered
     * @return $this
     */
    public function setLastDiscovered($lastDiscovered) {
        $this->updateChanges(func_get_arg(0));
        $this->lastDiscovered = $lastDiscovered;
        return $this;
    }


}
