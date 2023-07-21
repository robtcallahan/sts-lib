<?php

namespace STS\HPSIM;

use STS\DB\DBTable;

use STS\CMDB\CMDBServer;
use STS\CMDB\CMDBSubsystem;
use STS\Util\DistSwitchLookup;
use STS\SNCache\Server;

class HPSIMBladeTable extends DBTable
{
    protected $dbIndex = 'hpsim';
   	protected $tableName = 'blade';
    protected $idAutoIncremented = false;

	// array of our object properties in the same order as xmlAttributes. provides the translation from one to the other
	protected static $columnNames = array(
		"id", "deviceName", "hwStatus", "mpStatus", "swStatus", "vmmStatus", "pmpStatus",
		"deviceType", "deviceAddress", "productName", "osName", "fullDnsName",
		"assocDeviceName", "assocDeviceType", "chassisId", "assocType",
		"serialNumber", "romVersion", "slotNumber",
        "hsUid", "ccrName",
		"memorySizeGB", "numCpus", "numCoresPerCpu", "cpuSpeedMHz", "iLo", "powerStatus", "distSwitchName",
        "overallMemoryUsageGB", "memoryUsagePercent", "overallCpuUsage", "cpuUsagePercent",
		"distNetworkCidr", "isInventory", "isSpare",
		"sysId", "cmdbName", "environment", "cmInstallStatus", "businessService", "subsystem",
		"opsSuppMgr", "opsSuppGrp", "comments", "shortDescr", "queried", "inCmdb"
	);


	// these are the CMDB keys mapped to local db column names
	protected static $cmdbServerColumns = array(
		"sysId", "name", "environment", "installStatus",
		"businessServices", "subsystemList",
		"comments", "shortDescription"
	);

	protected static $simServerColumns = array(
		"sysId", "cmdbName", "environment", "cmInstallStatus",
		"businessService", "subsystem",
		"comments", "shortDescr"
	);

	protected static $cmdbSubsysColumns = array("owningSupportManager", "operationsSupportGroup");

	protected static $simSubsysColumns = array("opsSuppMgr", "opsSuppGrp");

