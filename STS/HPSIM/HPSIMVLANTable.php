<?php
/*******************************************************************************
 *
 * $Id: HPSIMVLANTable.php 82761 2014-01-22 19:24:51Z rcallaha $
 * $Date: 2014-01-22 14:24:51 -0500 (Wed, 22 Jan 2014) $
 * $Author: rcallaha $
 * $Revision: 82761 $
 * $HeadURL: https://svn.ultradns.net/svn/sts_tools/sts-lib/trunk/STS/HPSIM/HPSIMVLANTable.php $
 *
 *******************************************************************************
 */

namespace STS\HPSIM;

use STS\DB\DBTable;


class HPSIMVLANTable extends DBTable
{
    protected $dbIndex = 'hpsim';
   	protected $tableName = 'vlan';
    protected $idAutoIncremented = true;

	protected static $columnNames = array(
		"id", "switchId", "name", "distSwitchName", "status", "sharedUplinkSet", "vlanId", "nativeVlan", "private", "preferredSpeed"
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
     * @param $id
     * @return HPSIMVLAN
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
     * @param $swId
     * @param $name
     * @return HPSIMVLAN
     */
    public function getBySwitchIdAndName($swId, $name)
	{
		$this->sysLog->debug("swId=" . $swId . ", name=" . $name);
        $sql = "select {$this->getQueryColumnsStr()}
                from   {$this->tableName}
		        where  switchId = " . $swId . "
		          and  name = '" . $name . "'
		        order by name;";
		$row =  $this->sqlQueryRow($sql);
		return $this->_set($row);
	}

    /**
     * @param $swId
     * @return HPSIMVLAN[]
     */
    public function getBySwitchId($swId)
	{
		$this->sysLog->debug("swId=" . $swId);
        $sql = "select {$this->getQueryColumnsStr()}
                from   {$this->tableName}
		        where  switchId = " . $swId . "
		        order by name;";
		$result =  $this->sqlQuery($sql);
		$objArray = array();
		for ($i = 0; $i < count($result); $i++) {
			$objArray[] = $this->_set($result[$i]);
		}
		return $objArray;
	}

    /**
     * @param $distSwitchName
     * @return mixed
     */
    public function getByDistSwitchName($distSwitchName)
	{
		$this->sysLog->debug("distSwitchName=" . $distSwitchName);

        $sql = "select distinct vlanId, name
                from   {$this->tableName}
		        where  distSwitchName = '" . $distSwitchName . "'
		        order by name;";
		return $this->sqlQuery($sql);
	}


	// *******************************************************************************
	// CRUD methods
	// *******************************************************************************

    /**
     * @param HPSIMVLAN $o
     * @param string $sql
     * @return HPSIMVLAN
     */
    public function create($o, $sql="")
	{
		$this->sysLog->debug();
		$newId = parent::create($o);
		return $this->getById($newId);
	}

    /**
     * @param HPSIMVLAN $o
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
     * @param HPSIMVLAN $o
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
     * @return HPSIMVLAN
     */
    private function _set($dbRowObj = null)
    {
	$this->sysLog->debug();

	$o = new HPSIMVLAN();
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

