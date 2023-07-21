<?php

namespace STS\HPSIM;

use STS\DB\DBTable;

class HPSIMSnapshotListTable extends DBTable
{
    protected $dbIndex = 'hpsim';
   	protected $tableName = 'snapshot_list';
    protected $idAutoIncremented = true;

	protected static $columnNames = array("id", "dateStamp", "objectType");

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
	 * @return HPSIMSnapshotList
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
     * @param $date
     * @param $type
     * @return HPSIMSnapshotList
     */
	public function getByDateAndType($date, $type)
	{
		$this->sysLog->debug("date=" . $date . ", type=" . $type);
		$sql = "select {$this->getQueryColumnsStr()}
          		from   {$this->tableName}
          		where  dateStamp = '{$date}'
          		  and  objectType = '{$type}'
    			order by dateStamp desc;";
		return $this->_set($this->sqlQueryRow($sql));
	}

    /**
     * @param $date
     * @param $type
     */
   	public function deleteByDateAndType($date, $type)
   	{
   		$this->sysLog->debug("date=" . $date);
   		$sql = "delete from   {$this->tableName}
             	where  dateStamp = '{$date}'
             	  and  objectType = '{$type}';";
   		$this->sql($sql);
   	}

    /**
     * @param $type
     * @return HPSIMSnapshotList[]
     */
	public function getAllByType($type)
	{
		$this->sysLog->debug();
		$sql   = "select {$this->getQueryColumnsStr()}
          		  from   {$this->tableName}
          		  where  objectType = '{$type}'
    			  order by dateStamp desc;";
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
	 * @param HPSIMSnapshotList $o
     * @param string $sql
	 * @return HPSIMSnapshotList
	 */
	public function create($o, $sql="")
	{
		$this->sysLog->debug();
		$newId = parent::create($o);
		return $this->getById($newId);
	}

	/**
	 * @param HPSIMSnapshotList $o
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
	 * @param HPSIMSnapshotList $o
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
	 * @return void
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
	 * @return HPSIMSnapshotList
	 */
	private function _set($dbRowObj = null)
	{
		$this->sysLog->debug();

		$o = new HPSIMSnapshotList();
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
