<?php

namespace STS\SNCache;

use STS\DB\DBTable;


class CITable extends DBTable
{
    const MULTIPLE_ENTRIES    = 69;

    protected static $reverseNameMapping = array();
    protected static $nameMapping = array(
        "sys_id"                    => "sysId",
        "sys_class_name"            => "sysClassName",

        "name"                      => "name",
        "serial_number"             => "serialNumber",
        "asset_tag"                 => "assetTag",

        "location"                  => "locationId",
        "dv_location"               => "location",

        "dv_install_status"         => "installStatus",
        "install_status"            => "installStatusId",

        "manufacturer"              => "manufacturerId",
        "dv_manufacturer"           => "manufacturer",
        "model_number"              => "modelNumber",

        "delivery_date"             => "deliveryDate",
        "po_number"                 => "poNumber",
        "u_asset_id"                => "assetId",
        "u_asset_receipt_date_time" => "assetReceiptDateTime",
        "dv_u_p_o__requestor"       => "poRequestor",
        "u_p_o__requestor"          => "poRequestorId",

        "sys_created_by"            => "sysCreatedBy",
        "sys_created_on"            => "sysCreatedOn",
        "sys_updated_by"            => "sysUpdatedBy",
        "sys_updated_on"            => "sysUpdatedOn",
    );

    /**
     * @param bool $idAutoIncremented
     */
    public function __construct($idAutoIncremented = false)
    {
        $this->dbIndex = 'sncache';
        $this->tableName = 'cmdb_ci';
        $this->idAutoIncremented = $idAutoIncremented;
		parent::__construct();

        $this->sysLog->debug();

        // create reverse name mapping hash
        foreach (self::$nameMapping as $cmdbProp => $thisProp) {
            self::$reverseNameMapping[$thisProp] = $cmdbProp;
        }
    }

    /**
     * @param  $sysId
     * @return CI
     */
    public function getById($sysId)
    {
        return $this->getBySysId($sysId);
    }

    /**
     * @param   $sysId
     * @return  CI
     */
    public function getBySysId($sysId)
    {
        $sql = "select {$this->getQueryColumnsStr()}
		        from   \"{$this->schemaName}\".\"{$this->tableName}\"
		        where  sys_id = '" . $sysId . "';";
        $row = $this->sqlQueryRow($sql);
        return $this->_set($row);
    }

    /**
     * @param $name
     * @throws \ErrorException
     * @return CI
     */
    public function getByName($name)
    {
        $sql = "select {$this->getQueryColumnsStr()}
		        from   \"{$this->schemaName}\".\"{$this->tableName}\"
		        where  name = '" . $name . "'
		          and  install_status != 117
		          and  install_status != 1501
		          and  sys_class_name != 'cmdb_ci_network_adapter'
		          and  sys_class_name != 'u_network_module'
		          and  sys_class_name != 'cmdb_ci_circuit'
		          and  sys_class_name != 'u_subsystem';";
        $row = $this->sqlQueryRow($sql);
        if (count($row) > 1) {
            throw new \ErrorException("More than one record returned", self::MULTIPLE_ENTRIES);
        }

        return $this->_set($row);
    }

    /**
     * @param $name
     * @throws \ErrorException
     * @return CI
     */
    public function getByNameLike($name)
    {
        $sql = "select {$this->getQueryColumnsStr()}
		        from   \"{$this->schemaName}\".\"{$this->tableName}\"
		        where  name LIKE '%" . $name . "%'
		          and  install_status != 117
		          and  install_status != 1501
		          and  sys_class_name != 'cmdb_ci_network_adapter'
		          and  sys_class_name != 'u_network_module'
		          and  sys_class_name != 'cmdb_ci_circuit'
		          and  sys_class_name != 'u_subsystem';";
        $row = $this->sqlQueryRow($sql);
        if (count($row) > 1) {
            throw new \ErrorException("More than one record returned", self::MULTIPLE_ENTRIES);
        }
        return $this->_set($row);
    }

    /**
     * @param $name
     * @throws \ErrorException
     * @return CI
     */
    public function getByNameStartsWith($name)
    {
        $sql = "select {$this->getQueryColumnsStr()}
		        from   \"{$this->schemaName}\".\"{$this->tableName}\"
		        where  name LIKE '" . $name . "%'
		          and  install_status != 117
		          and  install_status != 1501
		          and  sys_class_name != 'cmdb_ci_network_adapter'
		          and  sys_class_name != 'u_network_module'
		          and  sys_class_name != 'cmdb_ci_circuit'
		          and  sys_class_name != 'u_subsystem';";
        $row = $this->sqlQueryRow($sql);
        if (count($row) > 1) {
            throw new \ErrorException("More than one record returned", self::MULTIPLE_ENTRIES);
        }
        return $this->_set($row);
    }

