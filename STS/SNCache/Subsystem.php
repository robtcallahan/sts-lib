<?php

namespace STS\SNCache;

class Subsystem
{
    protected $sysId;
    protected $name;
    protected $sysClassName;

    protected $operationalStatus;

    protected $businessServiceId;
    protected $businessService;

    protected $opsSupportGroupId;
    protected $opsSupportGroup;

    protected $owningSupportManagerId;
    protected $owningSupportManagerFullName;
    protected $owningSupportManagerEmail;
    protected $owningSupportManagerFirstName;
    protected $owningSupportManagerLastName;
    protected $owningSupportManagerUserName;

    protected $sysAdminGroupId;
    protected $sysAdminGroup;

    protected $sysAdminManagerId;
    protected $sysAdminManagerFullName;
    protected $sysAdminManagerEmail;
    protected $sysAdminManagerFirstName;
    protected $sysAdminManagerLastName;
    protected $sysAdminManagerUserName;

    protected $sysCreatedBy;
   	protected $sysCreatedOn;
    protected $sysUpdatedBy;
    protected $sysUpdatedOn;

    protected $changes = array();


    /**
     * @return string
     */
    public function __toString() {
        $return = "";
        foreach (get_class_vars(__CLASS__) as $prop => $x) {
            if ($prop == 'changes') continue;
            if (property_exists($this, $prop)) {
                $return .= sprintf("%-25s => %s\n", $prop, $this->$prop);
            }
        }

        return $return;
    }

