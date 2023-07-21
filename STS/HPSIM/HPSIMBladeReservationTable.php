<?php
/*******************************************************************************
 *
 * $Id: HPSIMBladeReservationTable.php 81815 2013-12-09 20:00:43Z rcallaha $
 * $Date: 2013-12-09 15:00:43 -0500 (Mon, 09 Dec 2013) $
 * $Author: rcallaha $
 * $Revision: 81815 $
 * $HeadURL: https://svn.ultradns.net/svn/sts_tools/sts-lib/trunk/STS/HPSIM/HPSIMBladeReservationTable.php $
 *
 *******************************************************************************
 */

namespace STS\HPSIM;

use STS\DB\DBTable;

class HPSIMBladeReservationTable extends DBTable
{
    protected $dbIndex = 'hpsim';
   	protected $tableName = 'blade_reservation';
    protected $idAutoIncremented = true;

	protected static $columnNames = array(
		"id",
        "bladeId",
        "taskNumber",
        "taskSysId", "taskShortDescr", "projectName", "dateReserved", "userReserved",
		"dateUpdated", "userUpdated", "dateCompleted", "userCompleted", "dateCancelled", "userCancelled"
	);

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
	 * @return HPSIMBladeReservation
	 */
	public function getById($id)
	{
		$this->sysLog->debug("id=" . $id);
		$sql = "select {$this->getQueryColumnsStr()}
                from   {$this->tableName}
		        where  id = " . $id . ";";
		$row = $this->sqlQueryRow($sql);
		return $this->_set($row);
	}

	/**
	 * @param $bladeId
	 * @return HPSIMBladeReservation
	 */
	public function getByBladeId($bladeId)
	{
		$this->sysLog->debug("bladeId=" . $bladeId);
		$sql = "select {$this->getQueryColumnsStr()}
                from   {$this->tableName}
		        where  bladeId = {$bladeId};";
		$row = $this->sqlQueryRow($sql);
		return $this->_set($row);
	}

	/**
	 * @param $bladeId
	 * @return HPSIMBladeReservation
	 */
	public function getOpenByBladeId($bladeId)
	{
		$this->sysLog->debug("bladeId=" . $bladeId);
		$sql = "select {$this->getQueryColumnsStr()}
                from   {$this->tableName}
		        where  bladeId = {$bladeId}
		          and  dateCancelled is null
		          and  dateCompleted is null;";
		$row = $this->sqlQueryRow($sql);
		return $this->_set($row);
	}

	/**
	 * @return HPSIMBladeReservation[]
	 */
	public function getAll()
	{
		$this->sysLog->debug();
		$sql = "select {$this->getQueryColumnsStr()}
                from   {$this->tableName}
		        where  dateCancelled is null
		          and  dateCompleted is null;";
		$result = $this->sqlQuery($sql);
		$objects  = array();
		for ($i = 0; $i < count($result); $i++) {
			$objects[] = $this->_set($result[$i]);
		}
		return $objects;
	}

    /**
   	 * @return HPSIMBladeReservation[]
   	 */
   	public function getAllHashByBladeId()
   	{
   		$this->sysLog->debug();
   		$sql = "select {$this->getQueryColumnsStr()}
                   from   {$this->tableName}
   		        where  dateCancelled is null
   		          and  dateCompleted is null;";
   		$result = $this->sqlQuery($sql);
   		$objects  = array();
   		for ($i = 0; $i < count($result); $i++) {
   			$objects[$result[$i]->bladeId] = $this->_set($result[$i]);
   		}
   		return $objects;
   	}

	/**
	 * @param $bladeId
	 * @param $projectName
	 * @return HPSIMBladeReservation
	 */
	public function getOpenByBladeIdAndProjectName($bladeId, $projectName)
	{
		$this->sysLog->debug("bladeId=" . $bladeId . ", projectName=" . $projectName);
		$sql = "select {$this->getQueryColumnsStr()}
                from   {$this->tableName}
		        where  bladeId = {$bladeId}
		          and  projectName = '{$projectName}'
		          and  dateCancelled is null
		          and  dateCompleted is null;";
		$row = $this->sqlQueryRow($sql);
		return $this->_set($row);
	}

	// *******************************************************************************
	// CRUD methods
	// *******************************************************************************

    /**
     * @param HPSIMBladeReservation $o
     * @param string $sql
     * @return HPSIMBladeReservation
     */
    public function create($o, $sql="")
	{
		$this->sysLog->debug();
		$newId = parent::create($o);
		return $this->getById($newId);
	}

    /**
     * @param HPSIMBladeReservation $o
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
     * @param HPSIMBladeReservation $o
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
	 * @return HPSIMBladeReservation
	 */
	private function _set($dbRowObj = null)
	{
		$this->sysLog->debug();

		$o = new HPSIMBladeReservation();
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
