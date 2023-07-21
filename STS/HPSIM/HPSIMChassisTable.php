<?php
/*******************************************************************************
 *
 * $Id: HPSIMChassisTable.php 81815 2013-12-09 20:00:43Z rcallaha $
 * $Date: 2013-12-09 15:00:43 -0500 (Mon, 09 Dec 2013) $
 * $Author: rcallaha $
 * $Revision: 81815 $
 * $HeadURL: https://svn.ultradns.net/svn/sts_tools/sts-lib/trunk/STS/HPSIM/HPSIMChassisTable.php $
 *
 *******************************************************************************
 */

namespace STS\HPSIM;

use STS\DB\DBTable;

use STS\CMDB\CMDBServer;
use STS\CMDB\CMDBSubsystem;

use STS\SNCache;

use Zend\Json\Server\Exception\ErrorException;

class HPSIMChassisTable extends DBTable
{
	protected $dbIndex = 'hpsim';
	protected $tableName = 'chassis';
    protected $idAutoIncremented = false;

	// array of our object properties in the same order as xmlAttributes. provides the translation from one to the other
	protected static $columnNames = array(
		"id", "deviceName", "fullDnsName", "hwStatus", "mpStatus", "deviceType", "productName",
		"assocDeviceName", "assocDeviceType", "assocDeviceKey", "assocType",
		"distSwitchName", "sysId", "environment", "cmInstallStatus", "businessService",
		"subsystem", "opsSuppMgr", "opsSuppGrp", "comments", "shortDescr"
	);

	// these are the CMDB keys mapped to local db column names
	protected static $cmdbServerColumns = array(
		"sysId", "name", "environment", "installStatus",
		"businessServices", "subsystemList",
		"comments", "shortDescription"
	);

	protected static $simServerColumns = array(
		"sysId", "fullDnsName", "environment", "cmInstallStatus",
		"businessService", "subsystem",
		"comments", "shortDescr"
	);

	protected static $cmdbSubsysColumns = array("owningSupportManager", "operationsSupportGroup");

	protected static $simSubsysColumns = array("opsSuppMgr", "opsSuppGrp");

