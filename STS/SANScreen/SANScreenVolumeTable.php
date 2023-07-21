<?php
/*******************************************************************************
 *
 * $Id: SANScreenVolumeTable.php 82460 2014-01-04 15:38:17Z rcallaha $
 * $Date: 2014-01-04 10:38:17 -0500 (Sat, 04 Jan 2014) $
 * $Author: rcallaha $
 * $Revision: 82460 $
 * $HeadURL: https://svn.ultradns.net/svn/sts_tools/sts-lib/trunk/STS/SANScreen/SANScreenVolumeTable.php $
 *
 *******************************************************************************
 */

namespace STS\SANScreen;

use STS\DB\DBTable;

class SANScreenVolumeTable extends DBTable
{
    protected $dbIndex = 'sanscreen';
   	protected $tableName = 'volume';
    protected $idAutoIncremented = false;

	protected static $columnNames = array(
		"id", "storageID", "name", "label", "type", "diskType", "diskSize", "diskSpeed",
		"redundancy", "virtual", "capacityGB", "rawCapacityGB", "consumedCapacityGB",
		"startTime", "endTime"
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
	 * @return SANScreenVolume
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
	 * @param $hostId
	 * @return SANScreenVolume[]
	 */
	public function getByHostId($hostId)
	{
		$this->sysLog->debug("hostId" . $hostId);
		$sql   = "select distinct
                       v.id, v.storageID, v.name, v.label, v.type, v.diskType, v.diskSize, v.diskSpeed,
                       v.redundancy, v.virtual, v.capacityGB, v.rawCapacityGB, v.consumedCapacityGB,
                       v.startTime, v.endTime
                from   array a,
                       path p,
                       host h
		        where  h.id = {$hostId}
		          and  p.arrayId = a.id
		          and  h.id = p.hostId
		        order by h.name asc;";
		$rows  = $this->sqlQuery($sql);
		$array = array();
		for ($i = 0; $i < count($rows); $i++) {
			$array[] = $this->_set($rows[$i]);
		}
		return $array;
	}

	/**
	 * @param $arrayId
	 * @return SANScreenVolume[]
	 */
	public function getByArrayId($arrayId)
	{
		$this->sysLog->debug("arrayId" . $arrayId);
		$sql   = "select    distinct
                          v.id, v.storageID, v.name, v.label, v.type, v.diskType, v.diskSize, v.diskSpeed,
                          v.redundancy, v.virtual, v.capacityGB, v.rawCapacityGB, v.consumedCapacityGB,
                          v.startTime, v.endTime
                from      path p,
                          volume v
                where     p.volumeId = v.id
                  and     arrayId = {$arrayId}
                order by  v.name;";
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
	 * @param SANScreenVolume $o
     * @param string $sql
	 * @return SANScreenVolume
	 */
    public function create($o, $sql = "") {
   		$this->sysLog->debug();
   		$newId = parent::create($o, $sql);
   		return $this->getById($newId);
   	}

	/**
	 * @param SANScreenVolume $o
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
	 * @param SANScreenVolume $o
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
	 * @return SANScreenVolume
	 */
	private function _set($dbRowObj = null)
	{
		$this->sysLog->debug();

		$o = new SANScreenVolume();
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
