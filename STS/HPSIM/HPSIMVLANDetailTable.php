<?php
/*******************************************************************************
 *
 * $Id: HPSIMVLANDetailTable.php 82972 2014-02-05 16:22:58Z rcallaha $
 * $Date: 2014-02-05 11:22:58 -0500 (Wed, 05 Feb 2014) $
 * $Author: rcallaha $
 * $Revision: 82972 $
 * $HeadURL: https://svn.ultradns.net/svn/sts_tools/sts-lib/trunk/STS/HPSIM/HPSIMVLANDetailTable.php $
 *
 *******************************************************************************
 */

namespace STS\HPSIM;

use STS\DB\DBTable;


class HPSIMVLANDetailTable extends DBTable
{
    protected $dbIndex = 'hpsim';
   	protected $tableName = 'vlan_detail';
    protected $idAutoIncremented = false;

	protected static $columnNames = array("id", "distSwitchName", "vlanId", "ipSubnet", "subnetMask", "gateway");

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
     * @param $vlanId
     * @param $distSwName
     * @return HPSIMVLANDetail
     */
	public function getByVlanIdAndDistSwitchName($vlanId, $distSwName)
	{
        // remove id and chassisId from query columns
        $colsArray = $this->getQueryColumns();
        array_shift($colsArray);
        array_shift($colsArray);

        $sql = "select distinct " . (implode(',', $colsArray)) . "
                from   {$this->tableName}
		        where  vlanId = '" . $vlanId . "' and distSwitchName='" . $distSwName . "';";
		$row = $this->sqlQueryRow($sql);
		return $this->_set($row);
	}

    /**
     * @param $distSwitchName
     * @return HPSIMVLANDetail[]
     */
   	public function getByDistSwitchName($distSwitchName)
   	{
   		$this->sysLog->debug();
   		$sql    = "select " . $this->getQueryColumnsStr() . "
                   from   " . $this->tableName . "
                   where distSwitchName = '" . $distSwitchName . "'
   		           order by distSwitchName asc, vlanId asc;";
   		$result = $this->sqlQuery($sql);
   		$array  = array();
   		for ($i = 0; $i < count($result); $i++) {
   			$array[] = $this->_set($result[$i]);
   		}
   		return $array;
   	}

	/**
	 * @return HPSIMVLANDetail[]
	 */
	public function getAll()
	{
		$this->sysLog->debug();
		$sql    = "select " . $this->getQueryColumnsStr() . "
                   from   " . $this->tableName . "
		           order by distSwitchName asc, vlanId asc;";
		$result = $this->sqlQuery($sql);
		$array  = array();
		for ($i = 0; $i < count($result); $i++) {
			$array[] = $this->_set($result[$i]);
		}
		return $array;
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
	 * @return HPSIMVLANDetail
	 */
	private function _set($dbRowObj = null)
	{
		$this->sysLog->debug();

		$o = new HPSIMVLANDetail();
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
