<?php

namespace STS\HPSIM;

class HPSIMBlade
{
	// from HP HPSIM
	protected $id;
    protected $hsUid;
    protected $ccrName;
	protected $chassisId;
	protected $deviceName;
	protected $fullDnsName;

	protected $hwStatus;
	protected $mpStatus;
	protected $swStatus;
	protected $vmmStatus;
	protected $pmpStatus;
	protected $deviceType;
	protected $deviceAddress;
	protected $productName;
	protected $osName;
	protected $assocDeviceName;
	protected $assocDeviceType;
	protected $assocType;

	// from mxreport "RTC - Blades"
	protected $serialNumber;
	protected $romVersion;
	
	// from mxreport "RTC - Blade Slots"
	protected $slotNumber;

	// from chassis query
	protected $powerStatus;

	// other meta data
	protected $distSwitchName;
	protected $distNetworkCidr;
	
	protected $isInventory;
	protected $isSpare;
	
	// CMDB data
	protected $sysId;
	protected $cmdbName;
	protected $environment;
	protected $cmInstallStatus;
	protected $businessService;
	protected $subsystem;
	protected $opsSuppMgr;
	protected $opsSuppGrp;
	protected $comments;
	protected $shortDescr;
	
	// if this is a Xen master according to CMDB, is it queried for VMs using xm list
	protected $queried;  // true/false: indicates if this blade, if it's a xen master, has been queried using the "xm list" command
	protected $inCmdb;   // true/false: indicates if this blade is currently in the CMDB

	// from get_chassis_data
	protected $memorySizeGB;
	protected $numCpus;
	protected $numCoresPerCpu;
    protected $cpuSpeedMHz;
	protected $iLo;

    protected $overallMemoryUsageGB;
    protected $memoryUsagePercent;
    protected $overallCpuUsage;
    protected $cpuUsagePercent;

