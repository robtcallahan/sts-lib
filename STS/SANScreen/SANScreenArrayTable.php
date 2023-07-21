<?php
/*******************************************************************************
 *
 * $Id: SANScreenArrayTable.php 82460 2014-01-04 15:38:17Z rcallaha $
 * $Date: 2014-01-04 10:38:17 -0500 (Sat, 04 Jan 2014) $
 * $Author: rcallaha $
 * $Revision: 82460 $
 * $HeadURL: https://svn.ultradns.net/svn/sts_tools/sts-lib/trunk/STS/SANScreen/SANScreenArrayTable.php $
 *
 *******************************************************************************
 */

namespace STS\SANScreen;

use STS\DB\DBTable;

class SANScreenArrayTable extends DBTable
{
    const     STERLING_FIRST = 1;
    const     PLAIN_SORT = 2;

    protected $dbIndex = 'sanscreen';
   	protected $tableName = 'array';
    protected $idAutoIncremented = false;

	protected static $columnNames = array(
		"id",
        "name",
        "sanName",
        "tier",
        "status",
        "objectType",
        "vendor",
        "model",
        "serialNumber",
        "capacityGB",
		"rawCapacityGB",
        "microcodeVersion",
        "ip",
        "dead",
        "startTime",
        "endTime"
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
	 * @return SANScreenArray
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
	 * @param $name
	 * @return SANScreenArray
	 */
	public function getByName($name)
	{
		$this->sysLog->debug("name=" . $name);
		$sql = "select {$this->getQueryColumnsStr()}
                from   {$this->tableName}
		        where  name = '" . $name . "';";
		$row = $this->sqlQueryRow($sql);
		return $this->_set($row);
	}

    /**
   	 * @param $sn
   	 * @return SANScreenArray
   	 */
   	public function getBySerialNumber($sn)
   	{
   		$this->sysLog->debug("serialNumber=" . $sn);
   		$sql = "select {$this->getQueryColumnsStr()}
                from   {$this->tableName}
   		        where  serialNumber = '" . $sn . "';";
   		$row = $this->sqlQueryRow($sql);
   		return $this->_set($row);
   	}

    /**
     * @param int $sortType
     * @return mixed
     */
	public function getSans($sortType = self::STERLING_FIRST)
	{
		$this->sysLog->debug();
		$sql   = "select distinct sanName
                  from   {$this->tableName}
	              order by sanName asc;";
		$rows = $this->sqlQuery($sql);
        $result = array();
        if ($sortType == self::PLAIN_SORT) {
            foreach ($rows as $row) {
                $result[] = $row->sanName;
            }
            return $result;
        }

        $stArray = array();
        $chArray = array();
        foreach ($rows as $row) {
            if (preg_match("/^CH/", $row->sanName)) {
                $chArray[] = $row->sanName;
            } else {
                $stArray[] = $row->sanName;
            }
        }
        return array_merge($stArray, $chArray);
	}

    /**
     * @param string $sanName
   	 * @return mixed
   	 */
   	public function getTiersBySanName($sanName)
   	{
   		$this->sysLog->debug();
   		$sql   = "select tier
                  from   {$this->tableName}
                  where  sanName = '" . $sanName . "'
                  group by tier
   		          order by tier asc;";
   		$rows  = $this->sqlQuery($sql);
   		$results = array();
   		foreach ($rows as $r) {
            $results[] = $r->tier;
   		}
   		return $results;
   	}

    /**
     * @param string $san
     * @param string $tier
   	 * @param string $orderBy
   	 * @param string $dir
   	 * @return SANScreenArray[]
   	 */
   	public function getBySanAndTier($san, $tier, $orderBy = "name", $dir = "asc")
   	{
   		$this->sysLog->debug();
   		$sql   = "select {$this->getQueryColumnsStr()}
                  from   {$this->tableName}
                  where  sanName = '" . $san . "'
                    and  tier = '" . $tier . "'
   		          order by " . $orderBy . " " . $dir . ";";
   		$rows  = $this->sqlQuery($sql);
        $results = array();
        foreach ($rows as $r) {
            $results[] = $this->_set($r);
   		}
   		return $results;
   	}

    /**
   	 * @param string $orderBy
   	 * @param string $dir
   	 * @return SANScreenArray[]
   	 */
   	public function getAll($orderBy = "name", $dir = "asc")
   	{
   		$this->sysLog->debug();
   		$sql   = "select {$this->getQueryColumnsStr()}
                   from   {$this->tableName}
   		        order by " . $orderBy . " " . $dir . ";";
   		$rows  = $this->sqlQuery($sql);
   		$array = array();
   		for ($i = 0; $i < count($rows); $i++) {
   			$array[] = $this->_set($rows[$i]);
   		}
   		return $array;
   	}

    /**
   	 * @return SANScreenArray[]
   	 */
   	public function getAllOrderBySan()
   	{
   		$this->sysLog->debug();
   		$sql   = "select {$this->getQueryColumnsStr()}
                   from   {$this->tableName}
   		        order by sanName, tier, name;";
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
	 * @param SANScreenArray $o
     * @param string $sql
	 * @return SANScreenArray
	 */
    public function create($o, $sql = "") {
   		$this->sysLog->debug();
   		$newId = parent::create($o, $sql);
   		return $this->getById($newId);
   	}

	/**
	 * @param SANScreenArray $o
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
	 * @param SANScreenArray $o
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
	 * @return SANScreenArray
	 */
	private function _set($dbRowObj = null)
	{
		$this->sysLog->debug();

		$o = new SANScreenArray();
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
