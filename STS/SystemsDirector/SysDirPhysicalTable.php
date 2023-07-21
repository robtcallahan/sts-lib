<?php
/*******************************************************************************
 *
 * $Id: SysDirPhysicalTable.php 75275 2013-05-15 20:19:29Z rcallaha $
 * $Date: 2013-05-15 16:19:29 -0400 (Wed, 15 May 2013) $
 * $Author: rcallaha $
 * $Revision: 75275 $
 * $HeadURL: https://svn.ultradns.net/svn/sts_tools/sts-lib/trunk/STS/SystemsDirector/SysDirPhysicalTable.php $
 *
 *******************************************************************************
 */

namespace STS\SystemsDirector;

use STS\DB\DBTable;
use STS\SystemsDirector\SysDirPhysical;

use STS\CMDB\CMDBServer;
use STS\CMDB\CMDBSubsystem;

class SysDirPhysicalTable extends DBTable
{
	protected static $columnNames = array(
		"oid", "name", "machineType", "model", "manufacturer", "architecture", "serialNumber",
		"sysId", "environment", "cmInstallStatus", "businessService", "subsystem",
		"opsSuppMgr", "opsSuppGrp", "comments", "shortDescr"
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
		$this->tableName = "physical";
		parent::__construct($idAutoIncremented);

		$this->sysLog->debug();
	}

	/**
	 * @param $id
	 * @return SysDirPhysical
	 */
	public function getById($id) {
		return $this->getByOid($id);
	}

	/**
	 * @param SysDirPhysical $c
	 * @param CMDBServer     $s
	 * @return SysDirPhysical
	 */
	public static function mergeCmdbServer(SysDirPhysical $c, CMDBServer $s)
	{
		for ($i = 0; $i < count(self::$cmdbServerColumns); $i++) {
			$c->set(self::$sysDirServerColumns[$i], $s->get(self::$cmdbServerColumns[$i]));
		}
		return $c;
	}

	/**
	 * @param SysDirPhysical $c
	 * @param CMDBSubsystem  $s
	 * @return SysDirPhysical
	 */
	public static function mergeCmdbSubsystem(SysDirPhysical $c, CMDBSubsystem $s)
	{
		for ($i = 0; $i < count(self::$cmdbSubsysColumns); $i++) {
			$c->set(self::$sysDirSubsysColumns[$i], $s->get(self::$cmdbSubsysColumns[$i]));
		}
		return $c;
	}

	/**
	 * @param $oid
	 * @return SysDirPhysical
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
	 * @return SysDirPhysical
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
	 * @return SysDirPhysical[]
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
	 * @param SysDirPhysical $o
	 * @return SysDirPhysical
	 */
	public function create(SysDirPhysical $o)
	{
		$this->sysLog->debug();
		$newId = parent::create($o);
		return $this->getById($newId);
	}

	/**
	 * @param SysDirPhysical $o
	 * @return mixed
	 */
	public function update(SysDirPhysical $o)
	{
		$this->sysLog->debug();
		return parent::update($o, "oid");
	}

	/**
	 * @param SysDirPhysical $o
	 * @return mixed
	 */
	public function delete(SysDirPhysical $o)
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
		return self::$columnNames;
	}

	/**
	 * @param null $dbRowObj
	 * @return SysDirPhysical
=	 */
	private function _set($dbRowObj = null)
	{
		$this->sysLog->debug();

		$o = new SysDirPhysical();
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
