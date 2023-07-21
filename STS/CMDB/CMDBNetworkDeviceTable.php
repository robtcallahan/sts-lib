<?php
/*******************************************************************************
 *
 * $Id: CMDBNetworkDeviceTable.php 82444 2014-01-03 14:28:59Z rcallaha $
 * $Date: 2014-01-03 09:28:59 -0500 (Fri, 03 Jan 2014) $
 * $Author: rcallaha $
 * $Revision: 82444 $
 * $HeadURL: https://svn.ultradns.net/svn/sts_tools/sts-lib/trunk/STS/CMDB/CMDBNetworkDeviceTable.php $
 *
 *******************************************************************************
 */

namespace STS\CMDB;



class CMDBNetworkDeviceTable extends CMDBDAO
{
    protected static $reverseNameMapping = array();
    protected static $nameMapping = array(
        "sys_id"                         => "sysId",
        "sys_class_name"                 => "sysClassName",
        "name"                           => "name",

        "dv_classification"              => "classification",
        "asset_tag"                      => "assetTag",
        "serial_number"                  => "serialNumber",

        'dv_u_business_service'          => 'businessService',
        'u_business_service'             => 'businessServiceId',
        'dv_u_business_service_s_'       => 'businessServices',
        'u_business_service_s_'          => 'businessServicesIds',
        "u_cmdb_subsystem_list"          => "subsystemListId",
        "dv_u_cmdb_subsystem_list"       => "subsystemList",

        "dv_install_status"              => "installStatus",
        "install_status"                 => "installStatusId",

        "dv_hardware_status"             => "hardwareStatus",
        "dv_operational_status"          => "operationalStatus",

        "dv_u_environment"               => "environment",
        "u_environment"                  => "environmentId",

        "device_type"                    => "deviceTypeId",
        "dv_device_type"                 => "deviceType",

        "dv_u_location_type"             => "locationType",
        "u_location_type"                => "locationTypeId",
        "location"                       => "locationId",
        "dv_location"                    => "location",
        "u_rack"                         => "rackId",
        "dv_u_rack"                      => "rack",
        "u_number_rack_units"            => "numberOfRackUnits",
        "u_rack_position"                => "rackPosition",

        "manufacturer"                   => "manufacturerId",
        "dv_manufacturer"                => "manufacturer",
        "model_number"                   => "modelNumber",

        "comments"                       => "comments",
        "short_description"              => "shortDescription",

        "u_maintenance_contract_end_dat" => "maintContractEndDate",
        "u_maintenance_contract_start_d" => "maintContractStartDate",

        "u_data_source"                  => "dataSource",

        "sys_created_by"                 => "sysCreatedBy",
        "sys_created_on"                 => "sysCreatedOn",
        "sys_updated_by"                 => "sysUpdatedBy",
        "sys_updated_on"                 => "sysUpdatedOn",
    );

    protected $ciTable;
    protected $format;
    protected $logFilePointer;
    protected $silentMode;
    protected $json;

    private $_baseFilter = 'sys_class_name!=^install_statusNOT%20IN117,1501';

    /**
     * @param mixed $arg
     */
    public function __construct($arg = null)
    {
        $useUserCredentials = false;
        $config = null;
        if (is_bool($arg)) {
            $useUserCredentials = $arg;
            parent::__construct($useUserCredentials);
        }
        else if (is_array($arg)) {
            $config = $arg;
            parent::__construct($config);
        } else {
            parent::__construct($useUserCredentials);
        }
        $this->sysLog->debug();

        // define CMDB table and return format
        $this->ciTable = "cmdb_ci_netgear";
        $this->format  = "JSON";

        $this->silentMode = true;

        // create reverse name mapping hash
        foreach (self::$nameMapping as $cmdbProp => $thisProp) {
            self::$reverseNameMapping[$thisProp] = $cmdbProp;
        }
    }

    /**
     * @param      $sysId
     * @param bool $raw
     * @return mixed|CMDBNetworkDevice
     */
    public function getById($sysId, $raw = false)
    {
        $this->sysLog->debug("sysId=" . $sysId);
        return $this->getBySysId($sysId, $raw);
    }

    /**
     * @param      $sysId
     * @param bool $raw
     * @return mixed|CMDBNetworkDevice
     */
    public function getBySysId($sysId, $raw = false)
    {
        $this->sysLog->debug("sysId=" . $sysId);
        $query  = "sys_id={$sysId}";
        $result = $this->getRecord($this->ciTable, $query);
        if (!$raw) {
            return $this->_set($result);
        } else {
            return $result;
        }
    }

    /**
     * @param      $name
     * @param bool $raw
     * @return mixed|CMDBNetworkDevice
     */
    public function getByName($name, $raw = false)
    {
        $this->sysLog->debug("name=" . $name);

        $query  = $this->_baseFilter . "^name=" . $name;
        $result = $this->getRecord($this->ciTable, $query);
        if (!$raw) {
            return $this->_set($result);
        } else {
            return $result;
        }
    }

    /**
     * @param $name
     * @return CMDBNetworkDevice
     */
    public function getByNameLike($name)
    {
        $this->sysLog->debug("name=" . $name);
        $query  = $this->_baseFilter . "^nameLIKE" . $name;
        $result = $this->getRecord($this->ciTable, $query);
        return $this->_set($result);
    }

