<?php

namespace STS\SNCache;

class BusinessService
{
    protected $sysId;
    protected $name;
    protected $sysClassName;

    protected $description;
    protected $operationalStatus;

    protected $changeNotificationEmail;
    protected $incidentNotificationEmail;

    protected $sysCreatedBy;
   	protected $sysCreatedOn;
    protected $sysUpdatedBy;
    protected $sysUpdatedOn;

    protected $customerSupportLeaderId;
    protected $customerSupportLeaderFullName;
    protected $customerSupportLeaderEmail;
    protected $customerSupportLeaderFirstName;
    protected $customerSupportLeaderLastName;
    protected $customerSupportLeaderUserName;

    protected $developmentLeaderId;
    protected $developmentLeaderFullName;
    protected $developmentLeaderEmail;
    protected $developmentLeaderFirstName;
    protected $developmentLeaderLastName;
    protected $developmentLeaderUserName;

    protected $incidentExecutivesId;
    protected $incidentExecutivesFullName;
    protected $incidentExecutivesEmail;
    protected $incidentExecutivesFirstName;
    protected $incidentExecutivesLastName;
    protected $incidentExecutivesUserName;

    protected $operationsLeaderId;
    protected $operationsLeaderFullName;
    protected $operationsLeaderEmail;
    protected $operationsLeaderFirstName;
    protected $operationsLeaderLastName;
    protected $operationsLeaderUserName;

    protected $systemsAdminLeaderId;
    protected $systemsAdminLeaderFullName;
    protected $systemsAdminLeaderEmail;
    protected $systemsAdminLeaderFirstName;
    protected $systemsAdminLeaderLastName;
    protected $systemsAdminLeaderUserName;

    protected $productLeaderId;
    protected $productLeaderFullName;
    protected $productLeaderEmail;
    protected $productLeaderFirstName;
    protected $productLeaderLastName;
    protected $productLeaderUserName;

