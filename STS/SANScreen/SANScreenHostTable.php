<?php
/*******************************************************************************
 *
 * $Id: SANScreenHostTable.php 82460 2014-01-04 15:38:17Z rcallaha $
 * $Date: 2014-01-04 10:38:17 -0500 (Sat, 04 Jan 2014) $
 * $Author: rcallaha $
 * $Revision: 82460 $
 * $HeadURL: https://svn.ultradns.net/svn/sts_tools/sts-lib/trunk/STS/SANScreen/SANScreenHostTable.php $
 *
 *******************************************************************************
 */

namespace STS\SANScreen;

use STS\DB\DBTable;
use STS\CMDB\CMDBServer;
use STS\CMDB\CMDBSubsystem;

class SANScreenHostTable extends DBTable
{
    protected $dbIndex = 'sanscreen';
   	protected $tableName = 'host';
    protected $idAutoIncremented = false;

	private static $columnNames = array(
		"id", "name", "objectType", "ip", "dead", "startTime", "endTime",
		"sysId", "cmdbName", "modelNumber", "environment", "cmInstallStatus",
		"businessService", "subsystem", "opsSuppMgr", "opsSuppGrp"
	);

	// these are the CMDB keys mapped to local db column names
	protected static $cmdbServerColumns = array(
		"sysId", "name", "modelNumber", "environment", "installStatus", "businessService", "subsystemList"
	);

	protected static $ssServerColumns = array(
		"sysId", "cmdbName", "modelNumber", "environment", "cmInstallStatus", "businessService", "subsystem"
	);

	protected static $cmdbSubsysColumns = array("owningSupportManager", "operationsSupportGroup");

	protected static $ssSubsysColumns = array("opsSuppMgr", "opsSuppGrp");

	public function __construct($config = null)
	{
        if ($config) {
             // need to add these to the config since won't be in the config file
             $config['tableName'] = $this->tableName;
             $config['dbIndex'] = $this->dbIndex;
             $config['idAutoIncremented'] = $this->idAutoIncremented;
         }
         parent::__construct($config);
 		$this->sysLog->debug();
	}

	/**
	 * @param $id
	 * @return SANScreenHost
	 */
	public function getById($id)
	{
		$this->sysLog->debug("id=" . $id);
		$sql = "select {$this->getQueryColumnsStr()}
                from   {$this->tableName}
		        where  id = {$id};";
		$row = $this->sqlQueryRow($sql);
		return $this->_set($row);
	}

	/**
	 * @param $name
	 * @return SANScreenHost
	 */
	public function getByName($name)
	{
		$this->sysLog->debug("name=" . $name);
		$sql = "select {$this->getQueryColumnsStr()}
                from   {$this->tableName}
		        where  name = '{$name}';";
		$row = $this->sqlQueryRow($sql);
		return $this->_set($row);
	}

	/**
	 * @param $arrayId
	 * @return SANScreenHost[]
	 */
	public function getByArrayId($arrayId)
	{
		$this->sysLog->debug("arrayId=" . $arrayId);
		$sql   = "select distinct h.*
                from   array a,
                       path p,
                       host h
		        where  arrayId = {$arrayId}
		          and  p.arrayId = a.id
		          and  h.id = p.hostId
		        order by h.name asc;";
		$rows  = $this->sqlQuery($sql);
		$array = array();
		for ($i = 0; $i < count($rows); $i++) {
			$array[] = $this->_set($rows[$i]);
		}
		return $array;
	}

	/**
	 * @return SANScreenHost[]
	 */
	public function getAll()
	{
		$this->sysLog->debug();
		$sql   = "select {$this->getQueryColumnsStr()}
          		from   {$this->tableName}
    	  		order by name;";
		$rows  = $this->sqlQuery($sql);
		$hosts = array();
		for ($i = 0; $i < count($rows); $i++) {
			$hosts[] = $this->_set($rows[$i]);
		}
		return $hosts;
	}

	/**
	 * @param SANScreenHost $h
	 * @param CMDBServer    $s
	 * @return SANScreenHost
	 */
	public function mergeCmdbServer(SANScreenHost $h, CMDBServer $s)
	{
		for ($i = 0; $i < count(self::$cmdbServerColumns); $i++) {
			$h->set(self::$ssServerColumns[$i], $s->get(self::$cmdbServerColumns[$i]));
		}
		return $h;
	}

	/**
	 * @param SANScreenHost $h
	 * @param CMDBSubsystem $s
	 * @return SANScreenHost
	 */
	public function mergeCmdbSubsystem(SANScreenHost $h, CMDBSubsystem $s)
	{
		for ($i = 0; $i < count(self::$cmdbSubsysColumns); $i++) {
			$h->set(self::$ssSubsysColumns[$i], $s->get(self::$cmdbSubsysColumns[$i]));
		}
		return $h;
	}

	// *******************************************************************************
	// CRUD methods
	// *******************************************************************************

	/**
	 * @param SANScreenHost $o
     * @param string $sql
	 * @return SANScreenHost
	 */
    public function create($o, $sql = "") {
   		$this->sysLog->debug();
   		$newId = parent::create($o, $sql);
   		return $this->getById($newId);
   	}

	/**
	 * @param SANScreenHost $o
     * @param string $idColumn
     * @param string $sql
	 * @return mixed
	 */
    public function update($o, $idColumn = "id", $sql = "")
   	{
   		$this->sysLog->debug();
        return parent::update($o, $idColumn, $sql);
   	}

	/**
	 * @param SANScreenHost $o
     * @param string $idColumn
     * @param string $sql
	 * @return mixed
	 */
    public function delete($o, $idColumn = "id", $sql = "")
   	{
   		$this->sysLog->debug();
        return parent::delete($o, $idColumn, $sql);
   	}

	// *******************************************************************************
	// Getters and Setters
	// *******************************************************************************

	/**
	 * @param $logLevel
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
	 * @param $columnNames
	 */
	public static function setColumnNames($columnNames)
	{
		self::$columnNames = $columnNames;
	}

	/**
	 * @return array
	 */
	public static function getColumnNames()
	{
		return self::$columnNames;
	}

	/**
	 * @param null $dbRowObj
	 * @return SANScreenHost
	 */
	private function _set($dbRowObj = null)
	{
		$this->sysLog->debug();

		$o = new SANScreenHost();
		if ($dbRowObj) {
			foreach (self::$columnNames as $prop) {
				$o->set($prop, $dbRowObj->$prop);
			}
		}
		else {
			foreach (self::$columnNames as $prop) {
				$o->set($prop, null);
			}
		}
		return $o;
	}
}
