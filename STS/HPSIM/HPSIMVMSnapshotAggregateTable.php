<?php

namespace STS\HPSIM;

use STS\DB\DBTable;

class HPSIMVMSnapshotAggregateTable extends DBTable
{
    protected $dbIndex = 'hpsim';
   	protected $tableName = 'vm_snapshot_aggregate';
    protected $idAutoIncremented = true;

	protected static $columnNames = array(
        "id",
        "dateStamp",
        "distSwitchName",
        "businessService",
        "builds",
        "decoms",
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
   	 * @param $date
   	 * @return HPSIMVMSnapshotAggregate[]
   	 */
    public function getByDate($date) {
        $this->sysLog->debug("date=" . $date);
        $sql   = "SELECT {$this->getQueryColumnsStr()}
                  FROM   {$this->tableName}
             	  WHERE  dateStamp = '{$date}'
       			  ORDER BY distSwitchName, businessService;";
        $rows  = $this->sqlQuery($sql);
        $array = array();
        for ($i = 0; $i < count($rows); $i++) {
            $array[] = $this->_set($rows[$i]);
        }
        return $array;
    }

    /**
     * @param $date
     * @param $bs
     * @return HPSIMVMSnapshotAggregate
     */
    public function getByDateAndBusinessService($date, $bs) {
        $sql   = "SELECT {$this->getQueryColumnsStr()}
                  FROM   {$this->tableName}
             	  WHERE  dateStamp = '{$date}'
             	    AND  businessService = '{$bs}';";
        return $this->_set($this->sqlQueryRow($sql));
    }

    /**
     * @param $date
     */
    public function deleteByDate($date) {
        $this->sysLog->debug("date=" . $date);
        $sql = "DELETE FROM   {$this->tableName}
                   WHERE  dateStamp = '{$date}';";
        $this->sql($sql);
    }

    // *******************************************************************************
	// CRUD methods
	// *******************************************************************************

    /**
     * @param HPSIMVMSnapshotAggregate $o
     * @param string $sql
     * @return HPSIMVMSnapshotAggregate
     */
    public function create($o, $sql="")
	{
		$this->sysLog->debug();
		parent::create($o);
		return $o;
	}

    /**
     * @param HPSIMVMSnapshotAggregate $o
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
     * @param HPSIMVMSnapshotAggregate $o
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

    /**
     * @param null $dbRowObj
     * @return HPSIMVMSnapshotAggregate
     */
    private function _set($dbRowObj = null)
	{
		$this->sysLog->debug();

		$o = new HPSIMVMSnapshotAggregate();
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
