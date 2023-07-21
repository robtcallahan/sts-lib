<?php
/*******************************************************************************
 *
 * $Id: SANScreenStoragePoolTableTable.php 82460 2014-01-04 15:38:17Z rcallaha $
 * $Date: 2014-01-04 10:38:17 -0500 (Sat, 04 Jan 2014) $
 * $Author: rcallaha $
 * $Revision: 82460 $
 * $HeadURL: https://svn.ultradns.net/svn/sts_tools/sts-lib/trunk/STS/SANScreen/SANScreenStoragePoolTableTable.php $
 *
 *******************************************************************************
 */

namespace STS\SANScreen;

use STS\DB\DBTable;

class SANScreenStoragePoolTable extends DBTable
{
    protected $dbIndex = 'sanscreen';
   	protected $tableName = 'storage_pool';
    protected $idAutoIncremented = false;

	protected static $columnNames = array(
        "id",
        "dataAllocatedCapacityMB",
        "dataUsedCapacityMB",
        "dedupeSavings",
        "name",
        "otherAllocatedCapacityMB",
        "otherUsedCapacityMB",
        "physicalDiskCapacityMB",
        "rawToUsableRatio",
        "redundancy",
        "reservedCapacityMB",
        "snapshotAllocatedCapacityMB",
        "snapshotUsedCapacityMB",
        "status",
        "storageId",
        "totalAllocatedCapacityMB",
        "totalUsedCapacityMB",
        "type",
        "vendorTier",
        "autoTiering",
        "dedupeEnabled",
        "includeInDwhCapacity",
        "raidGroup",
        "thinProvisioningSupported",
        "usesSSDCache",
        "virtual"
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
	 * @return SANScreenStoragePool
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
	 * @return SANScreenStoragePool[]
	 */
	public function getByArrayId($arrayId)
	{
		$this->sysLog->debug("arrayId" . $arrayId);
        $sql = "select {$this->getQueryColumnsStr()}
                from   {$this->tableName}
      		    where  arrayId = {$arrayId};";
		$rows  = $this->sqlQuery($sql);
		$array = array();
		for ($i = 0; $i < count($rows); $i++) {
			$array[] = $this->_set($rows[$i]);
		}
		return $array;
	}

    /**
   	 * @param $arrayId
   	 * @return mixed
   	 */
   	public function getCapacityByArrayId($arrayId)
   	{
   		$this->sysLog->debug("arrayId" . $arrayId);
        $sql = "select sum(physicalDiskCapacityMB) / 1024 / 1024 as totalRawTb,
                       sum(totalAllocatedCapacityMB) / 1024 / 1024 as totalUseableTb,
                       sum(totalUsedCapacityMB) / 1024 / 1024 as totalProvisionedTb,
                       (sum(totalAllocatedCapacityMB) - sum(totalUsedCapacityMB)) / 1024 / 1024 as totalAvailableTb
                from   {$this->tableName}
                where  storageId = {$arrayId};";
   		$row = $this->sqlQueryRow($sql);
   		return $row;
   	}

	// *******************************************************************************
	// CRUD methods
	// *******************************************************************************

	/**
	 * @param SANScreenStoragePool $o
     * @param string $sql
	 * @return SANScreenStoragePool
	 */
    public function create($o, $sql = "") {
   		$this->sysLog->debug();
   		$newId = parent::create($o, $sql);
   		return $this->getById($newId);
   	}

	/**
	 * @param SANScreenStoragePool $o
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
	 * @param SANScreenStoragePool $o
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
	 * @return SANScreenStoragePool
	 */
	private function _set($dbRowObj = null)
	{
		$this->sysLog->debug();

		$o = new SANScreenStoragePool();
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
