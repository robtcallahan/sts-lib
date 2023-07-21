<?php
/*******************************************************************************
 *
 * $Id: SysDirVirtualTable.php 73467 2013-03-21 11:29:38Z rcallaha $
 * $Date: 2013-03-21 07:29:38 -0400 (Thu, 21 Mar 2013) $
 * $Author: rcallaha $
 * $Revision: 73467 $
 * $HeadURL: https://svn.ultradns.net/svn/sts_tools/sts-lib/trunk/STS/SANScreen/SysDirVirtualTable.php $
 *
 *******************************************************************************
 */

namespace STS\SystemsDirector;

use STS\DB\DBTable;
use STS\SystemsDirector\SysDirVirtual;

use STS\CMDB\CMDBServer;
use STS\CMDB\CMDBSubsystem;

class SysDirVirtualTable extends DBTable
{
	protected static $columnNames = array(
		"vio",
		"memTotal",
		"cpuManufacturer", "cpuMaxClockSpeed", "cpuFamily", "cpuCount", "cpuEnabledCores",
		"operatingSystem", "osVersion", "osBuildNumber",
		"powerPathVersion"
	);
	
	// these are the CMDB keys mapped to local db column names
	protected static $cmdbServerColumns = array(
		"sysId", "name", "environment", "installStatus",
		"businessService", "subsystemList",
		"comments", "shortDescription"
	);

	protected static $sysDirServerColumns = array(
		"sysId", "cmdbName", "environment", "cmInstallStatus",
		"businessService", "subsystem",
		"comments", "shortDescr"
	);

	protected static $cmdbSubsysColumns = array("owningSupportManager", "operationsSupportGroup");

	protected static $sysDirSubsysColumns = array("opsSuppMgr", "opsSuppGrp");


	public function __construct($idAutoIncremented=false)
	{
		$this->dbIndex = "systems_director";
		$this->tableName = "virtual";
		parent::__construct($idAutoIncremented);

		$this->sysLog->debug();
	}

	/**
	 * @param SysDirVirtual $c
	 * @param CMDBServer    $s
	 * @return SysDirVirtual
	 */
	public static function mergeCmdbServer(SysDirVirtual $c, CMDBServer $s)
	{
		for ($i = 0; $i < count(self::$cmdbServerColumns); $i++) {
			$c->set(self::$sysDirServerColumns[$i], $s->get(self::$cmdbServerColumns[$i]));
		}
		return $c;
	}

	/**
	 * @param SysDirVirtual $c
	 * @param CMDBSubsystem $s
	 * @return SysDirVirtual
	 */
	public static function mergeCmdbSubsystem(SysDirVirtual $c, CMDBSubsystem $s)
	{
		for ($i = 0; $i < count(self::$cmdbSubsysColumns); $i++) {
			$c->set(self::$sysDirSubsysColumns[$i], $s->get(self::$cmdbSubsysColumns[$i]));
		}
		return $c;
	}

	/**
	 * @param $id
	 * @return SysDirVirtual
	 */
	public function getById($id) {
		return $this->getByOid($id);
	}

	/**
	 * @param $oid
	 * @return SysDirVirtual
	 */
	public function getByOid($oid)
	{
		$this->sysLog->debug("oid=" . $oid);
        $sql = "select {$this->getQueryColumnsStr()}
                from   {$this->tableName}
		        where  oid = {$oid};";
		$row = $this->sqlQueryRow($sql);
		return $this->_set($row);
	}

	/**
	 * @param $name
	 * @return SysDirVirtual
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
	 * @param string $orderBy
	 * @param string $dir
	 * @return SysDirVirtual[]
	 */
	public function getAll($orderBy="name", $dir="asc")
	{
		$this->sysLog->debug();
        $sql = "select {$this->getQueryColumnsStr()}
                from   {$this->tableName}
		        order by '{$orderBy}' {$dir};";
		$rows = $this->sqlQuery($sql);
		$array = array();
		for ($i=0; $i<count($rows); $i++)
		{
			$array[] = $this->_set($rows[$i]);
		}
		return $array;
	}

	// *******************************************************************************
	// CRUD methods
	// *******************************************************************************

	/**
	 * @param SysDirVirtual $o
	 * @return SysDirVirtual
	 */
	public function create(SysDirVirtual $o)
	{
		$this->sysLog->debug();
		$newId = parent::create($o);
		return $this->getById($newId);
	}

	/**
	 * @param SysDirVirtual $o
	 * @return mixed
	 */
	public function update(SysDirVirtual $o)
	{
		$this->sysLog->debug();
		return parent::update($o, "oid");
	}

	/**
	 * @param SysDirVirtual $o
	 * @return mixed
	 */
	public function delete(SysDirVirtual $o)
	{
		$this->sysLog->debug();
		return parent::delete($o, "oid");
	}

	// *******************************************************************************
	// Getters and Setters
	// *******************************************************************************

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
	public static function getColumnNames()
	{
		return array_merge(SysDirPhysicalTable::getColumnNames(), self::$columnNames);
	}

	/**
	 * @param null $dbRowObj
	 * @return SysDirVirtual
	 */
	private function _set($dbRowObj = null)
	{
		$this->sysLog->debug();

		$o = new SysDirVirtual();
		if ($dbRowObj) {
			foreach (self::getColumnNames() as $prop) {
				$o->set($prop, $dbRowObj->$prop);
			}
		} else {
			foreach (self::getColumnNames() as $prop) {
				$o->set($prop, null);
			}
		}
		return $o;
	}

}
