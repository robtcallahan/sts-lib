<?php

namespace STS\HPSIM;

class HPSIMBladeSnapshot
{
    protected $dateStamp;

	protected $deviceName;
	protected $fullDnsName;
    protected $chassisName;

    protected $productName;
	protected $serialNumber;
	protected $slotNumber;
	protected $distSwitchName;

	protected $isInventory;
	protected $isSpare;
	
	// CMDB data
	protected $sysId;
	protected $cmdbName;
	protected $environment;
	protected $cmInstallStatus;
	protected $businessService;
	protected $subsystem;

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
     * @param mixed $chassisName
     * @return $this
     */
    public function setChassisName($chassisName) {
        $this->updateChanges(func_get_arg(0));
        $this->chassisName = $chassisName;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getChassisName() {
        return $this->chassisName;
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
     * @param mixed $dateStamp
     * @return $this
     */
    public function setDateStamp($dateStamp) {
        $this->updateChanges(func_get_arg(0));
        $this->dateStamp = $dateStamp;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDateStamp() {
        return $this->dateStamp;
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


}
