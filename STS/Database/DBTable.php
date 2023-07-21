<?php
/*******************************************************************************
 *
 * $Id: DBTable.php 74931 2013-05-03 19:21:04Z rcallaha $
 * $Date: 2013-05-03 15:21:04 -0400 (Fri, 03 May 2013) $
 * $Author: rcallaha $
 * $Revision: 74931 $
 * $HeadURL: https://svn.ultradns.net/svn/sts_tools/sts-lib/trunk/STS/DB/DBTable.php $
 *
 *******************************************************************************
 */

namespace STS\Database;

use STS\Util\SysLog;
use Zend\Db\Adapter\Adapter;
use STS\Util\Obfuscation;

class DBTable
{
    /**
     * Zend DB Adapter instance
     * @var Adapter
     */
    protected $adapter;

    /**
     * The database table name
     * @var
     */
    protected $tableName;

    /**
     * The alias that will be assigned to the table
     * @var string
     */
    protected $tableAlias = 't';

    /**
     * The column name of the primary key
     * @var string
     */
    protected $primaryKey = "";

    /**
     * Denotes the primary key as being auto incrementing
     * @var bool
     */
    protected $autoIncrement = false;

    /**
     * Instantiationg of the SysLog class
     * @var SysLog
     */
    protected $sysLog;

    /**
     * The name that will be used in Syslog messages
     * @var
     */
    protected $sysLogName = "DBTable";

    /**
     * The Syslog log level
     * @var
     */
    protected $logLevel = SysLog::NOTICE;

    /**
     * An hash of arrays the defines the columns of the table.
     * The definition is in the subclass, eg., UserTable
     * @var mixed
     */
    protected $columns;

    /**
     * Same as $columns but without the primary key column, id by default, for use in create method
     * @var array
     */
    protected $insertColumns = array();

    /**
     * An array of table column names
     * @var array
     */
    protected $columnNames = array();

    /**
     * A comma delimited string of all table columns as a convenience for sql queries
     * created using the $columns structure
     * @var string
     */
    protected $queryColumnsStr = "";

    /**
     * A regexp of numeric columns. If the column type doesn't match any of these, then it needs
     * to have quotes in the sql string
     * @var string
     */
    protected $numberTypes = "/decimal|double|float|int|numeric/";

    /**
     * Initialize syslog
     * Define columnNames, primaryKey, autoIncrement, insertColumns, queryColumnsStr
     * Instantiate MySqlDB and connect()
     *
     * @param mixed $config
     *
     * $config = array(
     *     "username"    => "stsuser",
     *     "password"    => "PyQx4p`KFxM_O3@$",
     *     "database"    => "dbtest",
     *  ),
     */
    public function __construct($config)
    {
        // Set up SysLog
        $this->sysLog = SysLog::singleton($this->sysLogName);
        $this->sysLog->debug();

        $this->adapter = $this->getConnection($config);
        $this->_getColumnMetadata();
    }

    /**
     * @param mixed $config
     * @param $config
     * @throws \ErrorException
     * @return Adapter
     */
    protected function getConnection($config)
    {
        // appName & logLevel. If missing, assign defaults
        $config['appName'] = array_key_exists('appName', $config) ? $config['appName'] : 'DB\DBTable';
        $config['logLevel'] = array_key_exists('logLevel', $config) ? $config['logLevel'] : SysLog::NOTICE;

        // check for database details
        if (!array_key_exists('dbIndex', $config) || !$config['dbIndex']) throw new \ErrorException("dbIndex not defined in config");
        $dbIndex = $config['dbIndex'];
        if (!array_key_exists('tableName', $config) || !$config['tableName']) throw new \ErrorException("tableName not defined in config");
        $this->tableName = $config['tableName'];
        if (!array_key_exists('idAutoIncremented', $config)) throw new \ErrorException("idAutoIncremented not defined in config");

        // check for database connection credentials
        if (!array_key_exists('databases', $config)) throw new \ErrorException("databases category not defined in config");
        if (!array_key_exists($dbIndex, $config['databases'])) throw new \ErrorException("hpsim database not defined in config['databases");
        if (!array_key_exists('server', $config['databases'][$dbIndex])) throw new \ErrorException("server not defined in config['databases'][{$dbIndex}]");
        if (!array_key_exists('type', $config['databases'][$dbIndex])) throw new \ErrorException("type not defined in config['databases'][{$dbIndex}]");
        if (!array_key_exists('username', $config['databases'][$dbIndex])) throw new \ErrorException("username not defined in config['databases'][{$dbIndex}]");
        if (!array_key_exists('password', $config['databases'][$dbIndex])) throw new \ErrorException("password not defined in config['databases'][{$dbIndex}]");
        if (!array_key_exists('database', $config['databases'][$dbIndex])) throw new \ErrorException("database not defined in config['databases'][{$dbIndex}]");

        $crypt = new Obfuscation();

        $adapter = new Adapter(array(
            'driver'   => 'Mysqli',
            'hostname' => $config['databases'][$dbIndex]['server'],
            'database' => $config['databases'][$dbIndex]['database'],
            'username' => $config['databases'][$dbIndex]['username'],
            'password' => $crypt->decrypt($config['databases'][$dbIndex]['password'])
         ));
        return $adapter;
    }

