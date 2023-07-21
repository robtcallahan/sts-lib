<?php
/*******************************************************************************
 *
 * $Id: HPSIMMgmtProcessorTable.php 82037 2013-12-17 19:19:11Z rcallaha $
 * $Date: 2013-12-17 14:19:11 -0500 (Tue, 17 Dec 2013) $
 * $Author: rcallaha $
 * $Revision: 82037 $
 * $HeadURL: https://svn.ultradns.net/svn/sts_tools/sts-lib/trunk/STS/HPSIM/HPSIMMgmtProcessorTable.php $
 *
 *******************************************************************************
 */

namespace STS\HPSIM;

use STS\DB\DBTable;


class HPSIMMgmtProcessorTable extends DBTable
{
    protected $dbIndex = 'hpsim';
   	protected $tableName = 'mgmt_processor';
    protected $idAutoIncremented = false;

	// array of our object properties in the same order as xmlAttributes. provides the translation from one to the other
	protected static $columnNames = array(
		"id", "deviceName", "hwStatus", "mpStatus",
		"deviceType", "deviceAddress", "productName", "osName", "fullDnsName",
		"assocDeviceName", "assocDeviceType", "chassisId", "assocType",
		"version", "role"
	);

	// these are names of the XML attributes returned from mxquery and mxreport. Need to be able to translate between these names
	// and our object properties
	protected static $xmlAttributes = array(
		"DeviceKey", "DeviceName", "HWStatus", "MPStatus",
		"DeviceType", "DeviceAddress", "ProductName", "OSName", "FullDNSName",
		"AssociatedDeviceName", "AssociatedDeviceType", "AssociatedDeviceKey", "AssociationType",
		"Version"
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
	 * @return HPSIMMgmtProcessor
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
	 * @param $hash
	 * @return null|HPSIMMgmtProcessor
	 */
	public static function simToMgmtProcessor($hash)
	{
		if ($hash["AssociationType"] != "MgmtProcToEnclosure") {
			return null;
		}

		$newMe = new HPSIMMgmtProcessor();

		foreach ($hash as $k => $v) {
			$key = array_search($k, self::$xmlAttributes);
			if ($key !== false) {
				$newMe->set(self::$columnNames[$key], $v);
			}
		}
		return $newMe;
	}

	/**
	 * @param $chassisId
	 * @return HPSIMMgmtProcessor[]
	 */
	public function getByChassisId($chassisId)
	{
		$this->sysLog->debug("chassisId=" . $chassisId);
		$sql      = "select {$this->getQueryColumnsStr()}
                     from   {$this->tableName}
		             where  chassisId = {$chassisId}
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
   	 * @return HPSIMMgmtProcessor
   	 */
   	public function getActiveByChassisId($chassisId)
   	{
   		$this->sysLog->debug("chassisId=" . $chassisId);
   		$sql      = "select {$this->getQueryColumnsStr()}
                     from   {$this->tableName}
   		             where  chassisId = {$chassisId}
   		               and  role = 'Active'
   		             order by deviceName;";
        $row = $this->sqlQueryRow($sql);
        return $this->_set($row);
   	}

	/**
	 * @param $fqdn
	 * @return HPSIMMgmtProcessor
	 */
	public function getByFullDnsName($fqdn)
	{
		$this->sysLog->debug("fqdn=" . $fqdn);
		$sql = "select {$this->getQueryColumnsStr()}
                from   {$this->tableName}
		        where  fullDnsName = '{$fqdn}';";
		$row = $this->sqlQueryRow($sql);
		return $this->_set($row);
	}

	/**
	 * @return HPSIMMgmtProcessor[]
	 */
	public function getAll()
	{
		$this->sysLog->debug();

		$sql    = "select {$this->getQueryColumnsStr()}
          	    from   {$this->tableName}
          	    order by deviceName;";
		$result = $this->sqlQuery($sql);
		$objs   = array();
		for ($i = 0; $i < count($result); $i++) {
			$objs[] = $this->_set($result[$i]);
		}
		return $objs;
	}

	/**
	 * @return string
	 */
	public function getAllAsList()
	{
		$this->sysLog->debug();
		// get all the mgmt processors
		$mps = $this->getAll();

		// create a string of these hosts with a carriage return separator
		$list = "";
		for ($i = 0; $i < count($mps); $i++) {
			$mp   = $mps[$i];
			if (!$mp->getFullDnsName()) {
				if (preg_match("/(st|ch)/", $mp->getDeviceName(), $m)) {
					switch ($m[1]) {
						case "st":
							$mp->setFullDnsName($mp->getDeviceName() . ".va.neustar.com");
							break;
						case "ch":
							$mp->setFullDnsName($mp->getDeviceName() . ".nc.neustar.com");
							break;
					}
				} else {
					continue;
				}
			}

			$fqdn = $mp->getFullDnsName();

			// sadly, we have to exclude some chassis....for now
            /*
			if ($fqdn === null || preg_match("/^(st|ch)(nt|ul)/", $fqdn)) {
				continue;
			}
            */
			$list .= $fqdn . "\n";
		}
		return $list;
	}

	/**
	 * @return string
	 */
	public function getAllActiveAsList()
	{
		$this->sysLog->debug();
		// get all the mgmt processors
		$mps = $this->getAll();

		// create a string of these hosts with a carriage return separator
		$list = "";
		for ($i = 0; $i < count($mps); $i++) {
			/** @var $mp HPSIMMgmtProcessor */
			$mp   = $mps[$i];
			$fqdn = $mp->getFullDnsName();

			// include Active but not NT and Ultra chassis
			if ($fqdn === null || preg_match("/^(st|ch)(nt|ul)/", $fqdn) || $mp->getRole() != "Active") {
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
     * @param HPSIMMgmtProcessor $o
     * @param string $sql
     * @return HPSIMMgmtProcessor
     */
    public function create($o, $sql="")
	{
		$this->sysLog->debug();
		$newId = parent::create($o);
		return $this->getById($newId);
	}

    /**
     * @param HPSIMMgmtProcessor $o
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
     * @param HPSIMMgmtProcessor $o
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
	 * @return HPSIMMgmtProcessor
	 */
	private function _set($dbRowObj = null)
	{
		$this->sysLog->debug();

		$o = new HPSIMMgmtProcessor();
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
