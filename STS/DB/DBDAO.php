<?php
/*******************************************************************************
 *
 * $Id: DBDAO.php 81814 2013-12-09 19:40:39Z rcallaha $
 * $Date: 2013-12-09 14:40:39 -0500 (Mon, 09 Dec 2013) $
 * $Author: rcallaha $
 * $Revision: 81814 $
 * $HeadURL: https://svn.ultradns.net/svn/sts_tools/sts-lib/trunk/STS/DB/DBDAO.php $
 *
 *******************************************************************************
 */

namespace STS\DB;

use STS\Util\SysLog;

class DBDAO
{
    // Hold an instance of the class
    private static $instances = array();
    private static $keys = array();

    protected $config;

    protected $dbType;
    protected $db;
    protected $idAutoIncremented; // true means that id column is not updated on create since it's auto incremented (default)
    // false to include id column on insert

    protected $tableName;

    protected $columns = null; // array of db table columns
    protected $queryColumns = null; // same as columns
    protected $insertColumns = null; // same as columns, but without the "id" column since you don't know this on an insert
    protected $queryColumnsStr = null; // query columns in string form, exploded with ", " between column names
    protected $insertColumnsStr = null; // insert columns in string form, exploded with ", " between column names
    protected $columnInfoHash = null; // hash of columns by name, each value contains: name, type, length, precision & nullable flag
    protected $columnInfoArray = null; // array of columns, each value contains: name, type, length, precision & nullable flag

    protected $numberTypes = "/int|float|numeric|decimal/";

    protected $sysLog;
    protected $logLevel = SysLog::NOTICE;

    /**
     * Construct function which requires the dbIndex from the config file and the dbTable
     *
     * @param $config
     * @throws \ErrorException
     * @return \STS\DB\DBDAO
     */
    public function __construct($config)
    {
        $dbIndex = $config['dbIndex'];

        // Set up SysLog
        $this->sysLog   = SysLog::singleton($config['appName']);
        $this->logLevel = $config['logLevel'];
        $this->sysLog->debug("dbIndex=" . $config['dbIndex'] . ", tableName=" . $config['tableName'] . ", idAutoincremented=" . $config['idAutoIncremented']);

        if ($config['dbIndex'] == null) {
            throw new \ErrorException("dbIndex not specified in DBDAO construct");
        }

        $dbConfig = $config['databases'][$dbIndex];
        $this->dbType = $dbConfig['type'];
        $this->sysLog->debug("dbServer=" . $dbConfig['server']);

        if ($this->dbType == "mysql") {
            $this->db = new MySqlDB($config);
        } else if ($this->dbType == "postgres") {
            $this->db = new PostgresDB($config);
        } else if ($this->dbType == "oracle") {
            $this->db = new OracleDB($config);
        } else {
            throw new \ErrorException("Unknown DB type: " . $this->dbType);
        }

        $this->idAutoIncremented = $config['idAutoIncremented'];

        $this->tableName = $config['tableName'];

        $this->getColumnInfo();
    }

    /**
     * @param $config
     * @return mixed
     */
    public static function singleton($config)
    {
        if ($config['tableName'] && $config['dbIndex']) {
            $key = $config['dbIndex'] . "" . $config['tableName'];
        } else {
            $key          = count(self::$keys);
            self::$keys[] = $key;
        }
        if (!array_key_exists($key, self::$instances)) {
            $instance = new DBDAO($config);
            self::$instances[$key] = $instance;
        }
        return self::$instances[$key];
    }