    /**
     *
     */
    private function _getColumnMetadata()
    {
        $sql = "SHOW COLUMNS FROM {$this->tableName};";
        $results = $this->adapter->query($sql, Adapter::QUERY_MODE_EXECUTE);

        #$row = $results->current();
        #print $row['lastName'] . "\n";
        #$rows = $this->db->getAllObjects($sql);

        foreach ($results as $row) {
            $column = array();

            $column['name'] = $row['Field'];
            $column['nullable'] = $row['Null'] == "NO" ? false : true;

            if (preg_match("/(\w+)\((\d+),?(\d+)?\)/", $row['Type'], $m)) {
                $column['type']      = $m[1];
                $column['length']    = $m[2];
                if (array_key_exists(3, $m)) {
                    $column['length'] = $m[2];
                    $column['precision'] = $m[2];
                    $column['scale'] = $m[3];
                }
            } else {
                $column['type']      = $row['Type'];
                $column['length']    = null;
            }

            if ($row['Extra'] && $row['Extra'] == "auto_increment") {
                $column['autoIncrement'] = true;
                $this->autoIncrement = true;
            }

            if ($row['Key'] && $row['Key'] == "PRI") {
                $column['primaryKey'] = true;
                $this->primaryKey = $row->Field;
            } else {
                $this->insertColumns[$column['name']] = $column;
            }

            $this->columns[$column['name']] = $column;
            #$this->columnNames[] = $column['name'];
        }

        // create the query columns string from the column names
        $this->queryColumnsStr = "";
        foreach ($this->columnNames as $cName) {
            $this->queryColumnsStr .= $cName . ",";
        }
        $this->queryColumnsStr = trim($this->queryColumnsStr, ',');
    }

    // *******************************************************************************
    // CRUD Methods
    // *******************************************************************************

    /**
     * @param object $o
     * @param string $sql
     * @return mixed
     */
    public function create($o, $sql = "")
    {
        $this->sysLog->debug();

        if ($sql == "") {
            $sql = "insert into " . $this->tableName . "(" . implode(',', array_keys($this->insertColumns)) . ") values(";

            $valuesArray = array();
            foreach ($this->insertColumns as $col) {
                $value = $o->get($col['name']);
                if ($value == "" || $value == null) {
                    if ($col['nullable']) {
                        $valuesArray[] = "null";
                    } else if ($col['type'] == "timestamp" || $col['type'] == "datetime") {
                        $valuesArray[] = "'0000-00-00 00:00:00'";
                    } else if ($col['type'] == "date") {
                        $valuesArray[] = "'0000-00-00'";
                    } else if ($col['type'] == "time") {
                        $valuesArray[] = "'00:00:00'";
                    } else {
                        $valuesArray[] = "''";
                    }
                } else {
                    $quote = preg_match($this->numberTypes, $col['type']) ? "" : "'";
                    if ($quote) {
                        $value = addslashes($value);
                    }
                    $valuesArray[] = "{$quote}{$value}{$quote}";
                }
            }
            $sql .= implode(",", $valuesArray) . ");";
        }
        $this->adapter->query($sql, Adapter::QUERY_MODE_EXECUTE);
        if ($this->autoIncrement) {
            $newId = $this->adapter->getDriver()->getLastGeneratedValue();
        } else {
            $newId = 0;
        }
        return $newId;
    }