    /**
     * @return object
     */
    public function toObject() {
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
    public function get($prop) {
        return $this->$prop;
    }

    /**
     * @param $prop
     * @param $value
     * @return mixed
     */
    public function set($prop, $value) {
        return $this->$prop = $value;
    }

    /**
     * @return array
     */
    public function getChanges() {
        return $this->changes;
    }

    /**
     *
     */
    public function clearChanges() {
        $this->changes = array();
    }

    /**
     * @param $value
     */
    private function updateChanges($value) {
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
        if (!array_key_exists($prop, $this->changes)) {
            $this->changes[$prop] = (object)array(
                'originalValue' => $this->$prop,
                'modifiedValue' => $value
            );
        } else {
            $this->changes[$prop]->modifiedValue = $value;
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
     * @param mixed $businessServiceId
     * @return $this
     */
    public function setBusinessServiceId($businessServiceId) {
        $this->updateChanges(func_get_arg(0));
        $this->businessServiceId = $businessServiceId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getBusinessServiceId() {
        return $this->businessServiceId;
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
     * @param mixed $operationalStatus
     * @return $this
     */
    public function setOperationalStatus($operationalStatus) {
        $this->updateChanges(func_get_arg(0));
        $this->operationalStatus = $operationalStatus;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getOperationalStatus() {
        return $this->operationalStatus;
    }

    /**
     * @param mixed $opsSupportGroup
     * @return $this
     */
    public function setOpsSupportGroup($opsSupportGroup) {
        $this->updateChanges(func_get_arg(0));
        $this->opsSupportGroup = $opsSupportGroup;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getOpsSupportGroup() {
        return $this->opsSupportGroup;
    }

    /**
     * @param mixed $opsSupportGroupId
     * @return $this
     */
    public function setOpsSupportGroupId($opsSupportGroupId) {
        $this->updateChanges(func_get_arg(0));
        $this->opsSupportGroupId = $opsSupportGroupId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getOpsSupportGroupId() {
        return $this->opsSupportGroupId;
    }

    /**
     * @param mixed $owningSupportManagerEmail
     * @return $this
     */
    public function setOwningSupportManagerEmail($owningSupportManagerEmail) {
        $this->updateChanges(func_get_arg(0));
        $this->owningSupportManagerEmail = $owningSupportManagerEmail;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getOwningSupportManagerEmail() {
        return $this->owningSupportManagerEmail;
    }

    /**
     * @param mixed $owningSupportManagerFirstName
     * @return $this
     */
    public function setOwningSupportManagerFirstName($owningSupportManagerFirstName) {
        $this->updateChanges(func_get_arg(0));
        $this->owningSupportManagerFirstName = $owningSupportManagerFirstName;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getOwningSupportManagerFirstName() {
        return $this->owningSupportManagerFirstName;
    }

    /**
     * @param mixed $owningSupportManagerFullName
     * @return $this
     */
    public function setOwningSupportManagerFullName($owningSupportManagerFullName) {
        $this->updateChanges(func_get_arg(0));
        $this->owningSupportManagerFullName = $owningSupportManagerFullName;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getOwningSupportManagerFullName() {
        return $this->owningSupportManagerFullName;
    }

    /**
     * @param mixed $owningSupportManagerId
     * @return $this
     */
    public function setOwningSupportManagerId($owningSupportManagerId) {
        $this->updateChanges(func_get_arg(0));
        $this->owningSupportManagerId = $owningSupportManagerId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getOwningSupportManagerId() {
        return $this->owningSupportManagerId;
    }

    /**
     * @param mixed $owningSupportManagerLastName
     * @return $this
     */
    public function setOwningSupportManagerLastName($owningSupportManagerLastName) {
        $this->updateChanges(func_get_arg(0));
        $this->owningSupportManagerLastName = $owningSupportManagerLastName;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getOwningSupportManagerLastName() {
        return $this->owningSupportManagerLastName;
    }

    /**
     * @param mixed $owningSupportManagerUserName
     * @return $this
     */
    public function setOwningSupportManagerUserName($owningSupportManagerUserName) {
        $this->updateChanges(func_get_arg(0));
        $this->owningSupportManagerUserName = $owningSupportManagerUserName;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getOwningSupportManagerUserName() {
        return $this->owningSupportManagerUserName;
    }

    /**
     * @param mixed $sysAdminGroup
     * @return $this
     */
    public function setSysAdminGroup($sysAdminGroup) {
        $this->updateChanges(func_get_arg(0));
        $this->sysAdminGroup = $sysAdminGroup;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSysAdminGroup() {
        return $this->sysAdminGroup;
    }

    /**
     * @param mixed $sysAdminGroupId
     * @return $this
     */
    public function setSysAdminGroupId($sysAdminGroupId) {
        $this->updateChanges(func_get_arg(0));
        $this->sysAdminGroupId = $sysAdminGroupId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSysAdminGroupId() {
        return $this->sysAdminGroupId;
    }

    /**
     * @param mixed $sysAdminManagerEmail
     * @return $this
     */
    public function setSysAdminManagerEmail($sysAdminManagerEmail) {
        $this->updateChanges(func_get_arg(0));
        $this->sysAdminManagerEmail = $sysAdminManagerEmail;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSysAdminManagerEmail() {
        return $this->sysAdminManagerEmail;
    }

    /**
     * @param mixed $sysAdminManagerFirstName
     * @return $this
     */
    public function setSysAdminManagerFirstName($sysAdminManagerFirstName) {
        $this->updateChanges(func_get_arg(0));
        $this->sysAdminManagerFirstName = $sysAdminManagerFirstName;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSysAdminManagerFirstName() {
        return $this->sysAdminManagerFirstName;
    }

    /**
     * @param mixed $sysAdminManagerFullName
     * @return $this
     */
    public function setSysAdminManagerFullName($sysAdminManagerFullName) {
        $this->updateChanges(func_get_arg(0));
        $this->sysAdminManagerFullName = $sysAdminManagerFullName;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSysAdminManagerFullName() {
        return $this->sysAdminManagerFullName;
    }

    /**
     * @param mixed $sysAdminManagerId
     * @return $this
     */
    public function setSysAdminManagerId($sysAdminManagerId) {
        $this->updateChanges(func_get_arg(0));
        $this->sysAdminManagerId = $sysAdminManagerId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSysAdminManagerId() {
        return $this->sysAdminManagerId;
    }

    /**
     * @param mixed $sysAdminManagerLastName
     * @return $this
     */
    public function setSysAdminManagerLastName($sysAdminManagerLastName) {
        $this->updateChanges(func_get_arg(0));
        $this->sysAdminManagerLastName = $sysAdminManagerLastName;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSysAdminManagerLastName() {
        return $this->sysAdminManagerLastName;
    }

    /**
     * @param mixed $sysAdminManagerUserName
     * @return $this
     */
    public function setSysAdminManagerUserName($sysAdminManagerUserName) {
        $this->updateChanges(func_get_arg(0));
        $this->sysAdminManagerUserName = $sysAdminManagerUserName;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSysAdminManagerUserName() {
        return $this->sysAdminManagerUserName;
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


}
