<?php

namespace STS\HPSIM;

class HPSIMVM
{
	protected $id;
	protected $bladeId;

    protected $isVmware;
    protected $vmUid;

	protected $deviceName;
	protected $fullDnsName;

    protected $status;   // as returned from the hypervisor query (virsh list)

	protected $osName;
	protected $osVersion;
	protected $osPatchLevel;

	protected $memorySize;
	protected $numberOfCpus;
    protected $guestMemUsageMB;
    protected $overallCpuUsageMHz;
		
	// common (with blades) CMDB data
	protected $sysId;
	protected $environment;
	protected $cmInstallStatus;
	protected $businessService;
	protected $subsystem;
	protected $opsSuppMgr;
	protected $opsSuppGrp;
	protected $comments;
	protected $shortDescr;
	
	// active flag; true if reported by xm list
	protected $active;   // true/false: indicates whether this blade was reported by "xm list" or not; 
	                     // should be used in conjuction with the "queried" property of the blade for validity
	protected $inCmdb;   // true/false: indicates if this blade is currently in the CMDB


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
     * @param mixed $active
     * @return $this
     */
    public function setActive($active) {
        $this->updateChanges(func_get_arg(0));
        $this->active = $active;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getActive() {
        return $this->active;
    }

    /**
     * @param mixed $bladeId
     * @return $this
     */
    public function setBladeId($bladeId) {
        $this->updateChanges(func_get_arg(0));
        $this->bladeId = $bladeId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getBladeId() {
        return $this->bladeId;
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
     * @param mixed $isVmware
     * @return $this
     */
    public function setIsVmware($isVmware) {
        $this->updateChanges(func_get_arg(0));
        $this->isVmware = $isVmware;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIsVmware() {
        return $this->isVmware;
    }

    /**
     * @param mixed $memorySize
     * @return $this
     */
    public function setMemorySize($memorySize) {
        $this->updateChanges(func_get_arg(0));
        $this->memorySize = $memorySize;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMemorySize() {
        return $this->memorySize;
    }

    /**
     * @param mixed $numberOfCpus
     * @return $this
     */
    public function setNumberOfCpus($numberOfCpus) {
        $this->updateChanges(func_get_arg(0));
        $this->numberOfCpus = $numberOfCpus;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNumberOfCpus() {
        return $this->numberOfCpus;
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
     * @param mixed $osPatchLevel
     * @return $this
     */
    public function setOsPatchLevel($osPatchLevel) {
        $this->updateChanges(func_get_arg(0));
        $this->osPatchLevel = $osPatchLevel;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getOsPatchLevel() {
        return $this->osPatchLevel;
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
     * @param mixed $status
     * @return $this
     */
    public function setStatus($status) {
        $this->updateChanges(func_get_arg(0));
        $this->status = $status;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getStatus() {
        return $this->status;
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
     * @param mixed $vmUid
     * @return $this
     */
    public function setVmUid($vmUid) {
        $this->updateChanges(func_get_arg(0));
        $this->vmUid = $vmUid;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getVmUid() {
        return $this->vmUid;
    }

    /**
     * @return mixed
     */
    public function getGuestMemUsageMB() {
        return $this->guestMemUsageMB;
    }

    /**
     * @param mixed $guestMemUsageMB
     * @return $this
     */
    public function setGuestMemUsageMB($guestMemUsageMB) {
        $this->updateChanges(func_get_arg(0));
        $this->guestMemUsageMB = $guestMemUsageMB;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getOverallCpuUsageMHz() {
        return $this->overallCpuUsageMHz;
    }

    /**
     * @param mixed $overallCpuUsageMHz
     * @return $this
     */
    public function setOverallCpuUsageMHz($overallCpuUsageMHz) {
        $this->updateChanges(func_get_arg(0));
        $this->overallCpuUsageMHz = $overallCpuUsageMHz;
        return $this;
    }


}
