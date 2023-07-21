<?php

namespace STS\SANScreen;

use STS\DB\DBTable;

class SANScreenArraySnapshotTable extends DBTable
{
    protected $dbIndex = 'sanscreen';
   	protected $tableName = 'array_snapshot';
    protected $idAutoIncremented = true;

	protected static $columnNames = array(
		"id",
        "dateStamp",
        "arrayName",
        "sanName",
        "tier",
        "rawTb",
        "useableTb",
        "provisionedTb",
        "availableTb"
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
	 * @return SANScreenArraySnapshot
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
	 * @return SANScreenArraySnapshot[]
	 */
	public function getByDate($date) {
		$this->sysLog->debug("date=" . $date);
		$sql = "select {$this->getQueryColumnsStr()}
          		from   {$this->tableName}
          		where  dateStamp = '{$date}'
    			order by sanName, tier, arrayName;";
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
	 * @param $array
	 * @return SANScreenArraySnapshot
	 */
	public function getByDateAndArray($date, $array) {
		$this->sysLog->debug("date=" . $date . ", array=" . $array);
		$sql = "select {$this->getQueryColumnsStr()}
                from   {$this->tableName}
		        where  dateStamp = '{$date}'
		          and  arrayName = '{$array}';";
		$row = $this->sqlQueryRow($sql);
		return $this->_set($row);
	}


	// *******************************************************************************
	// CRUD methods
	// *******************************************************************************

    /**
     * @param SANScreenArraySnapshot $o
     * @param string $sql
     * @return SANScreenArraySnapshot
     */
	public function create($o, $sql = "") {
		$this->sysLog->debug();
		$newId = parent::create($o, $sql);
		return $this->getById($newId);
	}

    /**
     * @param SANScreenArraySnapshot $o
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
	 * @param SANScreenArraySnapshot $o
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
	 * @return SANScreenArraySnapshot
	 */
	private function _set($dbRowObj = null) {
		$this->sysLog->debug();

		$o = new SANScreenArraySnapshot();
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