    /**
     * @throws \ErrorException
     */
    public function getColumnInfo()
    {
        $this->sysLog->debug();

        if ($this->dbType == "mysql") {
            $sql = "SHOW COLUMNS FROM {$this->tableName};";
        }
        else if ($this->dbType == "postgres") {
            $sql = "select column_name as name,
			               data_type as type,
			               character_maximum_length AS length,
			               numeric_precision AS \"precision\",
			               is_nullable as nullable
			        from   information_schema.columns 
			        where  table_name = '" . $this->tableName . "'";
        }
        else if ($this->dbType == "oracle") {
            $tName = strtoupper($this->tableName);
            if ($tName == "REPOSITORY") {
                $tName = "REPOSITORY_VIEW_NOPASS";
            }

            $sql = "SELECT column_name as \"name\",
			               data_type as \"type\", 
			               data_length as \"length\", 
			               data_precision as \"precision\", 
			               nullable as \"nullable\"
		            FROM   all_tab_columns
		            WHERE  table_name = '" . $tName . "'";
        }
        else {
            throw new \ErrorException("Don't know how to get column info for {$this->dbType}");
        }

        $this->db->connect();
        $rows = $this->db->getAllObjects($sql);
        //$this->db->close();

        $cols       = array();
        $queryCols  = array();
        $insertCols = array();
        $infoHash   = array();
        $infoArray  = array();

        if ($rows) {
            for ($i = 0; $i < count($rows); $i++) {
                $r = $rows[$i];
                $o = (object)array();

                if ($this->dbType == "mysql") {
                    $o->name     = $r->Field;
                    $o->nullable = $r->Null == "NO" ? false : true;
                    if (preg_match("/(\w+)\((\d+)\)/", $r->Type, $m)) {
                        $o->type      = $m[1];
                        $o->length    = $m[2];
                        $o->precision = null;
                    } else {
                        $o->type      = $r->Type;
                        $o->length    = null;
                        $o->precision = null;
                    }
                } else if ($this->dbType == "postgres") {
                    $o->name      = $r->name;
                    $o->nullable  = $r->nullable == "NO" ? false : true;
                    $o->type      = $r->type;
                    $o->length    = $r->length;
                    $o->precision = $r->precision;
                } else if ($this->dbType == "oracle") {
                    $o->name      = strtolower($r->name);
                    $o->nullable  = $r->nullable == "Y" ? true : false;
                    $o->type      = $r->type;
                    $o->length    = $r->length;
                    $o->precision = $r->precision;
                }
                $cols[]      = $o->name;
                $queryCols[] = $o->name;
                if ($o->name != "id") {
                    $insertCols[] = $o->name;
                } else if ($o->name == "id" && !$this->idAutoIncremented) {
                    $insertCols[] = $o->name;
                }
                $infoArray[]        = $o;
                $infoHash[$o->name] = $o;
            }
        }
        $this->columns          = $cols;
        $this->queryColumns     = $queryCols;
        $this->insertColumns    = $insertCols;
        $this->queryColumnsStr  = implode(', ', $this->queryColumns);
        $this->insertColumnsStr = implode(', ', $this->insertColumns);
        $this->columnInfoHash   = $infoHash;
        $this->columnInfoArray  = $infoArray;

        // if mysql, put `` around each column name in case we run across a key word
        if ($this->dbType == "mysql") {
            $cols = array();
            foreach ($queryCols as $c) {
                $cols[] = '`' . $c . '`';
            }
            $this->queryColumnsStr = implode(', ', $cols);

            $cols = array();
            foreach ($insertCols as $c) {
                $cols[] = '`' . $c . '`';
            }
            $this->insertColumnsStr = implode(', ', $cols);
        }
    }

