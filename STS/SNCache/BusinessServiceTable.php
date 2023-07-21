<?php

namespace STS\SNCache;

use STS\DB\DBTable;

class BusinessServiceTable extends DBTable
{
    const MULTIPLE_ENTRIES = 69;

    protected static $reverseNameMapping = array();
    protected static $nameMapping = array(
        'sys_id'                  => 'sysId',
        'name'                    => 'name',
        'sys_class_name'          => 'sysClassName',

        'u_description'           => 'description',

        'u_change_notification'   => 'changeNoticationEmail',
        'u_incident_notification' => 'incidentNotificationEmail',

        "sys_created_by"          => "sysCreatedBy",
        "sys_created_on"          => "sysCreatedOn",
        "sys_updated_by"          => "sysUpdatedBy",
        "sys_updated_on"          => "sysUpdatedOn",
    );

    protected static $joinTables = array(
        array(
            'table'    => "sys_choice",
            'alias'    => "sc",
            'joinType' => "left",
            'joinTo'   => "value :: Integer",
            'joinFrom' => "operational_status",
            'joinAnd'  => "sc.name = 'cmdb_ci' and sc.element = 'operational_status'",
            'where'    => "sc.label = 'Operational'",
            'columns'  => array(
                "label"         => "operationalStatus",
            )
        ),
        array(
            'table'    => "sys_user",
            'alias'    => "u1",
            'joinType' => "left",
            'joinTo'   => "sys_id",
            'joinFrom' => "u_customer_support_leader",
            'columns'  => array(
                "sys_id"        => "customerSupportLeaderId",
                "u_displayname" => "customerSupportLeaderFullName",
                "email"         => "customerSupportLeaderEmail",
                "first_name"    => "customerSupportLeaderFirstName",
                "last_name"     => "customerSupportLeaderLastName",
                "user_name"     => "customerSupportLeaderUserName"
            )
        ),
        array(
            'table'    => "sys_user",
            'alias'    => "u2",
            'joinType' => "left",
            'joinTo'   => "sys_id",
            'joinFrom' => "u_development_leader",
            'columns'  => array(
                "sys_id"        => "developmentLeaderId",
                "u_displayname" => "developmentLeaderFullName",
                "email"         => "developmentLeaderEmail",
                "first_name"    => "developmentLeaderFirstName",
                "last_name"     => "developmentLeaderLastName",
                "user_name"     => "developmentLeaderUserName"
            )
        ),
        array(
            'table'    => "sys_user",
            'alias'    => "u3",
            'joinType' => "left",
            'joinTo'   => "sys_id",
            'joinFrom' => "u_incident_executives",
            'columns'  => array(
                "sys_id"        => "incidentExecutivesId",
                "u_displayname" => "incidentExecutivesFullName",
                "email"         => "incidentLeaderEmail",
                "first_name"    => "incidentLeaderFirstName",
                "last_name"     => "incidentLeaderLastName",
                "user_name"     => "incidentLeaderUserName"
            )
        ),
        array(
            'table'    => "sys_user",
            'alias'    => "u4",
            'joinType' => "left",
            'joinTo'   => "sys_id",
            'joinFrom' => "u_operations_leader",
            'columns'  => array(
                "sys_id"        => "operationsLeaderId",
                "u_displayname" => "operationsLeaderFullName",
                "email"         => "operationsLeaderEmail",
                "first_name"    => "operationsLeaderFirstName",
                "last_name"     => "operationsLeaderLastName",
                "user_name"     => "operationsLeaderUserName"
            )
        ),
        array(
            'table'    => "sys_user",
            'alias'    => "u5",
            'joinType' => "left",
            'joinTo'   => "sys_id",
            'joinFrom' => "u_systems_administration_leade",
            'columns'  => array(
                "sys_id"        => "systemsAdminLeaderId",
                "u_displayname" => "systemsAdminLeaderFullName",
                "email"         => "systemsAdminLeaderEmail",
                "first_name"    => "systemsAdminLeaderFirstName",
                "last_name"     => "systemsAdminLeaderLastName",
                "user_name"     => "systemsAdminLeaderUserName"
            )
        ),
        array(
            'table'    => "sys_user",
            'alias'    => "u6",
            'joinType' => "left",
            'joinTo'   => "sys_id",
            'joinFrom' => "u_product_leader",
            'columns'  => array(
                "sys_id"        => "productLeaderId",
                "u_displayname" => "productLeaderFullName",
                "email"         => "productLeaderEmail",
                "first_name"    => "productLeaderFirstName",
                "last_name"     => "productLeaderLastName",
                "user_name"     => "productLeaderUserName"
            )
        ),
        array(
            'table'    => "u_products",
            'alias'    => "prod",
            'joinType' => "left",
            'joinTo'   => "sys_id",
            'joinFrom' => "u_product",
            'columns'  => array(
                "sys_id" => "productId",
                "name"   => "productName"
            )
        ),
    );

