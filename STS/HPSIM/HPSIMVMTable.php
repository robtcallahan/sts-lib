<?php
/*******************************************************************************
 *
 * $Id: HPSIMVMTable.php 82460 2014-01-04 15:38:17Z rcallaha $
 * $Date: 2014-01-04 10:38:17 -0500 (Sat, 04 Jan 2014) $
 * $Author: rcallaha $
 * $Revision: 82460 $
 * $HeadURL: https://svn.ultradns.net/svn/sts_tools/sts-lib/trunk/STS/HPSIM/HPSIMVMTable.php $
 *
 *******************************************************************************
 */

namespace STS\HPSIM;

use STS\DB\DBTable;

use STS\CMDB\CMDBServer;
use STS\CMDB\CMDBSubsystem;

class HPSIMVMTable extends DBTable
{
    protected $dbIndex = 'hpsim';
   	protected $tableName = 'vm';
    protected $idAutoIncremented = true;

	protected static $columnNames = array(
		"id", "sysId", "bladeId", "isVmware", "vmUid", "deviceName", "fullDnsName", "active", "status", "inCmdb",
		"osName", "osVersion", "osPatchLevel", "memorySize", "numberOfCpus", "guestMemUsageMB", "overallCpuUsageMHz",
		"environment", "cmInstallStatus",
		"businessService", "subsystem", "opsSuppMgr", "opsSuppGrp",
		"comments", "shortDescr"
	);

	// these are names of the CMDB attributes returned from ServiceNow. Need to be able to translate between these names
	// and our object properties
	protected static $cmdbServerColumns = array(
		"sysId", "name", "os", "osVersion", "osServicePack",
		"ram", "cpuCount",
		"environment", "installStatus",
		"businessServices", "subsystemList",
		"comments", "shortDescription"
	);
	protected static $simServerColumns = array(
		"sysId", "fullDnsName", "osName", "osVersion", "osPatchLevel",
		"memorySize", "numberOfCpus",
		"environment", "cmInstallStatus",
		"businessService", "subsystem",
		"comments", "shortDescr"
	);

	protected static $cmdbSubsysColumns = array("owningSupportManager", "operationsSupportGroup");

	protected static $simSubsysColumns = array("opsSuppMgr", "opsSuppGrp");

    /**
     * @param null $config
     */
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
	 * @return HPSIMVM
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
	 * @param $deviceName
	 * @return HPSIMVM
	 */
	public function getByDeviceName($deviceName)
	{
		$this->sysLog->debug("deviceName=" . $deviceName);
		$sql = "select {$this->getQueryColumnsStr()}
                from   {$this->tableName}
		        where  deviceName = '{$deviceName}';";
		$row = $this->sqlQueryRow($sql);
		return $this->_set($row);
	}

	/**
	 * @param \STS\CMDB\CMDBServer|\STS\SNCache\Server $server
	 * @return HPSIMVM
	 */
	public static function cmdbServerToVm($server)
	{
		$newMe = new HPSIMVM();

		for ($i = 0; $i < count(self::$cmdbServerColumns); $i++) {
			$newMe->set(self::$simServerColumns[$i], $server->get(self::$cmdbServerColumns[$i]));
		}

		if (preg_match("/^([\w\d-_]+)\..*$/", $server->getName(), $m)) {
			$newMe->setDeviceName($m[1]);
		}
		else {
			$newMe->setDeviceName($server->getName());
		}
		return $newMe;
	}

	/**
	 * @param HPSIMVM       $c
	 * @param CMDBSubsystem $s
	 * @return HPSIMVM
	 */
	public static function mergeCmdbSubsystem(HPSIMVM $c, CMDBSubsystem $s)
	{
		for ($i = 0; $i < count(self::$cmdbSubsysColumns); $i++) {
			$c->set(self::$simSubsysColumns[$i], $s->get(self::$cmdbSubsysColumns[$i]));
		}
		return $c;
	}

	/**
	 * @param        $bladeId
	 * @param string $orderBy
	 * @param string $dir
	 * @return HPSIMVM[]
	 */
	public function getByBladeId($bladeId, $orderBy = "fullDnsName", $dir = "asc")
	{
		$this->sysLog->debug("bladeId=" . $bladeId);
		$sql      = "select {$this->getQueryColumnsStr()}
                from   {$this->tableName}
		        where  bladeId = {$bladeId}
		        order by " . $orderBy . " " . $dir . ";";
		$result   = $this->sqlQuery($sql);
		$objArray = array();
		for ($i = 0; $i < count($result); $i++) {
			$objArray[] = $this->_set($result[$i]);
		}
		return $objArray;
	}

	public function getByBladeIdAndDeviceName($bladeId, $deviceName)
	{
		$this->sysLog->debug("bladeId=" . $bladeId . ", deviceName=" . $deviceName);
		$sql = "select {$this->getQueryColumnsStr()}
                from   {$this->tableName}
		        where  bladeId = {$bladeId}
		          and  deviceName = '" . $deviceName . "';";
		return $this->sqlQueryRow($sql);
	}

	/**
	 * @param $sysId
	 * @return HPSIMVM
	 */
	public function getBySysId($sysId)
	{
		$this->sysLog->debug("sysId=" . $sysId);
		$sql = "select {$this->getQueryColumnsStr()}
                from   {$this->tableName}
		        where  sysId = '{$sysId}';";
		$row = $this->sqlQueryRow($sql);
		return $this->_set($row);
	}

	/**
	 * @param $bladeId
	 * @param $fqdn
	 * @return HPSIMVM
	 */
	public function getByBladeIdAndFqdn($bladeId, $fqdn)
	{
		$this->sysLog->debug("bladeId=" . $bladeId . ", fqdn=" . $fqdn);
		$sql = "select {$this->getQueryColumnsStr()}
                from   {$this->tableName}
		        where  bladeId = {$bladeId}
		          and  fullDnsName = '{$fqdn}';";
		$row = $this->sqlQueryRow($sql);
		return $this->_set($row);
	}

	/**
	 * @param string $orderBy
	 * @param string $dir
	 * @return HPSIMVM[]
	 */
	public function getAll($orderBy = "fullDnsName", $dir = "asc")
	{
		$this->sysLog->debug();
		$sql    = "select {$this->getQueryColumnsStr()}
                from   {$this->tableName}
		        order by " . $orderBy . " " . $dir . ";";
		$result = $this->sqlQuery($sql);
		$vms    = array();
		for ($i = 0; $i < count($result); $i++) {
			$vms[] = $this->_set($result[$i]);
		}
		return $vms;
	}

	// *******************************************************************************
	// CRUD methods
	// *******************************************************************************

    /**
     * @param HPSIMVM $o
     * @param string $sql
     * @return HPSIMVM
     */
    public function create($o, $sql="")
	{
		$this->sysLog->debug();
		$newId = parent::create($o);
		return $this->getById($newId);
	}

    /**
     * @param HPSIMVM $o
     * @param string $idColumn
     * @param string $sql
     * @return mixed
     */
    public function update($o, $idColumn = "id", $sql = "")
	{
		$this->sysLog->debug();
		return parent::update($o);
	}

    /**
     * @param HPSIMVM $o
     * @param string $idColumn
     * @param string $sql
     * @return mixed
     */
    public function delete($o, $idColumn = "id", $sql = "")
	{
		$this->sysLog->debug();
		return parent::delete($o);
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
	 * @return HPSIMVM
	 */
	private function _set($dbRowObj = null)
	{
		$this->sysLog->debug();

		$o = new HPSIMVM();
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