    /**
   	 * Perform a custom SQL query to retrive data in object form from the database.
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

        $results = $this->adapter->query($sql, Adapter::QUERY_MODE_EXECUTE);
   		return $results->toArray();
   	}

    /**
   	 * Perform a custom SQL query to retrive one row in object form from the database.
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

        $results = $this->adapter->query($sql, Adapter::QUERY_MODE_EXECUTE);
   		$row = $results->current();
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

        $this->adapter->query($sql, Adapter::QUERY_MODE_EXECUTE);
   	}

    /**
   	 * Update a row in the table using the passed object or custom SQL
   	 *
   	 * @param object     $o        instantiated class to use for the update
   	 * @param int|string $idColumn column name to use in the where clause for the udpate
   	 * @param string     $sql      [optional] custom sql to perform the insert
   	 * @return object the newly-inserted rows
   	 */
   	public function update($o, $idColumn = "id", $sql = "")
   	{
   		$this->sysLog->debug();

        $idColumn = $this->primaryKey ? $this->primaryKey : $idColumn;

        if (count($o->getChanges()) == 0) {
            return $o;
        }

   		if ($sql == "") {
   			$sql = "update " . $this->tableName . " set ";

   			$valuesArray = array();
            foreach ($o->getChanges() as $prop => $chgObj) {
   				$value     = $o->get($prop);
   				$quote     = preg_match($this->numberTypes, $this->columns[$prop]['type']) ? "" : "'";
   				if ($quote) {
   					$value = addslashes($value);
   				}
   				if ($value == null) {
   					$quote = "";
   					$value = "NULL";
   				}
   				$valuesArray[] = "{$prop} = {$quote}{$value}{$quote}";
   			}
            $type = $this->columns[$idColumn]['type'];
   			$quote = preg_match($this->numberTypes, $type) ? "" : "'";
   			$sql .= implode(",", $valuesArray) . " where  $idColumn = {$quote}{$o->get($idColumn)}{$quote}";
   		}
        $o->clearChanges();
        $this->adapter->query($sql, Adapter::QUERY_MODE_EXECUTE);
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

        $idColumn = $this->primaryKey ? $this->primaryKey : $idColumn;

   		if ($sql == "") {
            $type = $this->columns[$idColumn]['type'];
   			$quote = preg_match($this->numberTypes, $type) ? "" : "'";
   			$sql       = "delete from " . $this->tableName . " where " . $idColumn . " = " . $quote . $o->get($idColumn) . $quote . ";";
   		}
        $this->adapter->query($sql, Adapter::QUERY_MODE_EXECUTE);
   		return $o;
   	}


    // *******************************************************************************
    // Getters and Setters
    // *******************************************************************************

    /**
     * @return array
     */
    public function getColumnNames()
    {
        return $this->columnNames;
    }

    /**
     * @return string
     */
    public function getQueryColumnsStr()
    {
        return $this->queryColumnsStr;
    }

    /**
     * @return string
     */
    public function getTableAlias()
    {
        return $this->tableAlias;
    }

    /**
     * @return array
     */
    public  function getColumns()
    {
        return $this->columns;
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
     * @return int
     */
    public function getLogLevel()
    {
        return $this->logLevel;
    }


    /**
     * @return string
     */
    public function getNumberTypes()
    {
        return $this->numberTypes;
    }

    /**
     * @return array
     */
    public function getInsertColumns()
    {
        return $this->insertColumns;
    }

    /**
     * @return SysLog
     */
    public function getSysLog()
    {
        return $this->sysLog;
    }

    /**
     * @return boolean
     */
    public function getAutoIncrement()
    {
        return $this->autoIncrement;
    }

    /**
     * @return null
     */
    public function getPrimaryKey()
    {
        return $this->primaryKey;
    }

    /**
     * @param mixed $sysLogName
     */
    public function setSysLogName($sysLogName)
    {
        $this->sysLogName = $sysLogName;
    }

    /**
     * @return mixed
     */
    public function getSysLogName()
    {
        return $this->sysLogName;
    }

    /**
     * @return mixed
     */
    public function getTableName()
    {
        return $this->tableName;
    }

    /**
     * @param mixed $tableName
     */
    public function setTableName($tableName)
    {
        $this->tableName = $tableName;
    }

    /**
     * @return Adapter
     */
    public function getAdapter()
    {
        return $this->adapter;
    }

}
