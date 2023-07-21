<?php

namespace STS\HPSIM;

class HPSIMVMSnapshot
{
    protected $dateStamp;

    protected $hostName;
    protected $fqdn;
    protected $vmType;  // Xen, KVM, VMware

    protected $bladeId;
    protected $bladeFqdn;
    protected $chassisName;
    protected $distSwitchName;


	// common CMDB data
    protected $inCmdb;   // true/false: indicates if this VM is currently in the CMDB
    protected $cmdbName;
	protected $sysId;
	protected $environment;
	protected $cmInstallStatus;
    protected $businessService;
	protected $subsystem;

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
     * @param mixed $bladeFqdn
     * @return $this
     */
    public function setBladeFqdn($bladeFqdn) {
        $this->updateChanges(func_get_arg(0));
        $this->bladeFqdn = strtolower($bladeFqdn);
        return $this;
    }

    /**
     * @return mixed
     */
    public function getBladeFqdn() {
        return strtolower($this->bladeFqdn);
    }

    /**
     * @param mixed $fqdn
     * @return $this
     */
    public function setFqdn($fqdn) {
        $this->updateChanges(func_get_arg(0));
        $this->fqdn = strtolower($fqdn);
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFqdn() {
        return strtolower($this->fqdn);
    }

    /**
     * @param mixed $vmType
     * @return $this
     */
    public function setVmType($vmType) {
        $this->updateChanges(func_get_arg(0));
        $this->vmType = $vmType;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getVmType() {
        return $this->vmType;
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
     * @param mixed $hostName
     * @return $this
     */
    public function setHostName($hostName) {
        $this->updateChanges(func_get_arg(0));
        $this->hostName = strtolower($hostName);
        return $this;
    }

    /**
     * @return mixed
     */
    public function getHostName() {
        return strtolower($this->hostName);
    }

    /**
     * @return mixed
     */
    public function getChassisName() {
        return $this->chassisName;
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
    public function getDistSwitchName() {
        return $this->distSwitchName;
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

}
