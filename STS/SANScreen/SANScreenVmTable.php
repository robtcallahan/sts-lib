<?php
/*******************************************************************************
 *
 * $Id: SANScreenVmTable.php 82460 2014-01-04 15:38:17Z rcallaha $
 * $Date: 2014-01-04 10:38:17 -0500 (Sat, 04 Jan 2014) $
 * $Author: rcallaha $
 * $Revision: 82460 $
 * $HeadURL: https://svn.ultradns.net/svn/sts_tools/sts-lib/trunk/STS/SANScreen/SANScreenVmTable.php $
 *
 *******************************************************************************
 */

namespace STS\SANScreen;

use STS\DB\DBTable;
use STS\CMDB\CMDBServer;
use STS\CMDB\CMDBSubsystem;

class SANScreenVmTable extends DBTable
{
    protected $dbIndex = 'sanscreen';
   	protected $tableName = 'vm';
    protected $idAutoIncremented = true;

	protected static $columnNames = array(
		"id", "hostId", "sysId", "name", "modelNumber", "environment", "cmInstallStatus",
		"businessService", "subsystem", "opsSuppMgr", "opsSuppGrp"
	);

	// these are the CMDB keys mapped to local db column names
	protected static $cmdbServerColumns = array(
		"sysId", "name", "modelNumber", "environment", "installStatus", "businessService", "subsystemList"
	);

	protected static $ssVmColumns = array(
		"sysId", "name", "modelNumber", "environment", "cmInstallStatus", "businessService", "subsystem"
	);

	protected static $cmdbSubsysColumns = array("owningSupportManager", "operationsSupportGroup");

	protected static $ssSubsysColumns = array("opsSuppMgr", "opsSuppGrp");


    public function __construct($config = null)
    {
        if ($config) {
            // need to add these to the config since won't be in the config file
            $config['tableName']         = $this->tableName;
            $config['dbIndex']           = $this->dbIndex;
            $config['idAutoIncremented'] = $this->idAutoIncremented;
        }
        parent::__construct($config);
        $this->sysLog->debug();
	}

	/**
	 * @param $id
	 * @return SANScreenVm
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
	 * @param        $hostId
	 * @param string $orderBy
	 * @param string $dir
	 * @return SANScreenVM[]
	 */
	public function getByHostId($hostId, $orderBy = "name", $dir = "asc")
	{
		$this->sysLog->debug("hostId=" . $hostId);
		$sql   = "select {$this->getQueryColumnsStr()}
                from   {$this->tableName}
		        where  hostId = {$hostId}
		        order by $orderBy $dir;";
		$rows  = $this->sqlQuery($sql);
		$array = array();
		for ($i = 0; $i < count($rows); $i++) {
			$array[] = $this->_set($rows[$i]);
		}
		return $array;
	}

	/**
	 * @param $sysId
	 * @return SANScreenVm
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
	 * @param SANScreenVm $h
	 * @param CMDBServer  $s
	 * @return SANScreenVm
	 */
	public function mergeCmdbServer(SANScreenVm $h, CMDBServer $s)
	{
		for ($i = 0; $i < count(self::$cmdbServerColumns); $i++) {
			$h->set(self::$ssVmColumns[$i], $s->get(self::$cmdbServerColumns[$i]));
		}
		return $h;
	}

	/**
	 * @param SANScreenVm   $h
	 * @param CMDBSubsystem $s
	 * @return SANScreenVm
	 */
	public function mergeCmdbSubsystem(SANScreenVm $h, CMDBSubsystem $s)
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
	 * @param SANScreenVm $o
     * @param string $sql
	 * @return SANScreenVm
	 */
    public function create($o, $sql = "") {
   		$this->sysLog->debug();
   		$newId = parent::create($o, $sql);
   		return $this->getById($newId);
   	}

	/**
	 * @param SANScreenVm $o
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
	 * @param SANScreenVm $o
     * @param string $idColumn
     * @param string $sql
	 * @return mixed
	 */
    public function delete($o, $idColumn = "id", $sql = "") {
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
	 * @return SANScreenVm
	 */
	private function _set($dbRowObj = null)
	{
		$this->sysLog->debug();

		$o = new SANScreenVm();
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