    protected $schemaName;
    protected $tableAlias;

    protected $select;
    protected $from;
    protected $join;
    protected $where;

    /**
     * @param $config
     */
    public function __construct($config=null) {
        $this->dbIndex   = 'sncache';
        $this->tableName = 'cmdb_ci_service';
        $this->schemaName = $config['databases'][$this->dbIndex]['schema'];
        $this->tableAlias = "t";

        if ($config && is_array($config)) {
            // need to add these to the config since won't be in the config file
            $config['tableName']         = $this->tableName;
            $config['dbIndex']           = $this->dbIndex;
            $config['idAutoIncremented'] = false;
        }

        parent::__construct($config);

        $this->select   = "";
        $tmpArray = array();
        foreach (self::$nameMapping as $cmdbProp => $thisProp) {
            $reverseNameMapping[$thisProp] = $cmdbProp;
            $tmpArray[]                    = "{$this->tableAlias}.{$cmdbProp} as \"{$thisProp}\"";
        }

        $this->select = "select " . implode(",\n\t", $tmpArray);
        if (isset(self::$joinTables) && count(self::$joinTables) > 0) {
            $this->select .= ",";
        }
        $this->select .= "\n";

        $this->from = "from {$this->schemaName}.{$this->tableName} as {$this->tableAlias}\n";
        $this->join = "";
        $this->where = "where 1 = 1\n";
        for ($i=0; $i<count(self::$joinTables); $i++) {
            $jt = self::$joinTables[$i];
            if ($i > 0) {
                $this->select .= ",\n";
            }

            $tmpArray = array();
            foreach ($jt['columns'] as $name => $alias) {
                $tmpArray[] = "{$jt['alias']}.{$name} as \"{$alias}\"";
            }
            $this->select .= implode(",\n\t", $tmpArray);

            $this->join .= "{$jt['joinType']} join {$this->schemaName}.{$jt['table']} {$jt['alias']} on {$jt['alias']}.{$jt['joinTo']} = {$this->tableAlias}.{$jt['joinFrom']}";
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


    public function getQuery() {
        return $this->query;
    }

    /**
     * @param  $sysId
     * @return BusinessService
     */
    public function getById($sysId) {
        return $this->getBySysId($sysId);
    }


    /**
     * @param   $sysId
     * @return  BusinessService
     */
    public function getBySysId($sysId) {
        $sql = $this->query . "\n and {$this->tableAlias}.sys_id = '" . $sysId . "';";
        $row = $this->sqlQueryRow($sql);
        return $this->_set($row);
    }

    /**
     * @param $name
     * @throws \ErrorException
     * @return BusinessService
     */
    public function getByName($name) {
        $sql = $this->query . "\n and lower({$this->tableAlias}.name) = lower('" . $name . "');";
        $row = $this->sqlQueryRow($sql);
        if (count($row) > 1) {
            throw new \ErrorException("More than one record returned", self::MULTIPLE_ENTRIES);
        }
        return $this->_set($row);
    }

    /**
     * @param $name
     * @throws \ErrorException
     * @return BusinessService[]
     */
    public function getByNameLike($name) {
        $sql = $this->query . "\n and lower({$this->tableAlias}.name) like lower('%" . $name . "%');";
        $rows    = $this->sqlQuery($sql);
        $results = array();
        foreach ($rows as $row) {
            $results[] = $this->_set($row);
        }
        return $results;
    }

    /**
     * @param null $nameSubstring
     * @return BusinessService[]
     */
    public function getAll($nameSubstring=null) {
        $sql =   $this->query . "\n
                 and {$this->tableAlias}.operational_status = 1\n";
        if ($nameSubstring != null) {
            $sql .= " and lower(t.name) LIKE '%" . strtolower($nameSubstring) . "%'";
        }
        $sql .= "order by t.name;";
        $rows    = $this->sqlQuery($sql);
        $results = array();
        foreach ($rows as $row) {
            $results[] = $this->_set($row);
        }
        return $results;
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
     * @param null $dbRowObj
     * @return BusinessService
     */
    private function _set($dbRowObj = null) {
        $this->sysLog->debug();

        $o = new BusinessService();
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
