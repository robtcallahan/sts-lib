<?php

namespace STS\SNCache;

use STS\DB\DBTable;

class ServerTable extends DBTable
{
    const MULTIPLE_ENTRIES = 69;

    protected $dbIndex = 'sncache';
    protected $tableName = 'cmdb_ci_server';
    protected $idAutoIncremented = false;

    protected static $reverseNameMapping = array();
    protected static $nameMapping = array(
        "sys_id"                => "sysId",
        "sys_class_name"        => "sysClassName",
        "name"                  => "name",
        "classification"        => "classification",

        "serial_number"         => "serialNumber",
        "asset_tag"             => "assetTag",

        'u_business_service_s_' => 'businessServiceIds',
        "u_cmdb_subsystem_list" => "subsystemListIds",
        "install_status"        => "installStatusId",
        "u_environment"         => "environmentId",

        "u_hosted_on"           => "hostedOnId",

        "u_distribution_switch" => "distributionSwitch",

        "u_host_type"           => "deviceTypeId",

        "location"              => "locationId",
        "u_rack"                => "rackId",
        "u_number_rack_units"   => "numberOfRackUnits",
        "u_rack_position"       => "rackPosition",

        "ip_address"            => "ipAddress",

        "cpu_manufacturer"      => "cpuManufacturerId",
        "cpu_count"             => "cpuCount",
        "cpu_core_count"        => "cpuCoreCount",
        "cpu_speed"             => "cpuSpeed",
        "cpu_type"              => "cpuType",

        "manufacturer"          => "manufacturerId",
        "model_number"          => "modelNumber",

        "ram"                   => "ram",
        "disk_space"            => "diskSpace",
        "os"                    => "os",
        "os_version"            => "osVersion",
        "os_service_pack"       => "osServicePack",

        "comments"              => "comments",
        "short_description"     => "shortDescription",

        "install_date"          => "installDate",

        "u_powerpath_version"   => "powerpathVersion",
        "u_bios_date"           => "biosDate",


        "u_last_backup_date"    => "lastBackupDate",
        "u_backup_directories"  => "backupDirectories",

        "u_data_source"         => "dataSource",
        "discovery_source"      => "discoverySource",
        "last_discovered"       => "lastDiscovered",

        "sys_created_by"        => "sysCreatedBy",
        "sys_created_on"        => "sysCreatedOn",
        "sys_updated_by"        => "sysUpdatedBy",
        "sys_updated_on"        => "sysUpdatedOn",
    );

    protected $schemaName;
    protected $tableAlias = "t";

    protected $select;
    protected $from;
    protected $join;
    protected $where;

    protected static $joinTables = array(
        array(
            'table'    => "sys_choice",
            'alias'    => "sc",
            'joinType' => "left",
            'joinTo'   => "value :: Integer",
            'joinFrom' => "install_status",
            'joinAnd'  => "sc.name = 'cmdb_ci_server' and sc.element = 'install_status' and inactive = false",
            'columns'  => array(
                "label" => "installStatus",
            )
        ),
        array(
            'table'    => "sys_choice",
            'alias'    => "sc2",
            'joinType' => "left",
            'joinTo'   => "value :: Integer",
            'joinFrom' => "u_host_type",
            'joinAnd'  => "sc2.name = 'cmdb_ci_server' and sc2.element = 'u_host_type' and sc2.inactive = false",
            'columns'  => array(
                "label" => "deviceType",
            )
        ),
        array(
            'table'    => "cmdb_ci",
            'alias'    => "ci",
            'joinType' => "left",
            'joinTo'   => "sys_id",
            'joinFrom' => "u_hosted_on",
            'joinAnd'  => "ci.sys_id = t.u_hosted_on",
            'columns'  => array(
                "name" => "hostedOn",
            )
        ),

        /*
         left join sn_cache_prod.cmdb_ci_service bs on
         bs.sys_id = regexp_replace(t.u_business_service_s_, ',.*', '') and
         bs.operational_status = 1
         */
        array(
            'table'           => "cmdb_ci_service",
            'alias'           => "bs",
            'joinType'        => "left",
            'joinTo'          => "sys_id",
            //'joinFrom'        => "u_business_service_s_",
            'joinFromLiteral' => "regexp_replace(t.u_business_service_s_, ',.*', '')",
            'joinAnd'         => "bs.operational_status = 1",
            'columns'         => array(
                "name" => "businessServices",
            )
        ),
        array(
            'table'           => "u_subsystem",
            'alias'           => "sub",
            'joinType'        => "left",
            'joinTo'          => "sys_id",
            //'joinFrom' => "u_cmdb_subsystem_list",
            'joinFromLiteral' => "regexp_replace(t.u_cmdb_subsystem_list, ',.*', '')",
            'joinAnd'         => "sub.operational_status = 7",
            'columns'         => array(
                "name" => "subsystemList",
            )
        ),
        array(
            'table'           => "core_company",
            'alias'           => "cc1",
            'joinType'        => "left",
            'joinTo'          => "sys_id",
            'joinFrom'        => "manufacturer",
            'columns'         => array(
                "name" => "manufacturer",
            )
        ),
        array(
            'table'    => "core_company",
            'alias'    => "cc2",
            'joinType' => "left",
            'joinTo'   => "sys_id",
            'joinFrom' => "cpu_manufacturer",
            'columns'  => array(
                "name" => "cpuManufacturer",
            )
        ),
    );

