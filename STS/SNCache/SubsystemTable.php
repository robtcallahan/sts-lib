<?php

namespace STS\SNCache;

use STS\DB\DBTable;

class SubsystemTable extends DBTable
{
    const MULTIPLE_ENTRIES = 69;

    protected static $reverseNameMapping = array();
    protected static $nameMapping = array(
        'sys_id'                         => 'sysId',
        'name'                           => 'name',
        'sys_class_name'                 => 'sysClassName',

        'operational_status'             => 'operationalStatusId',

        'u_business_service'             => 'businessServiceId',
        'u_operations_support_group'     => 'opsSupportGroupId',
        'u_owning_support_manager'       => 'owningSupportManagerId',
        'u_system_admin_group'           => 'sysAdminGroupId',
        'u_systems_administration_manag' => 'sysAdminManagerId',

        "sys_created_by"                 => "sysCreatedBy",
        "sys_created_on"                 => "sysCreatedOn",
        "sys_updated_by"                 => "sysUpdatedBy",
        "sys_updated_on"                 => "sysUpdatedOn",
    );

    protected static $joinTables = array(
        array(
            'table'    => "sys_choice",
            'alias'    => "sc",
            'joinType' => "left",
            'joinTo'   => "value :: Integer",
            'joinFrom' => "operational_status",
            'joinAnd'  => "sc.name = 'u_subsystem' and sc.element = 'operational_status'",
            'where'    => "sc.label = 'Operating'",
            'columns'  => array(
                "label"         => "operationalStatus",
            )
        ),
        array(
            'table'           => "cmdb_ci_service",
            'alias'           => "bs",
            'joinType'        => "left",
            'joinTo'          => "sys_id",
            'joinFrom'        => "u_business_service",
            'joinAnd'         => "bs.operational_status = 1",
            'columns'         => array(
                "name" => "businessService",
            )
        ),
        array(
            'table'    => "sys_user_group",
            'alias'    => "g1",
            'joinType' => "left",
            'joinTo'   => "sys_id",
            'joinFrom' => "u_operations_support_group",
            'columns'  => array(
                "name" => "opsSupportGroup",
            )
        ),
        array(
            'table'    => "sys_user",
            'alias'    => "u1",
            'joinType' => "left",
            'joinTo'   => "sys_id",
            'joinFrom' => "u_owning_support_manager",
            'columns'  => array(
                "u_displayname" => "owningSupportManagerFullName",
                "email"         => "owningSupportManagerEmail",
                "first_name"    => "owningSupportManagerFirstName",
                "last_name"     => "owningSupportManagerLastName",
                "user_name"     => "owningSupportManagerUserName"
            )
        ),
        array(
            'table'    => "sys_user_group",
            'alias'    => "g2",
            'joinType' => "left",
            'joinTo'   => "sys_id",
            'joinFrom' => "u_system_admin_group",
            'columns'  => array(
                "name" => "sysAdminGroup",
            )
        ),
        array(
            'table'    => "sys_user",
            'alias'    => "u2",
            'joinType' => "left",
            'joinTo'   => "sys_id",
            'joinFrom' => "u_systems_administration_manag",
            'columns'  => array(
                "u_displayname" => "sysAdminManagerFullName",
                "email"         => "sysAdminManagerEmail",
                "first_name"    => "sysAdminManagerFirstName",
                "last_name"     => "sysAdminManagerLastName",
                "user_name"     => "sysAdminManagerUserName"
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
        $this->tableName = 'u_subsystem';
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
     * @return Subsystem
     */
    public function getById($sysId) {
        return $this->getBySysId($sysId);
    }


    /**
     * @param   $sysId
     * @return  Subsystem
     */
    public function getBySysId($sysId) {
        $sql = $this->query . "\n and {$this->tableAlias}.sys_id = '" . $sysId . "';";
        $row = $this->sqlQueryRow($sql);
        return $this->_set($row);
    }

    /**
     * @param $name
     * @throws \ErrorException
     * @return Subsystem
     */
    public function getByName($name) {
        $sql = $this->query . "\n and lower({$this->tableAlias}.name) = lower('" . $name . "');";
        #print $sql . "\n\n";
        $row = $this->sqlQueryRow($sql);
        if (count($row) > 1) {
            throw new \ErrorException("More than one record returned", self::MULTIPLE_ENTRIES);
        }
        return $this->_set($row);
    }

    /**
     * @param $name
     * @throws \ErrorException
     * @return Subsystem[]
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
     * @return Subsystem[]
     */
    public function getAll() {
        $sql = $this->query . "\n
        and {$this->tableAlias}.operational_status = 1
        order by name;";
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
     * @return Subsystem
     */
    private function _set($dbRowObj = null) {
        $this->sysLog->debug();

        $o = new Subsystem();
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
