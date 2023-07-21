<?php

namespace STS\HPSIM;

use STS\DB\DBTable;

class HPSIMVMSnapshotTable extends DBTable
{
    protected $dbIndex = 'hpsim';
   	protected $tableName = 'vm_snapshot';
    protected $idAutoIncremented = true;

	protected static $columnNames = array(
        "dateStamp",
        "hostName",
        "fqdn",
        "vmType",
        "bladeId",
        "bladeFqdn",
        "distSwitchName",
        "chassisName",
        "inCmdb",
        "cmdbName",
        "sysId",
		"environment",
        "cmInstallStatus",
		"businessService",
        "subsystem"
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
   	 * @return HPSIMVMSnapshot[]
   	 */
    public function getByDate($date) {
        $this->sysLog->debug("date=" . $date);
        $sql   = "SELECT {$this->getQueryColumnsStr()}
                FROM   {$this->tableName}
             	WHERE  dateStamp = '{$date}'
       			ORDER BY distSwitchName, businessService, chassisName;";
        $rows  = $this->sqlQuery($sql);
        $array = array();
        for ($i = 0; $i < count($rows); $i++) {
            $array[] = $this->_set($rows[$i]);
        }
        return $array;
    }

    /**
   	 * @param $date
     * @param $type string vmware|xen
   	 * @return HPSIMVMSnapshot[]
   	 */
    public function getByDateAndType($date, $type='vmware') {
        $this->sysLog->debug("date=" . $date);
        $sql   = "SELECT {$this->getQueryColumnsStr()}
                  FROM   {$this->tableName}
             	  WHERE  dateStamp = '{$date}'
             	    AND  bladeFqdn like '%esx%'
       			  ORDER BY distSwitchName, businessService, chassisName;";
        $rows  = $this->sqlQuery($sql);
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
        $sql = "DELETE FROM   {$this->tableName}
                WHERE  dateStamp = '{$date}';";
        $this->sql($sql);
    }

	/**
	 * @param string $orderBy
	 * @param string $dir
	 * @return HPSIMVMSnapshot[]
	 */
	public function getAll($orderBy = "fullDnsName", $dir = "asc")
	{
		$this->sysLog->debug();
		$sql    = "select {$this->getQueryColumnsStr()}
                from   {$this->tableName}
		        order by " . $orderBy . " " . $dir . ";";
		$result = $this->sqlQuery($sql);
		$vms    = array();
		for ($i = 0; $i < count($result); $i++) {
			$vms[] = $this->_set($result[$i]);
		}
		return $vms;
	}

	// *******************************************************************************
	// CRUD methods
	// *******************************************************************************

    /**
     * @param HPSIMVMSnapshot $o
     * @param string $sql
     * @return HPSIMVMSnapshot
     */
    public function create($o, $sql="")
	{
		$this->sysLog->debug();
		$newId = parent::create($o);
        return $o;
	}

    /**
     * @param HPSIMVMSnapshot $o
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
     * @param HPSIMVMSnapshot $o
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
	 * @return HPSIMVMSnapshot
	 */
	private function _set($dbRowObj = null)
	{
		$this->sysLog->debug();

		$o = new HPSIMVMSnapshot();
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
