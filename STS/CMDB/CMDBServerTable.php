<?php
/*******************************************************************************
 *
 * $Id: CMDBServerTable.php 82444 2014-01-03 14:28:59Z rcallaha $
 * $Date: 2014-01-03 09:28:59 -0500 (Fri, 03 Jan 2014) $
 * $Author: rcallaha $
 * $Revision: 82444 $
 * $HeadURL: https://svn.ultradns.net/svn/sts_tools/sts-lib/trunk/STS/CMDB/CMDBServerTable.php $
 *
 *******************************************************************************
 */

namespace STS\CMDB;

class CMDBServerTable extends CMDBDAO
{
    protected static $reverseNameMapping = array();
    protected static $nameMapping = array(
        "sys_id"                         => "sysId",
        "sys_class_name"                 => "sysClassName",
        "name"                           => "name",
        "dv_classification"              => "classification",

        "serial_number"                  => "serialNumber",
        "asset_tag"                      => "assetTag",

        'dv_u_business_service'          => 'businessService',
        'u_business_service'             => 'businessServiceId',
        'dv_u_business_service_s_'       => 'businessServices',
        'u_business_service_s_'          => 'businessServicesIds',
        "dv_u_cmdb_subsystem_list"       => "subsystemList",
        "u_cmdb_subsystem_list"          => "subsystemListId",

        "dv_install_status"              => "installStatus",
        "install_status"                 => "installStatusId",

        "dv_firewall_status"             => "firewallStatus",
        "dv_hardware_status"             => "hardwareStatus",
        "dv_operational_status"          => "operationalStatus",

        "dv_u_environment"               => "environment",
        "u_environment"                  => "environmentId",

        "u_hosted_on"                    => "hostedOnId",
        "dv_u_hosted_on"                 => "hostedOn",
        "u_distribution_switch"          => "distributionSwitch",

        "dv_u_host_type"                 => "deviceType",
        "u_host_type"                    => "deviceTypeId",
        "dv_u_location_type"             => "locationType",
        "u_location_type"                => "locationTypeId",

        "virtual"                        => "isVirtual",

        "location"                       => "locationId",
        "dv_location"                    => "location",
        "u_rack"                         => "rackId",
        "dv_u_rack"                      => "rack",
        "u_number_rack_units"            => "numberOfRackUnits",
        "u_rack_position"                => "rackPosition",

        "ip_address"                     => "ipAddress",

        "cpu_manufacturer"               => "cpuManufacturerId",
        "dv_cpu_manufacturer"            => "cpuManufacturer",
        "cpu_count"                      => "cpuCount",
        "cpu_core_count"                 => "cpuCoreCount",
        "cpu_name"                       => "cpuName",
        "cpu_speed"                      => "cpuSpeed",
        "cpu_type"                       => "cpuType",

        "manufacturer"                   => "manufacturerId",
        "dv_manufacturer"                => "manufacturer",
        "model_number"                   => "modelNumber",

        "ram"                            => "ram",
        "disk_space"                     => "diskSpace",
        "os"                             => "os",
        "os_version"                     => "osVersion",
        "os_service_pack"                => "osServicePack",

        "u_watts"                        => "watts",
        "comments"                       => "comments",
        "short_description"              => "shortDescription",

        "u_maintenance_contract_end_dat" => "maintContractEndDate",
        "u_maintenance_contract_start_d" => "maintContractStartDate",

        "u_last_backup_date"             => "lastBackupDate",
        "u_backup_directories"           => "backupDirectories",
        "u_in_ehealth"                   => "inEHealth",

        "install_date"                   => "installDate",
        "u_bios_date"                    => "biosDate",
        "dv_u_last_ddmi_update"          => "lastDdmiUpdate",
        "last_discovered"                => "lastDiscovered",
        "u_powerpath_version"            => "powerpathVersion",

        "u_data_source"                  => "dataSource",
        "discovery_source"               => "discoverySource",
        "u_sync_update"                  => "syncUpdate",
        "attributes"                     => "attributes",

        "sys_created_by"                 => "sysCreatedBy",
        "sys_created_on"                 => "sysCreatedOn",
        "sys_updated_by"                 => "sysUpdatedBy",
        "sys_updated_on"                 => "sysUpdatedOn",
    );

    protected $ciTable;
    protected $format;

    private $_baseFilter = 'sys_class_name!=^install_statusNOT IN117,1501';

    /**
     * @param mixed $arg
     */
    public function __construct($arg = null) {
        $useUserCredentials = false;
        $config             = null;
        if (is_bool($arg)) {
            $useUserCredentials = $arg;
            parent::__construct($useUserCredentials);
        } else if (is_array($arg)) {
            $config = $arg;
            parent::__construct($config);
        } else {
            parent::__construct($useUserCredentials);
        }
        $this->sysLog->debug();

        $this->ciTable = "cmdb_ci_server";
        $this->format  = "JSON";

        // create reverse name mapping hash
        foreach (self::$nameMapping as $cmdbProp => $thisProp) {
            self::$reverseNameMapping[$thisProp] = $cmdbProp;
        }
    }

