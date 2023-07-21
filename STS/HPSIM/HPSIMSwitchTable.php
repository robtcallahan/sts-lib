<?php
/*******************************************************************************
 *
 * $Id: HPSIMSwitchTable.php 81891 2013-12-11 21:08:55Z rcallaha $
 * $Date: 2013-12-11 16:08:55 -0500 (Wed, 11 Dec 2013) $
 * $Author: rcallaha $
 * $Revision: 81891 $
 * $HeadURL: https://svn.ultradns.net/svn/sts_tools/sts-lib/trunk/STS/HPSIM/HPSIMSwitchTable.php $
 *
 *******************************************************************************
 */

namespace STS\HPSIM;

use STS\DB\DBTable;


class HPSIMSwitchTable extends DBTable
{
    protected $dbIndex = 'hpsim';
   	protected $tableName = 'switch';
    protected $idAutoIncremented = false;

	// these are names of the XML attributes returned from mxquery and mxreport. Need to be able to translate between these names
	// and our object properties
	protected static $xmlAttributes = array(
		"DeviceKey", "DeviceName", "HWStatus", "MPStatus", "SWStatus",
		"DeviceType", "DeviceAddress", "ProductName", "FullDNSName",
		"AssociatedDeviceName", "AssociatedDeviceType", "AssociatedDeviceKey", "AssociationType",
		"Version"
	);

	// array of our object properties in the same order as xmlAttributes. provides the translation from one to the other
	protected static $columnNames = array(
		"id", "deviceName", "hwStatus", "mpStatus", "swStatus",
		"deviceType", "deviceAddress", "productName", "fullDnsName",
		"assocDeviceName", "assocDeviceType", "chassisId", "assocType",
		"version", "role");

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
	 * @param $hash
	 * @return null|HPSIMSwitch
	 */
	public static function simToSwitch($hash)
	{
		if ($hash["AssociationType"] != "SwitchToEnclosure") {
			return null;
		}

		$newMe = new HPSIMSwitch();

		foreach ($hash as $k => $v) {
			$key = array_search($k, self::$xmlAttributes);
			if ($key !== false) {
				$newMe->set(self::$columnNames[$key], $v);
			}
		}
		return $newMe;
	}

	/**
	 * @param $id
	 * @return HPSIMSwitch
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
	 * @param $chassisId
	 * @return HPSIMSwitch[]
	 */
	public function getByChassisId($chassisId)
	{
		$this->sysLog->debug("chassisId=" . $chassisId);
		$sql      = "select {$this->getQueryColumnsStr()}
                     from   {$this->tableName}
		             where  chassisId = " . $chassisId . "
		             order by deviceName;";
		$result   = $this->sqlQuery($sql);
		$objArray = array();
		for ($i = 0; $i < count($result); $i++) {
			$objArray[] = $this->_set($result[$i]);
		}
		return $objArray;
	}

    /**
   	 * @param $chassisId
   	 * @return HPSIMSwitch
   	 */
   	public function getActiveByChassisId($chassisId)
   	{
   		$this->sysLog->debug("chassisId=" . $chassisId);
   		$sql      = "select {$this->getQueryColumnsStr()}
                     from   {$this->tableName}
   		             where  chassisId = " . $chassisId . "
   		               and  role = 'Active'
   		             order by deviceName;";
        $row = $this->sqlQueryRow($sql);
        return $this->_set($row);
   	}

	/**
	 * @param $ipAddress
	 * @return HPSIMSwitch
	 */
	public function getByIpAddress($ipAddress)
	{
		$this->sysLog->debug("ipAddress=" . $ipAddress);
		$sql = "select {$this->getQueryColumnsStr()}
                from   {$this->tableName}
		        where  deviceAddress = '" . $ipAddress . "';";
		$row = $this->sqlQueryRow($sql);
		return $this->_set($row);
	}

    /**
     * @param $fqdn
     * @return HPSIMSwitch
     */
    public function getByFullDnsName($fqdn)
   	{
   		$this->sysLog->debug("fqdn=" . $fqdn);
   		$sql = "select {$this->getQueryColumnsStr()}
                  from {$this->tableName}
   		        where  fullDnsName = '" . $fqdn . "';";
   		$row = $this->sqlQueryRow($sql);
   		return $this->_set($row);
   	}

    /**
     * @return HPSIMSwitch[]
     */
    public function getAll()
   	{
   		$this->sysLog->debug();

   		$sql    = "select {$this->getQueryColumnsStr()}
             	   from   " . $this->tableName . "
             	    order by deviceName;";
   		$result = $this->sqlQuery($sql);
   		$objs   = array();
   		for ($i = 0; $i < count($result); $i++) {
   			$objs[] = $this->_set($result[$i]);
   		}
   		return $objs;
   	}

    /**
     * @return HPSIMSwitch[]
     */
    public function getAllActive()
   	{
   		$this->sysLog->debug();

   		$sql    = "select {$this->getQueryColumnsStr()}
             	   from   " . $this->tableName . "
             	   where  role = 'Active'
             	   order by deviceName;";
   		$result = $this->sqlQuery($sql);
   		$objs   = array();
   		for ($i = 0; $i < count($result); $i++) {
   			$objs[] = $this->_set($result[$i]);
   		}
   		return $objs;
   	}


    public function getAllActiveAsList()
   	{
   		$this->sysLog->debug();
   		// get all the mgmt processors
   		$switches = $this->getAll();

   		// create a string of these hosts with a carriage return separator
   		$list = "";
   		for ($i = 0; $i < count($switches); $i++) {
   			$sw   = $switches[$i];
   			$fqdn = $sw->getFullDnsName();

   			// include Active but not NT and Ultra chassis
   			if ($fqdn === null || preg_match("/^(st|ch)(nt|ul)/", $fqdn) || $sw->getRole() != "Active") {
   				continue;
   			}
   			$list .= $fqdn . "\n";
   		}
   		return $list;
   	}


	// *******************************************************************************
	// CRUD methods
	// *******************************************************************************

    /**
     * @param HPSIMSwitch $o
     * @param string $sql
     * @return HPSIMSwitch
     */
    public function create($o, $sql="")
	{
		$this->sysLog->debug();
		$newId = parent::create($o);
		return $this->getById($newId);
	}

    /**
     * @param HPSIMSwitch $o
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
     * @param HPSIMSwitch $o
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
	 * @return HPSIMSwitch
	 */
	private function _set($dbRowObj = null)
	{
		$this->sysLog->debug();

		$o = new HPSIMSwitch();
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
