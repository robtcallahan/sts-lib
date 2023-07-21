<?php
/*******************************************************************************
 *
 * $Id: HPSIMChassisWWNTable.php 81815 2013-12-09 20:00:43Z rcallaha $
 * $Date: 2013-12-09 15:00:43 -0500 (Mon, 09 Dec 2013) $
 * $Author: rcallaha $
 * $Revision: 81815 $
 * $HeadURL: https://svn.ultradns.net/svn/sts_tools/sts-lib/trunk/STS/HPSIM/HPSIMChassisWWNTable.php $
 *
 *******************************************************************************
 */

namespace STS\HPSIM;

use STS\DB\DBTable;


class HPSIMChassisWWNTable extends DBTable
{
    protected $dbIndex = 'hpsim';
   	protected $tableName = 'chassis_wwn';
    protected $idAutoIncremented = true;

	protected static $columnNames = array("id", "chassisId", "wwn", "type", "usedBy", "speed", "status");

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
	 * @return HPSIMChassisWWN
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
	 * @param        $chassisId
	 * @param string $orderBy
	 * @param string $dir
	 * @return HPSIMChassisWWN[]
	 */
	public function getByChassisId($chassisId, $orderBy = "wwn", $dir = "asc")
	{
		$this->sysLog->debug("chassisId=" . $chassisId);
		$sql      = "select {$this->getQueryColumnsStr()}
                from   {$this->tableName}
		        where  chassisId = {$chassisId}
		        order by $orderBy $dir;";
		$result   = $this->sqlQuery($sql);
		$objArray = array();
		for ($i = 0; $i < count($result); $i++) {
			$objArray[] = $this->_set($result[$i]);
		}
		return $objArray;
	}

    /**
     * @param $chassisId
     * @return mixed
     */
    public function deleteByChassisId($chassisId)
	{
		$this->sysLog->debug("chassisId=" . $chassisId);
		$sql = "delete from {$this->tableName}
		        where  chassisId = {$chassisId};";
		return $this->sql($sql);
	}

	// *******************************************************************************
	// CRUD methods
	// *******************************************************************************

    /**
     * @param HPSIMChassisWWN $o
     * @param string $sql
     * @return HPSIMChassisWWN
     */
    public function create($o, $sql="")
	{
		$this->sysLog->debug();
		$newId = parent::create($o);
		return $this->getById($newId);
	}

    /**
     * @param $o
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
     * @param $o
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
	 * @return void
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
	 * @return HPSIMChassisWWN
	 */
	private function _set($dbRowObj = null)
	{
		$this->sysLog->debug();

		$o = new HPSIMChassisWWN();
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