    /**
     * Create a new rows in the table using the passed object or custom sql
     *
     * @param object $o   [optional] instantiated class to use for the insert
     * @param string $sql [optional] custom sql to perform the insert
     * @internal param string $retrieveWhere where clause to retrieve the newly-inserted row
     * @return int
     */
    public function create($o, $sql = "")
    {
        $this->sysLog->debug();

        $this->db->connect();

        if ($sql == "") {
            $sql = "insert into {$this->tableName}
		            (" . $this->insertColumnsStr . ")
		            values(";

            $valuesArray = array();
            for ($i = 0; $i < count($this->insertColumns); $i++) {
                $name      = $this->insertColumns[$i];
                $h         = $this->columnInfoHash[$name];
                $getMethod = "get" . ucfirst($name);
                if ($o->$getMethod() === "" || $o->$getMethod() === null) {
                    if ($h->nullable) {
                        $valuesArray[] = "null";
                    } else if ($h->type == "timestamp") {
                        $valuesArray[] = "0000-00-00 00:00:00";
                    } else {
                        $valuesArray[] = "''";
                    }
                } else {
                    $quote = preg_match($this->numberTypes, $h->type) ? "" : "'";
                    $value = $o->$getMethod();
                    if ($quote) {
                        $value = $this->db->escape_string($value);
                    }
                    $valuesArray[] = "{$quote}{$value}{$quote}";
                }
            }
            $sql .= implode(",", $valuesArray) . ");";
        }

        $this->db->query($sql);
        if ($this->idAutoIncremented && $this->dbType == 'mysql') {
            $newId = $this->db->getInsertId();
        } else {
            $newId = 0;
        }
        //$this->db->close();
        return $newId;
    }

    /**
     * Perform a custom SQL query to retrive data from the database. No objects are instantiated from the results
     *
     * @param string $sql custom sql to perform the query
     * @throws \ErrorException
     * @return array an array of objects created from the resulting rows
     */
    public function sqlQuery($sql = "")
    {
        $this->sysLog->debug();

        if ($sql == "") {
            throw new \ErrorException("No SQL statement passed as argument");
        }

        $this->db->connect();
        $rows = $this->db->getAllObjects($sql);
        //$this->db->close();
        return $rows;
    }

    /**
     * Perform a custom SQL query to retrive one row from the database. No object is instantiated from the results
     *
     * @param string $sql custom sql to perform the query
     * @throws \ErrorException
     * @return object an SQL query object
     */
    public function sqlQueryRow($sql = "")
    {
        $this->sysLog->debug();

        if ($sql == "") {
            throw new \ErrorException("No SQL statement passed as argument");
        }

        $this->db->connect();
        $row = $this->db->getObject($sql);
        //$this->db->close();
        return $row;
    }

    /**
     * Generic SQL statement where no results are expected, eg., an insert or alter statement
     *
     * @param string $sql
     * @throws \ErrorException
     * @return void
     */
    public function sql($sql = "")
    {
        $this->sysLog->debug();

        if ($sql == "") {
            throw new \ErrorException("No SQL statement passed as argument");
        }

        $this->db->connect();
        $this->db->query($sql);
        //$this->db->close();
    }

    /**
     * Update a row in the table using the passed object or custom SQL
     *
     * @param object $o        instantiated class to use for the update
     * @param int|string $idColumn column name to use in the where clause for the udpate
     * @param string $sql      [optional] custom sql to perform the insert
     * @return object the newly-inserted rows
     */
    public function update($o, $idColumn = "id", $sql = "")
    {
        $this->sysLog->debug();

        $this->db->connect();

        if ($sql == "") {
            $sql = "update " . $this->tableName . " set ";

            $valuesArray = array();
            foreach ($this->columnInfoHash as $name => $h) {
                $getMethod = "get" . ucfirst($name);
                $value     = $o->$getMethod();
                $quote     = preg_match($this->numberTypes, $h->type) ? "" : "'";
                if ($quote) {
                    $value = $this->db->escape_string($value);
                }
                if ($value == null) {
                    $quote = "";
                    $value = "NULL";
                }
                if ($this->dbType == "mysql") {
                    $valuesArray[] = "`{$name}` = {$quote}{$value}{$quote}";
                } else {
                    $valuesArray[] = "{$name} = {$quote}{$value}{$quote}";
                }
            }
            $h         = $this->columnInfoHash[$idColumn];
            $quote     = preg_match($this->numberTypes, $h->type) ? "" : "'";
            $getMethod = "get" . ucfirst($idColumn);
            $sql .= implode(",", $valuesArray) . " where  $idColumn = {$quote}{$o->$getMethod()}{$quote}";
        }

        $this->db->query($sql);
        //$this->db->close();
        return $o;
    }

    /**
     * Delete a row from the time using the pass object and optional id column or using a custom SQL query
     *
     * @param object $o        the object to be deleted
     * @param string $idColumn [optional] an id column to use in the delete query for the where clause
     * @param string $sql      [optional] custom SQL to use rather then the default
     * @return object $o the same object that was passed in
     */
    public function delete($o, $idColumn = "id", $sql = "")
    {
        $this->sysLog->debug();

        if ($sql == "") {
            $h         = $this->columnInfoHash[$idColumn];
            $quote     = preg_match($this->numberTypes, $h->type) ? "" : "'";
            $getMethod = "get" . ucfirst($idColumn);
            $sql       = "delete from" . " " . $this->tableName . " where " . $idColumn . " = " . $quote . $o->$getMethod() . $quote . ";";
        }
        $this->db->connect();
        $this->db->query($sql);
        //$this->db->close();
        return $o;
    }

    /**
     * @param $stringIn
     * @return string
     */
    public function mysqlEscapeString($stringIn)
    {
        $this->db->connect();
        $stringOut = $this->db->escape_string($stringIn);
        //$this->db->close();
        return $stringOut;
    }

    // *******************************************************************************
    // Getters and Setters
    // *******************************************************************************

    /**
     * @return mixed
     */
    public function getDbType()
    {
        return $this->dbType;
    }

    /**
     * @return string
     */
    public function getNumberTypes()
    {
        return $this->numberTypes;
    }

    /**
     * @return null
     */
    public function getColumns()
    {
        return $this->columns;
    }

    /**
     * @return null
     */
    public function getQueryColumns()
    {
        return $this->queryColumns;
    }

    /**
     * @return null
     */
    public function getInsertColumns()
    {
        return $this->insertColumns;
    }

    /**
     * @return null
     */
    public function getQueryColumnsStr()
    {
        return $this->queryColumnsStr;
    }

    /**
     * @return null
     */
    public function getInsertColumnsStr()
    {
        return $this->insertColumnsStr;
    }

    /**
     * @return null
     */
    public function getColumnInfoHash()
    {
        return $this->columnInfoHash;
    }

    /**
     * @return null
     */
    public function getColumnInfoArray()
    {
        return $this->columnInfoArray;
    }
}
