<?php
/*******************************************************************************
 *
 * $Id: HPSIMExceptionTypeTable.php 82460 2014-01-04 15:38:17Z rcallaha $
 * $Date: 2014-01-04 10:38:17 -0500 (Sat, 04 Jan 2014) $
 * $Author: rcallaha $
 * $Revision: 82460 $
 * $HeadURL: https://svn.ultradns.net/svn/sts_tools/sts-lib/trunk/STS/HPSIM/HPSIMExceptionTypeTable.php $
 *
 *******************************************************************************
 */

namespace STS\HPSIM;

use STS\DB\DBTable;


class HPSIMExceptionTypeTable extends DBTable
{
	const BLEXC_SN_ON_INV = 1;           // HPSIM Name: S/N,      Power: On,     CMDB: 'inventory'
	const BLEXC_SN_ONOFF_SN = 2;         // HPSIM Name: S/N,      Power: On/Off, CMDB: S/N
	const BLEXC_SN_ONOFF_FQDN = 3;       // HPSIM Name: S/N,      Power: On/Off, CMDB: FQDN
	const BLEXC_SN_ONOFF_NOTFOUND = 4;   // HPSIM Name: S/N,      Power: On/Off, CMDB: Not Found
	const BLEXC_AQ_ONOFF_NOTFOUND = 5;   // HPSIM Name: Aquiring, Power: On/Off, CMDB: Not Found
	const BLEXC_FQDN_ONOFF_NOTFOUND = 6; // HPSIM Name: FQDN,     Power: On/Off, CMDB: Not Found

    const BLEXC_CONNECTION_ERROR = 11;   // Could not connect to hyperisor

	const VMEXC_NOTFOUND = 7;            // VM not found in CMDB
	const VMEXC_NOTDECOMMED = 8;         // VM not found in "xm list" and is not marked as Decommissioned in CMDB
	const VMEXC_EXISTSDECOMMED = 10;     // VM exists but marked as decommissioned in CMDB

	const MPEXC_NOTQUERIED = 9;          // Managment Processor not queried probably due to a permissions problem

    protected $dbIndex = 'hpsim';
   	protected $tableName = 'exception_type';
    protected $idAutoIncremented = true;

	private static $instance = null;

	protected static $columnNames = array("id", "exceptionNumber", "exceptionObject", "exceptionDescr");

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
	 * @return null|HPSIMExceptionTypeTable
	 */
	public static function singleton()
	{
		if (!self::$instance) {
			self::$instance = new HPSIMExceptionTypeTable();
		}
		return self::$instance;
	}

	/**
	 * @param $id
	 * @return HPSIMExceptionType
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
	 * @param $object
	 * @return HPSIMExceptionType[]
	 */
	public function getExceptionNumbersByObject($object)
	{
		$this->sysLog->debug();
		$sql   = "select {$this->getQueryColumnsStr()}
                from   {$this->tableName}
		        where  exceptionObject = '{$object}';";
		$rows  = $this->sqlQuery($sql);
		$types = array();
		for ($i = 0; $i < count($rows); $i++) {
			$e                               = $this->_set($rows[$i]);
			$types[$e->getExceptionNumber()] = $e;
		}
		return $types;
	}

	/**
	 * @param $object
	 * @return HPSIMExceptionType[]
	 */
	public function getExceptionIdsByObject($object)
	{
		$this->sysLog->debug();
		$sql   = "select {$this->getQueryColumnsStr()}
                from   {$this->tableName}
		        where  exceptionObject = '{$object}';";
		$rows  = $this->sqlQuery($sql);
		$types = array();
		for ($i = 0; $i < count($rows); $i++) {
			$e                  = $this->_set($rows[$i]);
			$types[$e->getId()] = $e;
		}
		return $types;
	}


	// *******************************************************************************
	// CRUD methods
	// *******************************************************************************

    /**
     * @param HPSIMExceptionType $o
     * @param string $sql
     * @return HPSIMExceptionType
     */
    public function create($o, $sql="")
	{
		$this->sysLog->debug();
		$newId = parent::create($o);
		return $this->getById($newId);
	}

    /**
     * @param HPSIMExceptionType $o
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
     * @param HPSIMExceptionType $o
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
	 * @return HPSIMExceptionType
	 */
	private function _set($dbRowObj = null)
	{
		$this->sysLog->debug();

		$o = new HPSIMExceptionType();
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