    public function __construct($config = null) {
        if ($config && is_array($config)) {
            $this->schemaName = $config['databases'][$this->dbIndex]['schema'];
        }
        if ($config && is_object($config)) {
            $dbIndex          = $this->dbIndex;
            $this->schemaName = $config->databases->$dbIndex->schema;
        }

        if ($config && is_array($config)) {
            // need to add these to the config since won't be in the config file
            $config['tableName']         = $this->tableName;
            $config['dbIndex']           = $this->dbIndex;
            $config['idAutoIncremented'] = $this->idAutoIncremented;
        }

        parent::__construct($config);

        $this->select = "";
        $tmpArray     = array();
        foreach (self::$nameMapping as $cmdbProp => $thisProp) {
            $reverseNameMapping[$thisProp] = $cmdbProp;
            $tmpArray[]                    = "{$this->tableAlias}.{$cmdbProp} as \"{$thisProp}\"";
        }

        $this->select = "select " . implode(",\n\t", $tmpArray);
        if (isset(self::$joinTables) && count(self::$joinTables) > 0) {
            $this->select .= ",";
        }
        $this->select .= "\n";

        $this->from  = "from {$this->schemaName}.{$this->tableName} as {$this->tableAlias}\n";
        $this->join  = "";
        $this->where = "where 1 = 1\n";
        for ($i = 0; $i < count(self::$joinTables); $i++) {
            $jt = self::$joinTables[$i];
            if ($i > 0) {
                $this->select .= ",\n";
            }

            $tmpArray = array();
            foreach ($jt['columns'] as $name => $alias) {
                $tmpArray[] = "{$jt['alias']}.{$name} as \"{$alias}\"";
            }
            $this->select .= implode(",\n\t", $tmpArray);

            if (array_key_exists('joinFromLiteral', $jt)) {
                $this->join .= "{$jt['joinType']} join {$this->schemaName}.{$jt['table']} {$jt['alias']} on {$jt['alias']}.{$jt['joinTo']} = {$jt['joinFromLiteral']}";
            } else {
                $this->join .= "{$jt['joinType']} join {$this->schemaName}.{$jt['table']} {$jt['alias']} on {$jt['alias']}.{$jt['joinTo']} = {$this->tableAlias}.{$jt['joinFrom']}";
            }
            if (array_key_exists('joinAnd', $jt)) {
                $this->join .= " and {$jt['joinAnd']}";
            };
            $this->join .= "\n";

            if (array_key_exists('where', $jt) && $jt['where']) {
                $this->where .= " and {$jt['where']}\n";
            }
        }
        $this->select .= "\n";


        $this->query = $this->select . $this->from . $this->join . $this->where;
    }

    /**
     * @param  $sysId
     * @return Server
     */
    public function getById($sysId) {
        $this->sysLog->debug("sysId=" . $sysId);
        return $this->getBySysId($sysId);
    }

    /**
     * @param  $sysId
     * @return Server
     */
    public function getBySysId($sysId) {
        $sql = $this->query . "\n and {$this->tableAlias}.sys_id = '" . $sysId . "';";
        $row = $this->sqlQueryRow($sql);
        return $this->_set($row);
    }