	// these are names of the XML attributes returned from mxquery and mxreport. Need to be able to translate between these names
	// and our object properties
	protected static $xmlAttributes = array(
		"DeviceKey", "DeviceName", "HWStatus", "MPStatus", "SWStatus", "VMMStatus", "pmp.status",
		"DeviceType", "DeviceAddress", "ProductName", "OSName", "FullDNSName",
		"AssociatedDeviceName", "AssociatedDeviceType", "AssociatedDeviceKey", "AssociationType",
		"SerialNumber", "ROMVersion", "SlotNumber"
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
	 * @param $hash
	 * @return null|HPSIMBlade
	 */
	public static function simToBlade($hash)
	{
		if ($hash["AssociationType"] != "ServerToEnclosure") {
			return null;
		}

		$newMe = new HPSIMBlade();

		foreach ($hash as $k => $v) {
			$key = array_search($k, self::$xmlAttributes);
			if ($key !== false) {
				$newMe->set(self::$columnNames[$key], $v);
			}
		}
		return $newMe;
	}

	/**
	 * @param HPSIMBlade $c
	 * @param CMDBServer $s
	 * @return HPSIMBlade
	 */
	public static function mergeCmdbServer(HPSIMBlade $c, $s)
	{
		for ($i = 0; $i < count(self::$cmdbServerColumns); $i++) {
			$c->set(self::$simServerColumns[$i], $s->get(self::$cmdbServerColumns[$i]));
		}
		return $c;
	}

	/**
	 * @param HPSIMBlade    $c
	 * @param CMDBSubsystem $s
	 * @return HPSIMBlade
	 */
	public static function mergeCmdbSubsystem(HPSIMBlade $c, CMDBSubsystem $s)
	{
		for ($i = 0; $i < count(self::$cmdbSubsysColumns); $i++) {
			$c->set(self::$simSubsysColumns[$i], $s->get(self::$cmdbSubsysColumns[$i]));
		}
		return $c;
	}

	/**
	 * @param HPSIMBlade $o
	 * @return HPSIMBlade
	 */
	public static function mapToDistSwitch(HPSIMBlade $o)
	{
		$switchName = "";
		$network    = "";

		if ($o->getDeviceAddress() == "") return $o;

		/** @var $distSw DistSwitchLookup */
		$distSw = DistSwitchLookup::singleton();
		$found  = $distSw->getDistSwitchNameByIp($o->getDeviceAddress(), $switchName, $network);

		if ($found) {
			$o->setDistNetworkCidr($network);
			$o->setDistSwitchName($switchName);
		}
		return $o;
	}

	/**
	 * @param $bladeId
	 * @return HPSIMBlade
	 */
	public function getById($bladeId)
	{
		$this->sysLog->debug("id=" . $bladeId);
		$sql = "select {$this->getQueryColumnsStr()}
                from   {$this->tableName}
		        where  id = " . $bladeId . ";";
		$row = $this->sqlQueryRow($sql);
		return $this->_set($row);
	}

	/**
	 * @param $deviceName
	 * @return HPSIMBlade
	 */
	public function getByDeviceName($deviceName)
	{
		$this->sysLog->debug();
		$sql = "select {$this->getQueryColumnsStr()}
                from   {$this->tableName}
		        where  deviceName = '" . $deviceName . "';";
		$row = $this->sqlQueryRow($sql);
		return $this->_set($row);
	}

	/**
	 * @param $serialNumber
	 * @return HPSIMBlade
	 */
	public function getBySerialNumber($serialNumber)
	{
		$this->sysLog->debug();
		$sql = "select {$this->getQueryColumnsStr()}
                from   {$this->tableName}
		        where  serialNumber = '" . $serialNumber . "';";
		$row = $this->sqlQueryRow($sql);
		return $this->_set($row);
	}

	/**
	 * @param $deviceNameSubstring
	 * @return HPSIMBlade[]
	 */
	public function getByDeviceNameLike($deviceNameSubstring)
	{
		$this->sysLog->debug();
		$sql    = "select {$this->getQueryColumnsStr()}
                from   {$this->tableName}
		        where  deviceName LIKE '%" . $deviceNameSubstring . "';";
		$result = $this->sqlQuery($sql);
		$blades = array();
		for ($i = 0; $i < count($result); $i++) {
			$blades[] = $this->_set($result[$i]);
		}
		return $blades;
	}

	/**
	 * @param $fqdn
	 * @return HPSIMBlade
	 */
	public function getByFullDnsName($fqdn)
	{
		$this->sysLog->debug();
		$sql = "select {$this->getQueryColumnsStr()}
                from   {$this->tableName}
		        where  fullDnsName = '" . $fqdn . "';";
		$row = $this->sqlQueryRow($sql);
		return $this->_set($row);
	}

	/**
	 * @param $fqdn
	 * @return HPSIMBlade
	 */
	public function getByFqdn($fqdn)
	{
		return $this->getByFullDnsName($fqdn);
	}

	/**
	 * @param        $chassisId
	 * @param string $orderBy
	 * @param string $dir
	 * @return HPSIMBlade[]
	 */
	public function getByChassisId($chassisId, $orderBy = "deviceName", $dir = "asc")
	{
		$this->sysLog->debug();
		$sql    = "select {$this->getQueryColumnsStr()}
                   from   {$this->tableName}
		           where  chassisId = {$chassisId}
		           order by " . $orderBy . " " . $dir . ";";
		$result = $this->sqlQuery($sql);
		$blades = array();
		for ($i = 0; $i < count($result); $i++) {
			$blades[] = $this->_set($result[$i]);
		}
		return $blades;
	}

	/**
	 * @param $chassisId
	 * @param $slotNumber
	 * @return HPSIMBlade
	 */
	public function getByChassisIdAndSlotNumber($chassisId, $slotNumber)
	{
		$this->sysLog->debug();
		$sql = "select {$this->getQueryColumnsStr()}
                from   {$this->tableName}
		        where  chassisId = " . $chassisId . "
		          and  slotNumber = " . $slotNumber . ";";
		$row = $this->sqlQueryRow($sql);
		return $this->_set($row);
	}

	/**
	 * @param $fqdn
	 * @return HPSIMBlade[]
	 */
	public function getByMmFqdn($fqdn)
	{
		$this->sysLog->debug();
		$sql    = "select {$this->getQueryColumnsStr()}
                from   {$this->tableName}
		        where  chassisId in (select chassisId from mgmt_processor where fullDnsName = '" . $fqdn . "');";
		$result = $this->sqlQuery($sql);
		$blades = array();
		for ($i = 0; $i < count($result); $i++) {
			$blades[] = $this->_set($result[$i]);
		}
		return $blades;
	}

	/**
	 * @param $fqdn
	 * @param $slotNumber
	 * @return HPSIMBlade
	 */
	public function getByMmFqdnAndSlotNumber($fqdn, $slotNumber)
	{
		$this->sysLog->debug();
		$sql    = "select {$this->getQueryColumnsStr()}
                from   {$this->tableName}
		        where  chassisId in (select chassisId from mgmt_processor where fullDnsName = '" . $fqdn . "')
		          and  slotNumber = " . $slotNumber . ";";
		$result = $this->sqlQueryRow($sql);
		return $this->_set($result);
	}

	/**
	 * @param string $orderBy
	 * @param string $dir
	 * @return HPSIMBlade[]
	 */
	public function getAll($orderBy = "deviceName", $dir = "asc")
	{
		$this->sysLog->debug();
		$sql     = "select {$this->getQueryColumnsStr()}
                from   {$this->tableName}
		        order by " . $orderBy . " " . $dir . ";";
		$result  = $this->sqlQuery($sql);
		$blades = array();
		for ($i = 0; $i < count($result); $i++) {
            $blades[] = $this->_set($result[$i]);
		}
		return $blades;
	}

    /**
	 * @return int
	 */
	public function getMaxId()
	{
		$this->sysLog->debug();
		$sql = "select max(id) as maxId
                from   " . $this->tableName . ";";
		$row = $this->sqlQueryRow($sql);
		return $row->maxId;
	}

	/**
	 * @param $bladeId
	 * @return int
	 */
	public function getVmCount($bladeId)
	{
		$this->sysLog->debug();
		$sql = "select count(*) as numRows
		        from vm
		        where bladeId = " . $bladeId . ";";
		$row = $this->sqlQueryRow($sql);
		return $row->numRows;
	}

	/**
	 * @param $bladeId
	 * @return int
	 */
	public function getTotalVmMemory($bladeId)
	{
		$this->sysLog->debug();
		$sql = "select sum(memorySize) as totalMemory
		        from vm
		        where bladeId = " . $bladeId . ";";
		$row = $this->sqlQueryRow($sql);
		return $row->totalMemory;
	}

	/**
	 * @param $bladeId
	 * @return int
	 */
	public function getWwnCount($bladeId)
	{
		$this->sysLog->debug();
		$sql = "select count(*) as numRows
		        from blade_wwn
		        where bladeId = " . $bladeId . ";";
		$row = $this->sqlQueryRow($sql);
		return $row->numRows;
	}

	/**
	 * @param string $orderBy
	 * @param string $dir
	 * @return HPSIMBlade[]
	 */
	public function getInventory($orderBy = "deviceName", $dir = "asc")
	{
		$this->sysLog->debug();
		$sql    = "select {$this->getQueryColumnsStr()}
                   from   {$this->tableName}
		           where  isInventory = 1
		           order by " . $orderBy . " " . $dir . ";";
		$result = $this->sqlQuery($sql);
		$blades = array();
		for ($i = 0; $i < count($result); $i++) {
			$blades[] = $this->_set($result[$i]);
		}
		return $blades;
	}

    /**
     * @param int $chassisId
   	 * @param string $orderBy
   	 * @param string $dir
   	 * @return HPSIMBlade[]
   	 */
   	public function getInventoryByChassisId($chassisId, $orderBy = "deviceName", $dir = "asc")
   	{
   		$this->sysLog->debug();
   		$sql    = "select {$this->getQueryColumnsStr()}
                   from   {$this->tableName}
   		           where  chassisId = {$chassisId}
   		             and  isInventory = 1
   		           order by " . $orderBy . " " . $dir . ";";
   		$result = $this->sqlQuery($sql);
   		$blades = array();
   		for ($i = 0; $i < count($result); $i++) {
   			$blades[] = $this->_set($result[$i]);
   		}
   		return $blades;
   	}

	// *******************************************************************************
	// CRUD methods
	// *******************************************************************************

    /**
     * @param HPSIMBlade $o
     * @param string $sql
     * @return HPSIMBlade
     */
    public function create($o, $sql="")
	{
		$this->sysLog->debug();
		parent::create($o);
		return $o;
	}

    /**
     * @param HPSIMBlade $o
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
     * @param HPSIMBlade $o
     * @param string $idColumn
     * @param string $sql
     * @return mixed
     */
    public function delete($o, $idColumn = "id", $sql = "")
	{
		$this->sysLog->debug();
		return parent::delete($o);
	}

	// *****************************************************************************
	// * Getters and Setters
	// *****************************************************************************

	/**
	 * @param int $logLevel
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

	private function _set($dbRowObj = null)
	{
		$this->sysLog->debug();

		$o = new HPSIMBlade();
		if ($dbRowObj) {
			foreach (self::$columnNames as $prop) {
                if (property_exists($dbRowObj, $prop)) {
                    $o->set($prop, $dbRowObj->$prop);
                }
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
