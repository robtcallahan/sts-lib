<?php
/*******************************************************************************
 *
 * $Id: SANScreenSnapshotTable.php 82460 2014-01-04 15:38:17Z rcallaha $
 * $Date: 2014-01-04 10:38:17 -0500 (Sat, 04 Jan 2014) $
 * $Author: rcallaha $
 * $Revision: 82460 $
 * $HeadURL: https://svn.ultradns.net/svn/sts_tools/sts-lib/trunk/STS/SANScreen/SANScreenSnapshotTable.php $
 *
 *******************************************************************************
 */

namespace STS\SANScreen;

use STS\DB\DBTable;

class SANScreenHostSnapshotTable extends DBTable
{
    protected $dbIndex = 'sanscreen';
   	protected $tableName = 'host_snapshot';
    protected $idAutoIncremented = true;

	protected static $columnNames = array(
		"id",
        "dateStamp",
        "hostName",
        "allocatedGb",
        "arrayName",
        "sanName",
        "tier",
        "capacityGb",
		"businessService",
        "subsystem"
    );

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
	 * @return SANScreenHostSnapshot
	 */
	public function getById($id) {
		$this->sysLog->debug("id=" . $id);
		$sql = "select {$this->getQueryColumnsStr()}
                from   {$this->tableName}
		        where  id = {$id};";
		$row = $this->sqlQueryRow($sql);
		return $this->_set($row);
	}

	/**
	 * @param $date
	 * @return SANScreenHostSnapshot[]
	 */
	public function getByDate($date) {
		$this->sysLog->debug("date=" . $date);
		$sql = "select {$this->getQueryColumnsStr()}
          		from   {$this->tableName}
          		where  dateStamp = '{$date}'
    			order by hostName, arrayName;";
		$rows = $this->sqlQuery($sql);
		$array = array();
		for ($i = 0; $i < count($rows); $i++) {
			$array[] = $this->_set($rows[$i]);
		}
		return $array;
	}

    /**
   	 * @param $date
   	 */
   	public function deleteByDate($date) {
   		$this->sysLog->debug("date=" . $date);
   		$sql = "delete from   {$this->tableName}
                where  dateStamp = '{$date}';";
   		$this->sql($sql);
   	}

	/**
	 * @param $date
	 * @return SANScreenHostSnapshot[]
	 */
	public function getByDateAndGroupByBusinessServiceAndArray($date) {
		$this->sysLog->debug("date=" . $date);
		$sql = "select dateStamp, sanName, tier, businessService, arrayName, sum(allocatedGb) as allocatedGb
          		from   {$this->tableName}
          		where  dateStamp = '{$date}'
          		group by arrayName, businessService
    			order by sanName, tier, arrayName, businessService;";
		$rows = $this->sqlQuery($sql);
		$array = array();
        foreach ($rows as $row) {
			$array[] = $this->_set($row);
		}
		return $array;
	}

	/**
	 * @param $date
	 * @return SANScreenHostSnapshot[]
	 */
	public function getByDateAndGroupByBusinessService($date) {
		$this->sysLog->debug("date=" . $date);
		$sql = "select dateStamp, businessService, sum(allocatedGb) as allocatedGb
          		from   {$this->tableName}
          		where  dateStamp = '{$date}'
          		group by businessService, sum(allocatedGb)
    			order by businessService;";
		$rows = $this->sqlQuery($sql);
		$array = array();
		for ($i = 0; $i < count($rows); $i++) {
			$array[] = $this->_set($rows[$i]);
		}
		return $array;
	}

	/**
	 * @param $date
	 * @param $host
	 * @param $array
	 * @return SANScreenHostSnapshot
	 */
	public function getByDateAndHostAndArray($date, $host, $array) {
		$this->sysLog->debug("date=" . $date . ", host=" . $host . ", array=" . $array);
		$sql = "select {$this->getQueryColumnsStr()}
                from   {$this->tableName}
		        where  dateStamp = '{$date}'
		          and  hostName = '{$host}'
		          and  arrayName = '{$array}';";
		$row = $this->sqlQueryRow($sql);
		return $this->_set($row);
	}

	/**
	 * @param $date
	 * @param $arrayName
	 * @param $businessService
	 * @return SANScreenHostSnapshot
	 */
	public function getByDateAndArrayAndBusinessService($date, $arrayName, $businessService) {
		$this->sysLog->debug("date=" . $date . ", arrayName=" . $arrayName . ", businessService=" . $businessService);
		$sql = "select dateStamp, sanName, tier, arrayName, businessService, sum(allocatedGb) as allocatedGb
          		from   {$this->tableName}
          		where  dateStamp = '{$date}'
          		  and  arrayName = '{$arrayName}'
          		  and  businessService = '{$businessService}'
		        group by arrayName, businessService;";
		return $this->_set($this->sqlQueryRow($sql));
	}

    public function getReportBusinessServiceBySan($date) {
        $sql = "SELECT sanName, tier, businessService, sum(allocatedGb) as allocatedGb
                FROM   {$this->tableName}
                WHERE  dateStamp = '" . $date . "'
                group  by sanName, tier, businessService
                order  by sanName, tier, businessService";
        return $this->sqlQuery($sql);
    }


	// *******************************************************************************
	// CRUD methods
	// *******************************************************************************

	/**
	 * @param SANScreenHostSnapshot $o
     * @param string $sql
	 * @return SANScreenHostSnapshot
	 */
    public function create($o, $sql = "") {
   		$this->sysLog->debug();
   		$newId = parent::create($o, $sql);
   		return $this->getById($newId);
   	}

	/**
	 * @param SANScreenHostSnapshot $o
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
	 * @param SANScreenHostSnapshot $o
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
	public function setLogLevel($logLevel) {
		$this->logLevel = $logLevel;
	}

	/**
	 * @return int
	 */
	public function getLogLevel() {
		return $this->logLevel;
	}

	/**
	 * @param $columnNames
	 */
	public static function setColumnNames($columnNames) {
		self::$columnNames = $columnNames;
	}

	/**
	 * @return array
	 */
	public static function getColumnNames() {
		return self::$columnNames;
	}

	/**
	 * @param null $dbRowObj
	 * @return SANScreenHostSnapshot
	 */
	private function _set($dbRowObj = null) {
		$this->sysLog->debug();

		$o = new SANScreenHostSnapshot();
		if ($dbRowObj) {
			foreach (self::$columnNames as $prop) {
				if (property_exists($dbRowObj, $prop)) {
					$o->set($prop, $dbRowObj->$prop);
				}
			}
		} else {
			foreach (self::$columnNames as $prop) {
				$o->set($prop, null);
			}
		}
		return $o;
	}
}