    /**
     * @param      $sysId
     * @param bool $raw
     * @return mixed|CMDBServer
     */
    public function getById($sysId, $raw = false) {
        $this->sysLog->debug("sysId=" . $sysId);
        return $this->getBySysId($sysId, $raw);
    }

    /**
     * @param      $sysId
     * @param bool $raw
     * @return mixed|CMDBServer
     */
    public function getBySysId($sysId, $raw = false) {
        #error_log("CMDBServerTable::getBySysId({$sysId}): \$this->curl->getUsername() = " . $this->curl->getUsername() . ", \$this->curl->getPassword() = " . $this->curl->getPassword());
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
     * @param bool $anyStatus
     * @param bool $raw
     * @return mixed|CMDBServer
     */
    public function getByName($name, $anyStatus = false, $raw = false) {
        $this->sysLog->debug("name=" . $name);

        if ($anyStatus) {
            $query = "sys_class_name!=^name=" . $name;
        } else {
            $query = $this->_baseFilter . "^name=" . $name;
        }
        $result = $this->getRecord($this->ciTable, $query);
        if (!$raw) {
            return $this->_set($result);
        } else {
            return $result;
        }
    }

    /**
     * @param      $name
     * @param bool $anyStatus
     * @return CMDBServer
     */
    public function getByNameLike($name, $anyStatus = false) {
        $this->sysLog->debug("name=" . $name);
        if ($anyStatus) {
            $query = "sys_class_name!=^nameLIKE" . $name;
        } else {
            $query = $this->_baseFilter . "^nameLIKE" . $name;
        }
        $return = $this->getRecord($this->ciTable, $query);
        return $this->_set($return);
    }

    /**
     * @param      $name
     * @param      $anyStatus
     * @param bool $raw
     * @return mixed|CMDBServer
     */
    public function getByNameStartsWith($name, $anyStatus = false, $raw = false) {
        $this->sysLog->debug("name=" . $name);
        if ($anyStatus) {
            $query = "sys_class_name!=^nameSTARTSWITH" . $name;
        } else {
            $query = $this->_baseFilter . "^nameSTARTSWITH" . $name;
        }
        $result = $this->getRecord($this->ciTable, $query);
        if (!$raw) {
            return $this->_set($result);
        } else {
            return $result;
        }
    }

    /**
     * @param $name
     * @param $anyStatus
     * @return CMDBServer[]
     */
    public function getAllByNameLike($name, $anyStatus = false) {
        $this->sysLog->debug("name=" . $name);
        if ($anyStatus) {
            $query = "sys_class_name!=^nameLIKE" . $name;
        } else {
            $query = $this->_baseFilter . "^nameLIKE" . $name;
        }
        $records = $this->getRecords($this->ciTable, $query);
        $objects = array();
        for ($i = 0; $i < count($records); $i++) {
            $objects[] = $this->_set($records[$i]);
        }
        return $objects;
    }

    /**
     * @param $serialNumber
     * @param $anyStatus
     * @return CMDBServer
     */
    public function getBySerialNumber($serialNumber, $anyStatus = false) {
        $this->sysLog->debug("serialNumber=" . $serialNumber);
        if ($anyStatus) {
            $query = "sys_class_name!=^serial_number=" . $serialNumber;
        } else {
            $query = $this->_baseFilter . "^serial_number=" . $serialNumber;
        }
        $return = $this->getRecord($this->ciTable, $query);
        return $this->_set($return);
    }

    /**
     * @param $serialNumber
     * @return CMDBServer[]
     */
    public function getAllBySerialNumber($serialNumber) {
        $this->sysLog->debug("serialNumber=" . $serialNumber);
        $query   = $this->_baseFilter . "^serial_numberLIKE" . $serialNumber;
        $records = $this->getRecords($this->ciTable, $query);
        $objects = array();
        for ($i = 0; $i < count($records); $i++) {
            $objects[] = $this->_set($records[$i]);
        }
        return $objects;
    }

    /**
     * @param $subsystemId
     * @return CMDBServer[]
     */
    public function getBySubsystemId($subsystemId) {
        $this->sysLog->debug("subsystemId=" . $subsystemId);
        $query   = $this->_baseFilter . "^u_cmdb_subsystem_listLIKE" . $subsystemId;
        $records = $this->getRecords($this->ciTable, $query);
        $objects = array();
        for ($i = 0; $i < count($records); $i++) {
            $objects[] = $this->_set($records[$i]);
        }
        return $objects;
    }

    /**
     * @param $businessServiceId
     * @return CMDBServer[]
     */
    public function getByBusinessServiceId($businessServiceId) {
        $this->sysLog->debug();
        $query   = $this->_baseFilter . "^u_business_service=" . $businessServiceId;
        $records = $this->getRecords($this->ciTable, $query);
        $objects = array();
        for ($i = 0; $i < count($records); $i++) {
            $objects[] = $this->_set($records[$i]);
        }
        return $objects;
    }

    /**
     * @param $bsArray
     * @return CMDBServer[]
     */
    public function getByBusinessServicesArray($bsArray) {
        $this->sysLog->debug();
        $query = $this->_baseFilter . "^";
        for ($i = 0; $i < count($bsArray); $i++) {
            $bsName = $bsArray[$i];
            if ($i != 0) {
                $query .= "^OR";
            }
            $query .= "u_business_service.name=" . $bsName;
        }
        $records = $this->getRecords($this->ciTable, $query);
        $objects = array();
        for ($i = 0; $i < count($records); $i++) {
            $objects[] = $this->_set($records[$i]);
        }
        return $objects;
    }

    /**
     * @param $sysId
     * @return CMDBServer[]
     */
    public function getChildren($sysId) {
        $this->sysLog->debug();
        $query   = "u_hosted_on={$sysId}";
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
     * @return CMDBServer[]|mixed
     */
    public function getByQueryString($query, $raw = false) {
        $this->sysLog->debug();
        $records = $this->getRecords($this->ciTable, $query);
        $objects = array();
        for ($i = 0; $i < count($records); $i++) {
            if (!$raw) {
                $objects[] = $this->_set($records[$i]);
            } else {
                $objects[] = $records[$i];
            }
        }
        return $objects;
    }

    /**
     * @param $name
     * @return CMDBServer
     */
    public function hostLookup($name) {
        // if we have an FQDN, then use that and don't try anything else
        if (preg_match("/([\w\d_\-]+)\./", $name, $m)) {
            $shortName = $m[1];
            try {
                $server = $this->getByName($name);
            } catch (\Exception $e) {
                return new CMDBServer();
            }

            if ($server->getSysId()) {
                return $server;
            } else {
                try {
                    $server = $this->getByNameLike($shortName . ".");
                } catch (\Exception $e) {
                    return new CMDBServer();
                }
                return $server;
            }
        } else {
            try {
                $server = $this->getByNameLike($name . ".");
            } catch (\Exception $e) {
                return new CMDBServer();
            }

            if ($server->getSysId()) {
                return $server;
            } else {
                try {
                    $server = $this->getByNameLike($name);
                } catch (\Exception $e) {
                    return new CMDBServer();
                }
                return $server;
            }
        }
    }

    /**
     * @param $sysId
     * @param $json
     * @return mixed|object
     */
    public function updateByJson($sysId, $json) {
        $this->sysLog->debug("json=" . $json);
        return parent::updateCI($this->ciTable, $sysId, $json);
    }

    /**
     * @param $json
     * @return mixed|object
     */
    public function createByJson($json) {
        $this->sysLog->debug("json=" . $json);
        return parent::createCI($this->ciTable, $json);
    }

    /**
     * @param CMDBServer $ci
     * @return CMDBServer
     * @throws \ErrorException
     */
    public function update(CMDBServer $ci) {
        $this->sysLog->debug();
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

    public function delete(CMDBServer $ci) {
        return parent::deleteCI($this->ciTable, $ci->getSysId());
    }

    /**
     * @param CMDBServer $ci
     * @return mixed|CMDBServer
     * @throws \ErrorException
     */
    public function create(CMDBServer $ci) {
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

    private function buildJson(CMDBServer $ci) {
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
        return $json;
    }


    // *******************************************************************************
    // * Getters and Setters
    // *****************************************************************************

    /**
     * @param $logLevel
     * @return void
     */
    public function setLogLevel($logLevel) {
        $this->logLevel = $logLevel;
    }

    /**
     * @return int
     */
    public function getLogLevel() {
        return $this->logLevel;
    }

    /**
     * @param $format
     */
    public function setFormat($format) {
        $this->format = $format;
    }

    /**
     * @return string
     */
    public function getFormat() {
        return $this->format;
    }

    /**
     * @return array
     */
    public static function getNameMapping() {
        return self::$nameMapping;
    }

    /**
     * @return array
     */
    public static function getReverseNameMapping() {
        return self::$reverseNameMapping;
    }

    /**
     * @return string
     */
    public function getCiTable() {
        return $this->ciTable;
    }

    /**
     * @param null $dbRowObj
     * @return CMDBServer
     */
    private function _set($dbRowObj = null) {
        $this->sysLog->debug();
        $o = new CMDBServer();
        foreach (self::$nameMapping as $cmdbProp => $modelProp) {
            if ($dbRowObj && property_exists($dbRowObj, $cmdbProp)) {
                $o->set($modelProp, $dbRowObj->$cmdbProp);
            } else {
                $o->set($modelProp, null);
            }
        }
        $o->clearChanges();
        return $o;
    }
}