    protected $productId;
    protected $productName;

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
     * @param mixed $changeNotificationEmail
     * @return $this
     */
    public function setChangeNotificationEmail($changeNotificationEmail) {
        $this->updateChanges(func_get_arg(0));
        $this->changeNotificationEmail = $changeNotificationEmail;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getChangeNotificationEmail() {
        return $this->changeNotificationEmail;
    }

    /**
     * @param mixed $customerSupportLeaderEmail
     * @return $this
     */
    public function setCustomerSupportLeaderEmail($customerSupportLeaderEmail) {
        $this->updateChanges(func_get_arg(0));
        $this->customerSupportLeaderEmail = $customerSupportLeaderEmail;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCustomerSupportLeaderEmail() {
        return $this->customerSupportLeaderEmail;
    }

    /**
     * @param mixed $customerSupportLeaderFirstName
     * @return $this
     */
    public function setCustomerSupportLeaderFirstName($customerSupportLeaderFirstName) {
        $this->updateChanges(func_get_arg(0));
        $this->customerSupportLeaderFirstName = $customerSupportLeaderFirstName;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCustomerSupportLeaderFirstName() {
        return $this->customerSupportLeaderFirstName;
    }

    /**
     * @param mixed $customerSupportLeaderFullName
     * @return $this
     */
    public function setCustomerSupportLeaderFullName($customerSupportLeaderFullName) {
        $this->updateChanges(func_get_arg(0));
        $this->customerSupportLeaderFullName = $customerSupportLeaderFullName;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCustomerSupportLeaderFullName() {
        return $this->customerSupportLeaderFullName;
    }

    /**
     * @param mixed $customerSupportLeaderId
     * @return $this
     */
    public function setCustomerSupportLeaderId($customerSupportLeaderId) {
        $this->updateChanges(func_get_arg(0));
        $this->customerSupportLeaderId = $customerSupportLeaderId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCustomerSupportLeaderId() {
        return $this->customerSupportLeaderId;
    }

    /**
     * @param mixed $customerSupportLeaderLastName
     * @return $this
     */
    public function setCustomerSupportLeaderLastName($customerSupportLeaderLastName) {
        $this->updateChanges(func_get_arg(0));
        $this->customerSupportLeaderLastName = $customerSupportLeaderLastName;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCustomerSupportLeaderLastName() {
        return $this->customerSupportLeaderLastName;
    }

    /**
     * @param mixed $customerSupportLeaderUserName
     * @return $this
     */
    public function setCustomerSupportLeaderUserName($customerSupportLeaderUserName) {
        $this->updateChanges(func_get_arg(0));
        $this->customerSupportLeaderUserName = $customerSupportLeaderUserName;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCustomerSupportLeaderUserName() {
        return $this->customerSupportLeaderUserName;
    }

    /**
     * @param mixed $description
     * @return $this
     */
    public function setDescription($description) {
        $this->updateChanges(func_get_arg(0));
        $this->description = $description;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDescription() {
        return $this->description;
    }

    /**
     * @param mixed $developmentLeaderEmail
     * @return $this
     */
    public function setDevelopmentLeaderEmail($developmentLeaderEmail) {
        $this->updateChanges(func_get_arg(0));
        $this->developmentLeaderEmail = $developmentLeaderEmail;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDevelopmentLeaderEmail() {
        return $this->developmentLeaderEmail;
    }

    /**
     * @param mixed $developmentLeaderFirstName
     * @return $this
     */
    public function setDevelopmentLeaderFirstName($developmentLeaderFirstName) {
        $this->updateChanges(func_get_arg(0));
        $this->developmentLeaderFirstName = $developmentLeaderFirstName;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDevelopmentLeaderFirstName() {
        return $this->developmentLeaderFirstName;
    }

    /**
     * @param mixed $developmentLeaderFullName
     * @return $this
     */
    public function setDevelopmentLeaderFullName($developmentLeaderFullName) {
        $this->updateChanges(func_get_arg(0));
        $this->developmentLeaderFullName = $developmentLeaderFullName;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDevelopmentLeaderFullName() {
        return $this->developmentLeaderFullName;
    }

    /**
     * @param mixed $developmentLeaderId
     * @return $this
     */
    public function setDevelopmentLeaderId($developmentLeaderId) {
        $this->updateChanges(func_get_arg(0));
        $this->developmentLeaderId = $developmentLeaderId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDevelopmentLeaderId() {
        return $this->developmentLeaderId;
    }

    /**
     * @param mixed $developmentLeaderLastName
     * @return $this
     */
    public function setDevelopmentLeaderLastName($developmentLeaderLastName) {
        $this->updateChanges(func_get_arg(0));
        $this->developmentLeaderLastName = $developmentLeaderLastName;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDevelopmentLeaderLastName() {
        return $this->developmentLeaderLastName;
    }

    /**
     * @param mixed $developmentLeaderUserName
     * @return $this
     */
    public function setDevelopmentLeaderUserName($developmentLeaderUserName) {
        $this->updateChanges(func_get_arg(0));
        $this->developmentLeaderUserName = $developmentLeaderUserName;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDevelopmentLeaderUserName() {
        return $this->developmentLeaderUserName;
    }

    /**
     * @param mixed $incidentExecutivesEmail
     * @return $this
     */
    public function setIncidentExecutivesEmail($incidentExecutivesEmail) {
        $this->updateChanges(func_get_arg(0));
        $this->incidentExecutivesEmail = $incidentExecutivesEmail;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIncidentExecutivesEmail() {
        return $this->incidentExecutivesEmail;
    }

    /**
     * @param mixed $incidentExecutivesFirstName
     * @return $this
     */
    public function setIncidentExecutivesFirstName($incidentExecutivesFirstName) {
        $this->updateChanges(func_get_arg(0));
        $this->incidentExecutivesFirstName = $incidentExecutivesFirstName;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIncidentExecutivesFirstName() {
        return $this->incidentExecutivesFirstName;
    }

    /**
     * @param mixed $incidentExecutivesFullName
     * @return $this
     */
    public function setIncidentExecutivesFullName($incidentExecutivesFullName) {
        $this->updateChanges(func_get_arg(0));
        $this->incidentExecutivesFullName = $incidentExecutivesFullName;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIncidentExecutivesFullName() {
        return $this->incidentExecutivesFullName;
    }

    /**
     * @param mixed $incidentExecutivesId
     * @return $this
     */
    public function setIncidentExecutivesId($incidentExecutivesId) {
        $this->updateChanges(func_get_arg(0));
        $this->incidentExecutivesId = $incidentExecutivesId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIncidentExecutivesId() {
        return $this->incidentExecutivesId;
    }

    /**
     * @param mixed $incidentExecutivesLastName
     * @return $this
     */
    public function setIncidentExecutivesLastName($incidentExecutivesLastName) {
        $this->updateChanges(func_get_arg(0));
        $this->incidentExecutivesLastName = $incidentExecutivesLastName;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIncidentExecutivesLastName() {
        return $this->incidentExecutivesLastName;
    }

    /**
     * @param mixed $incidentExecutivesUserName
     * @return $this
     */
    public function setIncidentExecutivesUserName($incidentExecutivesUserName) {
        $this->updateChanges(func_get_arg(0));
        $this->incidentExecutivesUserName = $incidentExecutivesUserName;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIncidentExecutivesUserName() {
        return $this->incidentExecutivesUserName;
    }

    /**
     * @param mixed $incidentNotificationEmail
     * @return $this
     */
    public function setIncidentNotificationEmail($incidentNotificationEmail) {
        $this->updateChanges(func_get_arg(0));
        $this->incidentNotificationEmail = $incidentNotificationEmail;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIncidentNotificationEmail() {
        return $this->incidentNotificationEmail;
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
     * @param mixed $operationsLeaderEmail
     * @return $this
     */
    public function setOperationsLeaderEmail($operationsLeaderEmail) {
        $this->updateChanges(func_get_arg(0));
        $this->operationsLeaderEmail = $operationsLeaderEmail;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getOperationsLeaderEmail() {
        return $this->operationsLeaderEmail;
    }

    /**
     * @param mixed $operationsLeaderFirstName
     * @return $this
     */
    public function setOperationsLeaderFirstName($operationsLeaderFirstName) {
        $this->updateChanges(func_get_arg(0));
        $this->operationsLeaderFirstName = $operationsLeaderFirstName;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getOperationsLeaderFirstName() {
        return $this->operationsLeaderFirstName;
    }

    /**
     * @param mixed $operationsLeaderFullName
     * @return $this
     */
    public function setOperationsLeaderFullName($operationsLeaderFullName) {
        $this->updateChanges(func_get_arg(0));
        $this->operationsLeaderFullName = $operationsLeaderFullName;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getOperationsLeaderFullName() {
        return $this->operationsLeaderFullName;
    }

    /**
     * @param mixed $operationsLeaderId
     * @return $this
     */
    public function setOperationsLeaderId($operationsLeaderId) {
        $this->updateChanges(func_get_arg(0));
        $this->operationsLeaderId = $operationsLeaderId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getOperationsLeaderId() {
        return $this->operationsLeaderId;
    }

    /**
     * @param mixed $operationsLeaderLastName
     * @return $this
     */
    public function setOperationsLeaderLastName($operationsLeaderLastName) {
        $this->updateChanges(func_get_arg(0));
        $this->operationsLeaderLastName = $operationsLeaderLastName;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getOperationsLeaderLastName() {
        return $this->operationsLeaderLastName;
    }

    /**
     * @param mixed $operationsLeaderUserName
     * @return $this
     */
    public function setOperationsLeaderUserName($operationsLeaderUserName) {
        $this->updateChanges(func_get_arg(0));
        $this->operationsLeaderUserName = $operationsLeaderUserName;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getOperationsLeaderUserName() {
        return $this->operationsLeaderUserName;
    }

    /**
     * @param mixed $productId
     * @return $this
     */
    public function setProductId($productId) {
        $this->updateChanges(func_get_arg(0));
        $this->productId = $productId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getProductId() {
        return $this->productId;
    }

    /**
     * @param mixed $productLeaderEmail
     * @return $this
     */
    public function setProductLeaderEmail($productLeaderEmail) {
        $this->updateChanges(func_get_arg(0));
        $this->productLeaderEmail = $productLeaderEmail;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getProductLeaderEmail() {
        return $this->productLeaderEmail;
    }

    /**
     * @param mixed $productLeaderFirstName
     * @return $this
     */
    public function setProductLeaderFirstName($productLeaderFirstName) {
        $this->updateChanges(func_get_arg(0));
        $this->productLeaderFirstName = $productLeaderFirstName;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getProductLeaderFirstName() {
        return $this->productLeaderFirstName;
    }

    /**
     * @param mixed $productLeaderFullName
     * @return $this
     */
    public function setProductLeaderFullName($productLeaderFullName) {
        $this->updateChanges(func_get_arg(0));
        $this->productLeaderFullName = $productLeaderFullName;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getProductLeaderFullName() {
        return $this->productLeaderFullName;
    }

    /**
     * @param mixed $productLeaderId
     * @return $this
     */
    public function setProductLeaderId($productLeaderId) {
        $this->updateChanges(func_get_arg(0));
        $this->productLeaderId = $productLeaderId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getProductLeaderId() {
        return $this->productLeaderId;
    }

    /**
     * @param mixed $productLeaderLastName
     * @return $this
     */
    public function setProductLeaderLastName($productLeaderLastName) {
        $this->updateChanges(func_get_arg(0));
        $this->productLeaderLastName = $productLeaderLastName;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getProductLeaderLastName() {
        return $this->productLeaderLastName;
    }

    /**
     * @param mixed $productLeaderUserName
     * @return $this
     */
    public function setProductLeaderUserName($productLeaderUserName) {
        $this->updateChanges(func_get_arg(0));
        $this->productLeaderUserName = $productLeaderUserName;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getProductLeaderUserName() {
        return $this->productLeaderUserName;
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
     * @param mixed $systemsAdminLeaderEmail
     * @return $this
     */
    public function setSystemsAdminLeaderEmail($systemsAdminLeaderEmail) {
        $this->updateChanges(func_get_arg(0));
        $this->systemsAdminLeaderEmail = $systemsAdminLeaderEmail;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSystemsAdminLeaderEmail() {
        return $this->systemsAdminLeaderEmail;
    }

    /**
     * @param mixed $systemsAdminLeaderFirstName
     * @return $this
     */
    public function setSystemsAdminLeaderFirstName($systemsAdminLeaderFirstName) {
        $this->updateChanges(func_get_arg(0));
        $this->systemsAdminLeaderFirstName = $systemsAdminLeaderFirstName;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSystemsAdminLeaderFirstName() {
        return $this->systemsAdminLeaderFirstName;
    }

    /**
     * @param mixed $systemsAdminLeaderFullName
     * @return $this
     */
    public function setSystemsAdminLeaderFullName($systemsAdminLeaderFullName) {
        $this->updateChanges(func_get_arg(0));
        $this->systemsAdminLeaderFullName = $systemsAdminLeaderFullName;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSystemsAdminLeaderFullName() {
        return $this->systemsAdminLeaderFullName;
    }

    /**
     * @param mixed $systemsAdminLeaderId
     * @return $this
     */
    public function setSystemsAdminLeaderId($systemsAdminLeaderId) {
        $this->updateChanges(func_get_arg(0));
        $this->systemsAdminLeaderId = $systemsAdminLeaderId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSystemsAdminLeaderId() {
        return $this->systemsAdminLeaderId;
    }

    /**
     * @param mixed $systemsAdminLeaderLastName
     * @return $this
     */
    public function setSystemsAdminLeaderLastName($systemsAdminLeaderLastName) {
        $this->updateChanges(func_get_arg(0));
        $this->systemsAdminLeaderLastName = $systemsAdminLeaderLastName;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSystemsAdminLeaderLastName() {
        return $this->systemsAdminLeaderLastName;
    }

    /**
     * @param mixed $systemsAdminLeaderUserName
     * @return $this
     */
    public function setSystemsAdminLeaderUserName($systemsAdminLeaderUserName) {
        $this->updateChanges(func_get_arg(0));
        $this->systemsAdminLeaderUserName = $systemsAdminLeaderUserName;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSystemsAdminLeaderUserName() {
        return $this->systemsAdminLeaderUserName;
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


}
