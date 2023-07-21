<?php
/*******************************************************************************
 *
 * $Id: HPSIMBladeWWNTable.php 81891 2013-12-11 21:08:55Z rcallaha $
 * $Date: 2013-12-11 16:08:55 -0500 (Wed, 11 Dec 2013) $
 * $Author: rcallaha $
 * $Revision: 81891 $
 * $HeadURL: https://svn.ultradns.net/svn/sts_tools/sts-lib/trunk/STS/HPSIM/HPSIMBladeWWNTable.php $
 *
 *******************************************************************************
 */

namespace STS\HPSIM;

use STS\DB\DBTable;


class HPSIMBladeWWNTable extends DBTable
{
    protected $dbIndex = 'hpsim';
   	protected $tableName = 'blade_wwn';
    protected $idAutoIncremented = false;

	protected static $columnNames = array("id", "bladeId", "wwn", "port", "fabricName", "speed", "status", "mac");

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
	 * @return HPSIMBladeWWN
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
	 * @param        $bladeId
	 * @param string $orderBy
	 * @param string $dir
	 * @return HPSIMBladeWWN[]
	 */
	public function getByBladeId($bladeId, $orderBy = "wwn", $dir = "asc")
	{
		$sql  = "select {$this->getQueryColumnsStr()}
                from   {$this->tableName}
		        where  bladeId = {$bladeId}
		        order by $orderBy $dir;";
		$result   = $this->sqlQuery($sql);
		$objArray = array();
		for ($i = 0; $i < count($result); $i++) {
			$objArray[] = $this->_set($result[$i]);
		}
		return $objArray;
	}

    /**
     * @param $bladeId
     * @return mixed
     */
    public function deleteByBladeId($bladeId)
	{
		$sql = "delete from {$this->tableName}
		        where  bladeId = {$bladeId};";
		return $this->sql($sql);
	}

	// *******************************************************************************
	// CRUD methods
	// *******************************************************************************

    /**
     * @param HPSIMBladeWWN $o
     * @param string $sql
     * @return HPSIMBladeWWN
     */
    public function create($o, $sql="")
	{
		$this->sysLog->debug();
		$newId = parent::create($o);
		return $this->getById($newId);
	}

    /**
     * @param HPSIMBladeWWN $o
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
     * @param HPSIMBladeWWN $o
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
	 * @return HPSIMBladeWWN
	 */
	private function _set($dbRowObj = null)
	{
		$this->sysLog->debug();

		$o = new HPSIMBladeWWN();
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