	// these are names of the XML attributes returned from mxquery and mxreport. Need to be able to translate between these names
	// and our object properties
	protected static $xmlAttributes = array(
		"DeviceKey", "DeviceName", "FullDNSName", "HWStatus", "MPStatus", "DeviceType", "ProductName",
		"AssociatedDeviceName", "AssociatedDeviceType", "AssociatedDeviceKey", "AssociationType"
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
	 * @return null|HPSIMChassis
	 */
	public static function simToChassis($hash)
	{
		if ($hash["AssociationType"] != "EnclosureToRack") {
			return null;
		}

		$newMe = new HPSIMChassis();

		foreach ($hash as $k => $v) {
			$key = array_search($k, self::$xmlAttributes);
			if ($key !== false) {
				$newMe->set(HPSIMChassisTable::$columnNames[$key], $v);
			}
		}
		return $newMe;
	}

	/**
	 * @param HPSIMChassis $c
	 * @param CMDBServer | SNCache\Server   $s
	 * @return HPSIMChassis
	 */
	public static function mergeCmdbServer(HPSIMChassis $c, $s)
	{
		for ($i = 0; $i < count(self::$cmdbServerColumns); $i++) {
			$c->set(self::$simServerColumns[$i], $s->get(self::$cmdbServerColumns[$i]));
		}
		return $c;
	}

	/**
	 * @param HPSIMChassis  $c
	 * @param CMDBSubsystem $s
	 * @return HPSIMChassis
	 */
	public static function mergeCmdbSubsystem(HPSIMChassis $c, CMDBSubsystem $s)
	{
		for ($i = 0; $i < count(self::$cmdbSubsysColumns); $i++) {
			$c->set(self::$simSubsysColumns[$i], $s->get(self::$cmdbSubsysColumns[$i]));
		}
		return $c;
	}

	/**
	 * @param $id
	 * @return HPSIMChassis
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
     * @param $sysId
     * @return HPSIMChassis
     */
    public function getBySysId($sysId)
   	{
   		$this->sysLog->debug("sysId=" . $sysId);
   		$sql = "select {$this->getQueryColumnsStr()}
                from   {$this->tableName}
   		        where  sysId = '" . $sysId . "';";
   		$row = $this->sqlQueryRow($sql);
   		return $this->_set($row);
   	}

	/**
	 * @param $deviceName
	 * @return HPSIMChassis
	 */
	public function getByDeviceName($deviceName)
	{
		$this->sysLog->debug("deviceName=" . $deviceName);
		$sql    = "select {$this->getQueryColumnsStr()}
                   from   {$this->tableName}
		           where  deviceName = '" . $deviceName . "';";
		$result = $this->sqlQueryRow($sql);
		return $this->_set($result);
	}

	/**
	 * @param $fqdn
	 * @return HPSIMChassis
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
	 * @param string $orderBy
	 * @param string $dir
	 * @return HPSIMChassis[]
	 */
	public function getAll($orderBy = "deviceName", $dir = "asc")
	{
		$this->sysLog->debug("deviceName=" . $orderBy);
		$sql     = "select {$this->getQueryColumnsStr()}
                    from   {$this->tableName}
		            order by " . $orderBy . " " . $dir . ";";
		$result  = $this->sqlQuery($sql);
		$chassis = array();
		for ($i = 0; $i < count($result); $i++) {
			$chassis[] = $this->_set($result[$i]);
		}
		return $chassis;
	}

	/**
	 * @return mixed
	 */
	public function getNetworks()
	{
		$this->sysLog->debug();
		$sql = "select distinct
                       case when distSwitchName is not null
                            then distSwitchName
                            else 'Unassigned'
                       end as distSwitchName
                from   " . $this->tableName . "
                order by distSwitchName";
		return $this->sqlQuery($sql);
	}

	/**
	 * @param $swName
	 * @return HPSIMChassis[]
	 */
	public function getBySwitchName($swName)
	{
		$this->sysLog->debug("swName=" . $swName);
		$sql = "select {$this->getQueryColumnsStr()}
                from   " . $this->tableName . "\n";
		if ($swName == "Unassigned") {
			$sql .= "where distSwitchName is null\n";
		}
		else {
			$sql .= "where  distSwitchName = '{$swName}'\n";
		}
		$sql .= "order by deviceName;";
		$result  = $this->sqlQuery($sql);
		$chassis = array();
		for ($i = 0; $i < count($result); $i++) {
			$chassis[] = $this->_set($result[$i]);
		}
		return $chassis;
	}

	/**
	 * @param $fqdn
	 * @return HPSIMChassis
	 */
	public function getByMmFqdn($fqdn)
	{
		$this->sysLog->debug("fqdn=" . $fqdn);
		$sql    = "select {$this->getQueryColumnsStr()}
                from   {$this->tableName}
		        where  id in (select chassisId from mgmt_processor where fullDnsName = '" . $fqdn . "');";
		$result = $this->sqlQueryRow($sql);
		return $this->_set($result);
	}

	/**
	 * @param      $id
	 * @param bool $on
	 * @return int
	 */
	public function getFullHeightBladeCount($id, $on = false)
	{
		$this->sysLog->debug();
		$sql = "select count(*) as bladeCount
		        from   blade
		        where  chassisId = " . $id . "
		          and  productName like '%BL68%G5%' ";
		if ($on) {
			$sql .= "and powerStatus = 'On'";
		}

		$row = $this->sqlQueryRow($sql);
		if ($row) {
			return $row->bladeCount;
		}
		return 0;
	}

	/**
	 * @param      $id
	 * @param bool $on
	 * @return int
	 */
	public function getHalfHeightBladeCount($id, $on = false)
	{
		$this->sysLog->debug();
		$sql = "select count(*) as bladeCount
		        from   blade
		        where  chassisId = " . $id . "
		          and  productName not like '%BL68%G5%' ";
		if ($on) {
			$sql .= "and powerStatus = 'On'";
		}

		$row = $this->sqlQueryRow($sql);
		if ($row) {
			return $row->bladeCount;
		}
		return 0;
	}

	/**
	 * @param $id
	 * @return int
	 */
	public function getWwnCount($id)
	{
		$this->sysLog->debug();
		$sql = "select count(*) as numRows
		        from blade_wwn
		        where bladeId = " . $id . ";";
		$row = $this->sqlQueryRow($sql);
		return $row->numRows;
	}

	/**
	 * @param $id
	 * @return mixed
	 */
	public function getLobsById($id)
	{
		$this->sysLog->debug();
		// the union includes those for all VMs on all the blades in the chassis as well
		$sql = "select distinct
		               businessService, subsystem, opsSuppMgr, opsSuppGrp
		        from   blade
		        where  chassisId = {$id}
		        union
		        select distinct
		               vm.businessService, vm.subsystem, vm.opsSuppMgr, vm.opsSuppGrp
		        from   blade b, vm
		        where  b.chassisId = " . $id . "
		          and  b.id = vm.bladeId
		        order by businessService, subsystem asc;";
		return $this->sqlQuery($sql);
	}

	// *******************************************************************************
	// CRUD methods
	// *******************************************************************************

    /**
     * @param HPSIMChassis $o
     * @param string $sql
     * @return HPSIMChassis
     */
    public function create($o, $sql="")
	{
		$this->sysLog->debug();
		$newId = parent::create($o);
		return $this->getById($newId);
	}

    /**
     * @param HPSIMChassis $o
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
     * @param HPSIMChassis $o
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
	// * Getters and Setters
	// *****************************************************************************

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
     * @return HPSIMChassis
     */
    private function _set($dbRowObj = null)
	{
		$this->sysLog->debug();

		$o = new HPSIMChassis();
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