    /**
     * @param      $name
     * @param bool $raw
     * @return mixed|CMDBNetworkDevice
     */
    public function getByNameStartsWith($name, $raw = false)
    {
        $this->sysLog->debug("name=" . $name);
        $query  = $this->_baseFilter . "^nameSTARTSWITH" . $name;
        $result = $this->getRecord($this->ciTable, $query);
        if (!$raw) {
            return $this->_set($result);
        } else {
            return $result;
        }
    }

    /**
     * @param $name
     * @return CMDBNetworkDevice[]
     */
    public function getAllByNameLike($name)
    {
        $this->sysLog->debug("name=" . $name);
        $query   = $this->_baseFilter . "^nameLIKE" . $name;
        $records = $this->getRecords($this->ciTable, $query);
        $objects = array();
        for ($i = 0; $i < count($records); $i++) {
            $objects[] = $this->_set($records[$i]);
        }
        return $objects;
    }

    /**
     * @param $sn
     * @return CMDBNetworkDevice[]
     */
    public function getAllBySerialNumber($sn)
    {
        $this->sysLog->debug("serialNumber=" . $sn);
        $query   = $this->_baseFilter . "^serial_number=" . $sn;
        $records = $this->getRecords($this->ciTable, $query);
        $objects = array();
        for ($i = 0; $i < count($records); $i++) {
            $objects[] = $this->_set($records[$i]);
        }
        return $objects;
    }

    /**
     * @param $bsArray
     * @return CMDBNetworkDevice[]
     */
    public function getByBusinessServicesArray($bsArray)
    {
        $this->sysLog->debug();
        $query = $this->_baseFilter . "^";
        for ($i = 0; $i < count($bsArray); $i++) {
            $bsName = $bsArray[$i];
            if ($i != 0) {
                $query .= "^OR";
            }
            $query .= "u_business_server.name=" . $bsName;
        }
        $records = $this->getRecords($this->ciTable, $query);
        $objects = array();
        for ($i = 0; $i < count($records); $i++) {
            $objects[] = $this->_set($records[$i]);
        }
        return $objects;
    }

    /**
     * @param      $query
     * @param bool $raw
     * @return CMDBNetworkDevice[]|mixed
     */
    public function getByQueryString($query, $raw = false)
    {
        $this->sysLog->debug();
        if (!$raw) {
            $records = $this->getRecords($this->ciTable, $query);
            $objects = array();
            for ($i = 0; $i < count($records); $i++) {
                $objects[] = $this->_set($records[$i]);
            }
            return $objects;
        } else {
            return $this->getRecords($this->ciTable, $query);
        }
    }

    /**
     * @param $sysId
     * @param $json
     * @return mixed|object
     */
    public function updateByJson($sysId, $json)
    {
        $this->sysLog->debug("json=" . $json);
        $this->json = $json;
        return parent::updateCI($this->ciTable, $sysId, $json);
    }

    /**
     * @param CMDBNetworkDevice $ci
     * @return CMDBNetworkDevice
     * @throws \ErrorException
     */
    public function update(CMDBNetworkDevice $ci)
    {
        if (count($ci->getChanges()) > 0) {
            $json = $this->buildJson($ci);
            if (!property_exists($ci, 'sysId')) {
                throw new \ErrorException("Server instance does not have a sysId defined");
            }
            $ci->clearChanges();
            $this->updateByJson($ci->getSysId(), $json);
            return $this->getBySysId($ci->getSysId());
        } else {
            return $ci;
        }
    }

    /**
     * @param $json
     * @return mixed|object
     */
    public function createByJson($json)
    {
        $this->sysLog->debug("json=" . $json);
        $this->json = $json;
        return parent::createCI($this->ciTable, $json);
    }

    /**
     * @param CMDBNetworkDevice $ci
     * @return mixed|CMDBServer
     * @throws \ErrorException
     */
    public function create(CMDBNetworkDevice $ci)
    {
        $this->sysLog->debug();
        if (count($ci->getChanges()) > 0) {
            $json = $this->buildJson($ci);
            $ci->clearChanges();
            $return = $this->createByJson($json);
            if (property_exists($return, 'records')) {
                if (array_key_exists(0, $return->records)) {
                    return $this->_set($return->records[0]);
                } else {
                    return $ci;
                }
            } else {
                return $this->getByName($ci->getName());
            }
        } else {
            return $ci;
        }
    }

    private function buildJson(CMDBNetworkDevice $ci)
    {
        $json = '';
        foreach ($ci->getChanges() as $prop => $o) {
            if (array_key_exists($prop, self::$reverseNameMapping)) {
                if ($json != "") $json .= ',';
                $json .= '"' . self::$reverseNameMapping[$prop] . '":"' . $o->modifiedValue . '"';
            } else {
                throw new \ErrorException("Trying to set a non-existent property: " . $prop);
            }
        }
        $json = '{' . $json . '}';
        $this->json = $json;
        return $json;
    }


    // *******************************************************************************
    // * Getters and Setters
    // *****************************************************************************

    /**
     * @param $silentMode
     */
    public function setSilentMode($silentMode)
    {
        $this->silentMode = $silentMode;
    }

    /**
     * @param $logFilePtr
     * @return void
     */
    public function setLogFilePtr($logFilePtr)
    {
        $this->logFilePointer = $logFilePtr;
    }

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
     * @return string
     */
    public function getCiTable()
    {
        return $this->ciTable;
    }

    /**
     * @return mixed
     */
    public function getJson()
    {
        return $this->json;
    }

    /**
     * @param null $dbRowObj
     * @return CMDBNetworkDevice
     */
    private function _set($dbRowObj = null)
    {
        $this->sysLog->debug();

        $o = new  CMDBNetworkDevice();
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