    /**
     * @param $serialNumber
     * @throws \ErrorException
     * @return CI
     */
    public function getBySerialNumber($serialNumber)
    {
        $sql = "select {$this->getQueryColumnsStr()}
		        from   \"{$this->schemaName}\".\"{$this->tableName}\"
		        where  serial_number = '" . $serialNumber . "'
		          and  install_status != 117
		          and  install_status != 1501
		          and  sys_class_name != 'cmdb_ci_network_adapter'
		          and  sys_class_name != 'u_network_module'
		          and  sys_class_name != 'cmdb_ci_circuit'
		          and  sys_class_name != 'u_subsystem';";
        $row = $this->sqlQueryRow($sql);
        if (count($row) > 1) {
            throw new \ErrorException("More than one record returned", self::MULTIPLE_ENTRIES);
        }
        return $this->_set($row);
    }

    /**
     * @param $serialNumber
     * @throws \ErrorException
     * @return CI
     */
    public function getBySerialNumberLike($serialNumber)
    {
        $sql = "select {$this->getQueryColumnsStr()}
		        from   \"{$this->schemaName}\".\"{$this->tableName}\"
		        where  serial_number LIKE '%" . $serialNumber . "%'
		          and  install_status != 117
		          and  install_status != 1501
		          and  sys_class_name != 'cmdb_ci_network_adapter'
		          and  sys_class_name != 'u_network_module'
		          and  sys_class_name != 'cmdb_ci_circuit'
		          and  sys_class_name != 'u_subsystem';";
        $row = $this->sqlQueryRow($sql);
        if (count($row) > 1) {
            throw new \ErrorException("More than one record returned", self::MULTIPLE_ENTRIES);
        }
        return $this->_set($row);
    }

    /**
     * @param $assetTag
     * @throws \ErrorException
     * @return CI
     */
    public function getByAssetTag($assetTag)
    {
        $sql = "select {$this->getQueryColumnsStr()}
		        from   \"{$this->schemaName}\".\"{$this->tableName}\"
		        where  asset_tag = '" . $assetTag . "'
		          and  install_status != 117
		          and  install_status != 1501
		          and  sys_class_name != 'cmdb_ci_network_adapter'
		          and  sys_class_name != 'u_network_module'
		          and  sys_class_name != 'cmdb_ci_circuit'
		          and  sys_class_name != 'u_subsystem';";
        $row = $this->sqlQueryRow($sql);
        if (count($row) > 1) {
            throw new \ErrorException("More than one record returned", self::MULTIPLE_ENTRIES);
        }
        return $this->_set($row);
    }

    /**
     * @param $assetTag
     * @throws \ErrorException
     * @return CI
     */
    public function getByAssetTagLike($assetTag)
    {
        $sql = "select {$this->getQueryColumnsStr()}
		        from   \"{$this->schemaName}\".\"{$this->tableName}\"
		        where  asset_tag = '" . $assetTag . "'
		          and  install_status != 117
		          and  install_status != 1501
		          and  sys_class_name != 'cmdb_ci_network_adapter'
		          and  sys_class_name != 'u_network_module'
		          and  sys_class_name != 'cmdb_ci_circuit'
		          and  sys_class_name != 'u_subsystem';";
        $row = $this->sqlQueryRow($sql);
        if (count($row) > 1) {
            throw new \ErrorException("More than one record returned", self::MULTIPLE_ENTRIES);
        }
        return $this->_set($row);
    }

    // *******************************************************************************
    // * Getters and Setters
    // *****************************************************************************

    /**
     * @param $logLevel
     * @return void
     */
    public function setLogLevel($logLevel)
    {
        $this->logLevel = $logLevel;
    }

    /**
     * @return mixed
     */
    public function getLogLevel()
    {
        return $this->logLevel;
    }

    /**
     * @return array
     */
    public static function getNameMapping()
    {
        return self::$nameMapping;
    }

    /**
     * @return array
     */
    public static function getReverseNameMapping()
    {
        return self::$reverseNameMapping;
    }

    /**
     * @param null $dbRowObj
     * @return CI
     */
    private function _set($dbRowObj = null)
    {
        $this->sysLog->debug();

        $o = new CI();
        foreach (self::$nameMapping as $cmdbProp => $modelProp) {
            if ($dbRowObj && property_exists($dbRowObj, $cmdbProp)) {
                $o->set($modelProp, $dbRowObj->$cmdbProp);
            } else {
                $o->set($modelProp, null);
            }
        }
        return $o;
    }
}
