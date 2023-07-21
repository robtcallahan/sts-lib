<?php
/*******************************************************************************
 *
 * $Id: DBTable.php 82037 2013-12-17 19:19:11Z rcallaha $
 * $Date: 2013-12-17 14:19:11 -0500 (Tue, 17 Dec 2013) $
 * $Author: rcallaha $
 * $Revision: 82037 $
 * $HeadURL: https://svn.ultradns.net/svn/sts_tools/sts-lib/trunk/STS/DB/DBTable.php $
 *
 *******************************************************************************
 */

namespace STS\DB;

use STS\Util\SysLog;

class DBTable
{
    protected $dbIndex;
    protected $dbType;
    protected $schemaName = null;
    protected $tableName;
    protected $idAutoIncremented;

    protected $dbDAO;

    protected $sysLog;
    protected $logLevel;

    public function __construct($config = null) {
        if ($config && is_array($config)) {
            // new config method: passing the config into the constructor
            // check for all needed config params

            // appName & logLevel. If missing, assign defaults
            $config['appName']  = array_key_exists('appName', $config) ? $config['appName'] : 'DB\DBTable';
            $config['logLevel'] = array_key_exists('logLevel', $config) ? $config['logLevel'] : SysLog::NOTICE;

            // check for database details
            if (!array_key_exists('dbIndex', $config) || !$config['dbIndex']) throw new \ErrorException("dbIndex not defined in config");
            if (!array_key_exists('tableName', $config) || !$config['tableName']) throw new \ErrorException("tableName not defined in config");
            if (!array_key_exists('idAutoIncremented', $config)) throw new \ErrorException("idAutoIncremented not defined in config");
            $dbIndex = $config['dbIndex'];

            // check for database connection credentials
            if (!array_key_exists('databases', $config)) throw new \ErrorException("databases category not defined in config");
            if (!array_key_exists($dbIndex, $config['databases'])) throw new \ErrorException("hpsim database not defined in config['databases");
            if (!array_key_exists('server', $config['databases'][$dbIndex])) throw new \ErrorException("server not defined in config['databases'][{$dbIndex}]");
            if (!array_key_exists('type', $config['databases'][$dbIndex])) throw new \ErrorException("type not defined in config['databases'][{$dbIndex}]");
            if (!array_key_exists('username', $config['databases'][$dbIndex])) throw new \ErrorException("username not defined in config['databases'][{$dbIndex}]");
            if (!array_key_exists('password', $config['databases'][$dbIndex])) throw new \ErrorException("password not defined in config['databases'][{$dbIndex}]");
            if (!array_key_exists('database', $config['databases'][$dbIndex])) throw new \ErrorException("database not defined in config['databases'][{$dbIndex}]");
        } else {
            $config = array();

            // old method of $GLOBALS or a local config file
            if (array_key_exists('config', $GLOBALS)) {
                $configOld = $GLOBALS['config'];
            } else {
                $configDir  = __DIR__ . "/config";
                $configFile = "config.php";
                if (is_dir($configDir) && is_file($configDir . "/" . $configFile)) {
                    $configOld = require($configDir . "/" . $configFile);
                } else {
                    throw new \ErrorException("Could not find config file: " . $configDir . "/" . $configFile);
                }
            }

            // dbIndex, tableName & idAutoIncremented should be set by the subclass
            if (!property_exists($this, 'dbIndex') || !$this->dbIndex) throw new \ErrorException("dbIndex property not set");
            if (!property_exists($this, 'tableName') || !$this->tableName) throw new \ErrorException("tableName property not set");
            if (!property_exists($this, 'idAutoIncremented')) throw new \ErrorException("idAutoIncremented property not set");
            $dbIndex                     = $this->dbIndex;
            $config['dbIndex']           = $dbIndex;
            $config['tableName']         = $this->tableName;
            $config['idAutoIncremented'] = $this->idAutoIncremented;

            // check for all needed config params
            if (is_object($configOld)) {
                // config is an object - the "old" way of doing things
                $config['appName']  = property_exists($configOld, 'appName') ? $configOld->appName : $dbIndex;
                $config['logLevel'] = property_exists($configOld, 'logLevel') ? $configOld->logLevel : SysLog::NOTICE;

                if (!property_exists($configOld, 'databases')) throw new \ErrorException("databases category not defined in config");
                if (!property_exists($configOld->databases, $dbIndex)) throw new \ErrorException("{$dbIndex} database not defined in config->databases");
                if (!property_exists($configOld->databases->$dbIndex, 'server')) throw new \ErrorException("server not defined in config->databases->{$dbIndex}");
                if (!property_exists($configOld->databases->$dbIndex, 'type')) throw new \ErrorException("type not defined in config->databases->{$dbIndex}");
                if (!property_exists($configOld->databases->$dbIndex, 'username')) throw new \ErrorException("username not defined in config->databases->{$dbIndex}");
                if (!property_exists($configOld->databases->$dbIndex, 'password')) throw new \ErrorException("password not defined in config->databases->{$dbIndex}");
                if (!property_exists($configOld->databases->$dbIndex, 'database')) throw new \ErrorException("database not defined in config->databases->{$dbIndex}");

                $config['databases'] = array(
                    $dbIndex => array(
                        'server'   => $configOld->databases->$dbIndex->server,
                        'type'     => $configOld->databases->$dbIndex->type,
                        'username' => $configOld->databases->$dbIndex->username,
                        'password' => $configOld->databases->$dbIndex->password,
                        'database' => $configOld->databases->$dbIndex->database
                    )
                );
                if (property_exists($configOld->databases->$dbIndex, 'port')) {
                    $config['databases'][$dbIndex]['port'] = $configOld->databases->$dbIndex->port;
                }
                if (property_exists($configOld->databases->$dbIndex, 'schema')) {
                    $config['databases'][$dbIndex]['schema'] = $configOld->databases->$dbIndex->schema;
                    $this->schemaName                        = $configOld->databases->$dbIndex->schema;
                }
            } else {
                // config is an array
                $config['appName']  = array_key_exists('appName', $configOld) ? $configOld['appName'] : $dbIndex;
                $config['logLevel'] = array_key_exists('logLevel', $configOld) ? $configOld['logLevel'] : SysLog::NOTICE;

                if (!array_key_exists('databases', $configOld)) throw new \ErrorException("databases category not defined in config");
                if (!array_key_exists($dbIndex, $configOld['databases'])) throw new \ErrorException("{$dbIndex} database not defined in config['databases']");
                if (!array_key_exists('server', $configOld['databases'][$dbIndex])) throw new \ErrorException("server not defined in config['databases'][{$dbIndex}]");
                if (!array_key_exists('type', $configOld['databases'][$dbIndex])) throw new \ErrorException("type not defined in config['databases'][{$dbIndex}]");
                if (!array_key_exists('username', $configOld['databases'][$dbIndex])) throw new \ErrorException("username not defined in config['databases'][{$dbIndex}]");
                if (!array_key_exists('password', $configOld['databases'][$dbIndex])) throw new \ErrorException("password not defined in config['databases'][{$dbIndex}]");
                if (!array_key_exists('database', $configOld['databases'][$dbIndex])) throw new \ErrorException("database not defined in config['databases'][{$dbIndex}]");

                $config['databases'] = array(
                    $dbIndex => array(
                        'server'   => $configOld['databases'][$dbIndex]['server'],
                        'type'     => $configOld['databases'][$dbIndex]['type'],
                        'username' => $configOld['databases'][$dbIndex]['username'],
                        'password' => $configOld['databases'][$dbIndex]['password'],
                        'database' => $configOld['databases'][$dbIndex]['database']
                    )
                );
                if (array_key_exists('port', $configOld['databases'][$dbIndex])) {
                    $config['databases'][$dbIndex]['port'] = $configOld['databases'][$dbIndex]['port'];
                }
                if (array_key_exists('schema', $configOld['databases'][$dbIndex])) {
                    $config['databases'][$dbIndex]['schema'] = $configOld['databases'][$dbIndex]['schema'];
                    $this->schemaName                        = $configOld['databases'][$dbIndex]['schema'];
                }
            }
        }

        // Set up SysLog
        $this->sysLog   = SysLog::singleton($config['appName']);
        $this->logLevel = $config['logLevel'];
        $this->sysLog->debug();

        // instantiate our database connection
        $this->dbDAO = DBDAO::singleton($config);
    }