    /**
     * @param $name
     * @throws \ErrorException
     * @return Server
     */
    public function getByName($name) {
        $sql = $this->query . "\n and {$this->tableAlias}.name = '" . $name . "';";
        $row = $this->sqlQueryRow($sql);
        if (count($row) > 1) {
            throw new \ErrorException("More than one record returned", self::MULTIPLE_ENTRIES);
        }
        return $this->_set($row);
    }

    /**
     * @param $name
     * @throws \ErrorException
     * @return Server
     */
    public function getByNameLike($name) {
        /*
        $sql = "select {$this->getQueryColumnsStr()}
		        from   \"{$this->schemaName}\".\"{$this->tableName}\"
		        where  name LIKE '%" . $name . "%'
		          and  install_status != 117
		          and  install_status != 1501;";
        */

        $sql = $this->query . "\n and {$this->tableAlias}.name LIKE  '%" . $name . "%';";
        $row = $this->sqlQueryRow($sql);
        if (count($row) > 1) {
            throw new \ErrorException("More than one record returned", self::MULTIPLE_ENTRIES);
        }
        return $this->_set($row);
    }

    /**
     * @param $name
     * @param bool $anyStatus
     * @throws \ErrorException
     * @return Server
     */
    public function getByNameStartsWith($name, $anyStatus = false) {
        $sql = $this->query . "\n and {$this->tableAlias}.name LIKE '" . $name . "%';";
        $row = $this->sqlQueryRow($sql);
        if (count($row) > 1) {
            throw new \ErrorException("More than one record returned", self::MULTIPLE_ENTRIES);
        }
        return $this->_set($row);
    }

    /**
     * @param $serialNumber
     * @throws \ErrorException
     * @return Server
     */
    public function getBySerialNumber($serialNumber) {
        $sql = $this->query . "\n and lower({$this->tableAlias}.serial_number) = '" . strtolower($serialNumber) . "';";
        $row = $this->sqlQueryRow($sql);
        if (count($row) > 1) {
            throw new \ErrorException("More than one record returned", self::MULTIPLE_ENTRIES);
        }
        return $this->_set($row);
    }

    /**
     * @param $serialNumber
     * @return Server[]
     */
    public function getAllBySerialNumber($serialNumber) {
        $sql     = $this->query . "\n and lower({$this->tableAlias}.serial_number) = '" . strtolower($serialNumber) . "';";
        $rows    = $this->sqlQuery($sql);
        $results = array();
        foreach ($rows as $row) {
            $results[] = $this->_set($row);
        }
        return $results;
    }

    /**
     * @param $serialNumber
     * @throws \ErrorException
     * @return Server
     */
    public function getBySerialNumberLike($serialNumber) {
        $sql = $this->query . "\n and lower({$this->tableAlias}.serial_number) LIKE '%" . strtolower($serialNumber) . "%';";
        $row = $this->sqlQueryRow($sql);
        if (count($row) > 1) {
            throw new \ErrorException("More than one record returned", self::MULTIPLE_ENTRIES);
        }
        return $this->_set($row);
    }

    /**
     * @param $assetTag
     * @throws \ErrorException
     * @return Server
     */
    public function getByAssetTag($assetTag) {
        $sql = $this->query . "\n and {$this->tableAlias}.asset_tag = '" . $assetTag . "';";
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
     * @param null $dbRowObj
     * @return Server
     */
    private function _set($dbRowObj = null) {
        $this->sysLog->debug();

        $o = new Server();
        foreach (self::$nameMapping as $cmdbProp => $modelProp) {
            if ($dbRowObj && property_exists($dbRowObj, $modelProp)) {
                $o->set($modelProp, $dbRowObj->$modelProp);
            } else {
                $o->set($modelProp, null);
            }
        }
        if (isset(self::$joinTables) && count(self::$joinTables)) {
            foreach (self::$joinTables as $jt) {
                foreach ($jt['columns'] as $cmdbProp => $modelProp) {
                    if ($dbRowObj && property_exists($dbRowObj, $modelProp)) {
                        $o->set($modelProp, $dbRowObj->$modelProp);
                    } else {
                        $o->set($modelProp, null);
                    }
                }
            }
        }
        return $o;
    }
}