    /**
     * Keeps track of properties that have their values changed
     *
     * @var array
     */
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
        if ($this->$prop != $value) {
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
     * @param mixed $assocDeviceName
     * @return $this
     */
    public function setAssocDeviceName($assocDeviceName) {
        $this->updateChanges(func_get_arg(0));
        $this->assocDeviceName = $assocDeviceName;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAssocDeviceName() {
        return $this->assocDeviceName;
    }

    /**
     * @param mixed $assocDeviceType
     * @return $this
     */
    public function setAssocDeviceType($assocDeviceType) {
        $this->updateChanges(func_get_arg(0));
        $this->assocDeviceType = $assocDeviceType;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAssocDeviceType() {
        return $this->assocDeviceType;
    }

    /**
     * @param mixed $assocType
     * @return $this
     */
    public function setAssocType($assocType) {
        $this->updateChanges(func_get_arg(0));
        $this->assocType = $assocType;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAssocType() {
        return $this->assocType;
    }

    /**
     * @param mixed $businessService
     * @return $this
     */
    public function setBusinessService($businessService) {
        $this->updateChanges(func_get_arg(0));
        $this->businessService = $businessService;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getBusinessService() {
        return $this->businessService;
    }

    /**
     * @param mixed $chassisId
     * @return $this
     */
    public function setChassisId($chassisId) {
        $this->updateChanges(func_get_arg(0));
        $this->chassisId = $chassisId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getChassisId() {
        return $this->chassisId;
    }

    /**
     * @param mixed $cmInstallStatus
     * @return $this
     */
    public function setCmInstallStatus($cmInstallStatus) {
        $this->updateChanges(func_get_arg(0));
        $this->cmInstallStatus = $cmInstallStatus;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCmInstallStatus() {
        return $this->cmInstallStatus;
    }

    /**
     * @param mixed $cmdbName
     * @return $this
     */
    public function setCmdbName($cmdbName) {
        $this->updateChanges(func_get_arg(0));
        $this->cmdbName = strtolower($cmdbName);
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCmdbName() {
        return strtolower($this->cmdbName);
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
     * @param mixed $deviceAddress
     * @return $this
     */
    public function setDeviceAddress($deviceAddress) {
        $this->updateChanges(func_get_arg(0));
        $this->deviceAddress = $deviceAddress;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDeviceAddress() {
        return $this->deviceAddress;
    }

    /**
     * @param mixed $deviceName
     * @return $this
     */
    public function setDeviceName($deviceName) {
        $this->updateChanges(func_get_arg(0));
        $this->deviceName = strtolower($deviceName);
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDeviceName() {
        return strtolower($this->deviceName);
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
     * @param mixed $distNetworkCidr
     * @return $this
     */
    public function setDistNetworkCidr($distNetworkCidr) {
        $this->updateChanges(func_get_arg(0));
        $this->distNetworkCidr = $distNetworkCidr;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDistNetworkCidr() {
        return $this->distNetworkCidr;
    }

    /**
     * @param mixed $distSwitchName
     * @return $this
     */
    public function setDistSwitchName($distSwitchName) {
        $this->updateChanges(func_get_arg(0));
        $this->distSwitchName = $distSwitchName;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDistSwitchName() {
        return $this->distSwitchName;
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
     * @param mixed $fullDnsName
     * @return $this
     */
    public function setFullDnsName($fullDnsName) {
        $this->updateChanges(func_get_arg(0));
        $this->fullDnsName = strtolower($fullDnsName);
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFullDnsName() {
        return strtolower($this->fullDnsName);
    }

    /**
     * @param mixed $hsUid
     * @return $this
     */
    public function setHsUid($hsUid) {
        $this->updateChanges(func_get_arg(0));
        $this->hsUid = $hsUid;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getHsUid() {
        return $this->hsUid;
    }

    /**
     * @return mixed
     */
    public function getCcrName() {
        return $this->ccrName;
    }

    /**
     * @param mixed $ccrName
     * @return $this
     */
    public function setCcrName($ccrName) {
        $this->updateChanges(func_get_arg(0));
        $this->ccrName = $ccrName;
        return $this;
    }

    /**
     * @param mixed $hwStatus
     * @return $this
     */
    public function setHwStatus($hwStatus) {
        $this->updateChanges(func_get_arg(0));
        $this->hwStatus = $hwStatus;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getHwStatus() {
        return $this->hwStatus;
    }

    /**
     * @param mixed $iLo
     * @return $this
     */
    public function setILo($iLo) {
        $this->updateChanges(func_get_arg(0));
        $this->iLo = $iLo;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getILo() {
        return $this->iLo;
    }

    /**
     * @param mixed $id
     * @return $this
     */
    public function setId($id) {
        $this->updateChanges(func_get_arg(0));
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @param mixed $inCmdb
     * @return $this
     */
    public function setInCmdb($inCmdb) {
        $this->updateChanges(func_get_arg(0));
        $this->inCmdb = $inCmdb;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getInCmdb() {
        return $this->inCmdb;
    }

    /**
     * @param mixed $isInventory
     * @return $this
     */
    public function setIsInventory($isInventory) {
        $this->updateChanges(func_get_arg(0));
        $this->isInventory = $isInventory;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIsInventory() {
        return $this->isInventory;
    }

    /**
     * @param mixed $isSpare
     * @return $this
     */
    public function setIsSpare($isSpare) {
        $this->updateChanges(func_get_arg(0));
        $this->isSpare = $isSpare;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIsSpare() {
        return $this->isSpare;
    }

    /**
     * @param mixed $memorySizeGB
     * @return $this
     */
    public function setMemorySizeGB($memorySizeGB) {
        $this->updateChanges(func_get_arg(0));
        $this->memorySizeGB = $memorySizeGB;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMemorySizeGB() {
        return $this->memorySizeGB;
    }

    /**
     * @param mixed $mpStatus
     * @return $this
     */
    public function setMpStatus($mpStatus) {
        $this->updateChanges(func_get_arg(0));
        $this->mpStatus = $mpStatus;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMpStatus() {
        return $this->mpStatus;
    }

    /**
     * @param mixed $numCoresPerCpu
     * @return $this
     */
    public function setNumCoresPerCpu($numCoresPerCpu) {
        $this->updateChanges(func_get_arg(0));
        $this->numCoresPerCpu = $numCoresPerCpu;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNumCoresPerCpu() {
        return $this->numCoresPerCpu;
    }

    /**
     * @param mixed $numCpus
     * @return $this
     */
    public function setNumCpus($numCpus) {
        $this->updateChanges(func_get_arg(0));
        $this->numCpus = $numCpus;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNumCpus() {
        return $this->numCpus;
    }

    /**
     * @param mixed $opsSuppGrp
     * @return $this
     */
    public function setOpsSuppGrp($opsSuppGrp) {
        $this->updateChanges(func_get_arg(0));
        $this->opsSuppGrp = $opsSuppGrp;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getOpsSuppGrp() {
        return $this->opsSuppGrp;
    }

    /**
     * @param mixed $opsSuppMgr
     * @return $this
     */
    public function setOpsSuppMgr($opsSuppMgr) {
        $this->updateChanges(func_get_arg(0));
        $this->opsSuppMgr = $opsSuppMgr;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getOpsSuppMgr() {
        return $this->opsSuppMgr;
    }

    /**
     * @param mixed $osName
     * @return $this
     */
    public function setOsName($osName) {
        $this->updateChanges(func_get_arg(0));
        $this->osName = $osName;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getOsName() {
        return $this->osName;
    }

    /**
     * @param mixed $pmpStatus
     * @return $this
     */
    public function setPmpStatus($pmpStatus) {
        $this->updateChanges(func_get_arg(0));
        $this->pmpStatus = $pmpStatus;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPmpStatus() {
        return $this->pmpStatus;
    }

    /**
     * @param mixed $powerStatus
     * @return $this
     */
    public function setPowerStatus($powerStatus) {
        $this->updateChanges(func_get_arg(0));
        $this->powerStatus = $powerStatus;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPowerStatus() {
        return $this->powerStatus;
    }

    /**
     * @param mixed $productName
     * @return $this
     */
    public function setProductName($productName) {
        $this->updateChanges(func_get_arg(0));
        $this->productName = $productName;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getProductName() {
        return $this->productName;
    }

    /**
     * @param mixed $queried
     * @return $this
     */
    public function setQueried($queried) {
        $this->updateChanges(func_get_arg(0));
        $this->queried = $queried;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getQueried() {
        return $this->queried;
    }

    /**
     * @param mixed $romVersion
     * @return $this
     */
    public function setRomVersion($romVersion) {
        $this->updateChanges(func_get_arg(0));
        $this->romVersion = $romVersion;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRomVersion() {
        return $this->romVersion;
    }

    /**
     * @param mixed $serialNumber
     * @return $this
     */
    public function setSerialNumber($serialNumber) {
        $this->updateChanges(func_get_arg(0));
        $this->serialNumber = strtolower($serialNumber);
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSerialNumber() {
        return strtolower($this->serialNumber);
    }

    /**
     * @param mixed $shortDescr
     * @return $this
     */
    public function setShortDescr($shortDescr) {
        $this->updateChanges(func_get_arg(0));
        $this->shortDescr = $shortDescr;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getShortDescr() {
        return $this->shortDescr;
    }

    /**
     * @param mixed $slotNumber
     * @return $this
     */
    public function setSlotNumber($slotNumber) {
        $this->updateChanges(func_get_arg(0));
        $this->slotNumber = $slotNumber;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSlotNumber() {
        return $this->slotNumber;
    }

    /**
     * @param mixed $subsystem
     * @return $this
     */
    public function setSubsystem($subsystem) {
        $this->updateChanges(func_get_arg(0));
        $this->subsystem = $subsystem;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSubsystem() {
        return $this->subsystem;
    }

    /**
     * @param mixed $swStatus
     * @return $this
     */
    public function setSwStatus($swStatus) {
        $this->updateChanges(func_get_arg(0));
        $this->swStatus = $swStatus;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSwStatus() {
        return $this->swStatus;
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
     * @param mixed $vmmStatus
     * @return $this
     */
    public function setVmmStatus($vmmStatus) {
        $this->updateChanges(func_get_arg(0));
        $this->vmmStatus = $vmmStatus;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getVmmStatus() {
        return $this->vmmStatus;
    }

    /**
     * @return mixed
     */
    public function getCpuSpeedMHz() {
        return $this->cpuSpeedMHz;
    }

    /**
     * @param mixed $cpuSpeedMHz
     * @return $this
     */
    public function setCpuSpeedMHz($cpuSpeedMHz) {
        $this->updateChanges(func_get_arg(0));
        $this->cpuSpeedMHz = $cpuSpeedMHz;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getOverallMemoryUsageGB() {
        return $this->overallMemoryUsageGB;
    }

    /**
     * @param mixed $overallMemoryUsageGB
     * @return $this
     */
    public function setOverallMemoryUsageGB($overallMemoryUsageGB) {
        $this->updateChanges(func_get_arg(0));
        $this->overallMemoryUsageGB = $overallMemoryUsageGB;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMemoryUsagePercent() {
        return $this->memoryUsagePercent;
    }

    /**
     * @param mixed $memoryUsagePercent
     * @return $this
     */
    public function setMemoryUsagePercent($memoryUsagePercent) {
        $this->updateChanges(func_get_arg(0));
        $this->memoryUsagePercent = $memoryUsagePercent;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getOverallCpuUsage() {
        return $this->overallCpuUsage;
    }

    /**
     * @param mixed $overallCpuUsage
     * @return $this
     */
    public function setOverallCpuUsage($overallCpuUsage) {
        $this->updateChanges(func_get_arg(0));
        $this->overallCpuUsage = $overallCpuUsage;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCpuUsagePercent() {
        return $this->cpuUsagePercent;
    }

    /**
     * @param mixed $cpuUsagePercent
     * @return $this
     */
    public function setCpuUsagePercent($cpuUsagePercent) {
        $this->updateChanges(func_get_arg(0));
        $this->cpuUsagePercent = $cpuUsagePercent;
        return $this;
    }

}
