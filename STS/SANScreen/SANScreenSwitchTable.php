<?php
/*******************************************************************************
 *
 * $Id: SANScreenSwitchTable.php 82460 2014-01-04 15:38:17Z rcallaha $
 * $Date: 2014-01-04 10:38:17 -0500 (Sat, 04 Jan 2014) $
 * $Author: rcallaha $
 * $Revision: 82460 $
 * $HeadURL: https://svn.ultradns.net/svn/sts_tools/sts-lib/trunk/STS/SANScreen/SANScreenSwitchTable.php $
 *
 *******************************************************************************
 */

namespace STS\SANScreen;

use STS\DB\DBTable;

class SANScreenSwitchTable extends DBTable
{
    protected $dbIndex = 'sanscreen';
   	protected $tableName = 'switch';
    protected $idAutoIncremented = false;

	protected static $columnNames = array(
		"id", "name", "objectType", "vendor", "model", "fabricId", "firmwareVersion",
		"status", "wwn", "ip", "dead", "startTime", "endTime"
	);

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
	 * @return SANScreenSwitch
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
	 * @param $arrayId
	 * @return SANScreenSwitch[]
	 */
	public function getByArrayId($arrayId)
	{
		$this->sysLog->debug();
		$sql      = "SELECT DISTINCT s.*
                FROM   switch s,
                       port p1,
                       port p2,
                       array a
                WHERE  a.id = {$arrayId}
                  AND p1.deviceId = s.id
                  AND p2.id = p1.connectedPortId
                  AND a.id = p2.deviceId;";
		$result   = $this->sqlQuery($sql);
		$objArray = array();
		for ($i = 0; $i < count($result); $i++) {
			$objArray[] = $this->_set($result[$i]);
		}
		return $objArray;
	}

	/**
	 * @param string $orderBy
	 * @param string $dir
	 * @return SANScreenSwitch[]
	 */
	public function getAll($orderBy = "name", $dir = "asc")
	{
		$this->sysLog->debug();
		$sql   = "select {$this->getQueryColumnsStr()}
                from   {$this->tableName}
		        order by '{$orderBy}' {$dir};";
		$rows  = $this->sqlQuery($sql);
		$array = array();
		for ($i = 0; $i < count($rows); $i++) {
			$array[] = $this->_set($rows[$i]);
		}
		return $array;
	}

	// *******************************************************************************
	// CRUD methods
	// *******************************************************************************

	/**
	 * @param SANScreenSwitch $o
     * @param string $sql
	 * @return SANScreenSwitch
	 */
    public function create($o, $sql = "") {
   		$this->sysLog->debug();
   		$newId = parent::create($o, $sql);
   		return $this->getById($newId);
   	}

	/**
	 * @param SANScreenSwitch $o
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
	 * @param SANScreenSwitch $o
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
	 * @return int
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
	 * @return SANScreenSwitch
	 */
	private function _set($dbRowObj = null)
	{
		$this->sysLog->debug();

		$o = new SANScreenSwitch();
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