    // *******************************************************************************
    // CRUD Methods
    // *******************************************************************************

    /**
     * @param        $o
     * @param string $sql
     * @return mixed
     */
    public function create($o, $sql = "") {
        $this->sysLog->debug();
        return $this->dbDAO->create($o, $sql);
    }

    /**
     * @param $sql
     * @return mixed
     */
    public function sqlQuery($sql) {
        $this->sysLog->debug();
        return $this->dbDAO->sqlQuery($sql);
    }

    /**
     * @param $sql
     * @return mixed
     */
    public function sqlQueryRow($sql) {
        $this->sysLog->debug();
        return $this->dbDAO->sqlQueryRow($sql);
    }

    /**
     * @param $sql
     * @return mixed
     */
    public function sql($sql) {
        $this->sysLog->debug();
        return $this->dbDAO->sql($sql);
    }

    /**
     * @param        $o
     * @param string $idColumn
     * @param string $sql
     * @return mixed
     */
    public function update($o, $idColumn = "id", $sql = "") {
        $this->sysLog->debug();
        return $this->dbDAO->update($o, $idColumn, $sql);
    }

    /**
     * @param        $o
     * @param string $idColumn
     * @param string $sql
     * @return mixed
     */
    public function delete($o, $idColumn = "id", $sql = "") {
        $this->sysLog->debug();
        return $this->dbDAO->delete($o, $idColumn, $sql);
    }

    /**
     * @param $string
     * @return mixed
     */
    public function mysqlEscapeString($string) {
        return $this->dbDAO->mysqlEscapeString($string);
    }

    // *******************************************************************************
    // Getters and Setters
    // *******************************************************************************

    /**
     * @return mixed
     */
    public function getNumberTypes() {
        return $this->dbDAO->getNumberTypes();
    }

    /**
     * @return mixed
     */
    public function getDbType() {
        return $this->dbDAO->getDbType();
    }

    /**
     * @return mixed
     */
    public function getColumns() {
        return $this->dbDAO->getColumns();
    }

    /**
     * @return mixed
     */
    public function getQueryColumns() {
        return $this->dbDAO->getQueryColumns();
    }

    /**
     * @return mixed
     */
    public function getInsertColumns() {
        return $this->dbDAO->getInsertColumns();
    }

    /**
     * @return mixed
     */
    public function getQueryColumnsStr() {
        return $this->dbDAO->getQueryColumnsStr();
    }

    /**
     * @return mixed
     */
    public function getInsertColumnsStr() {
        return $this->dbDAO->getInsertColumnsStr();
    }

    /**
     * @return mixed
     */
    public function getColumnInfoArray() {
        return $this->dbDAO->getColumnInfoArray();
    }

    /**
     * @return mixed
     */
    public function getColumnInfoHash() {
        return $this->dbDAO->getColumnInfoHash();
    }

    /**
     * @param $logLevel
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
     * @param $sysLog
     */
    public function setSysLog($sysLog) {
        $this->sysLog = $sysLog;
    }

    /**
     * @return SysLog
     */
    public function getSysLog() {
        return $this->sysLog;
    }
}
