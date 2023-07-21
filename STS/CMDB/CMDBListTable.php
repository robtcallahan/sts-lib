<?php
/*******************************************************************************
 *
 * $Id: CMDBList.php 73209 2013-03-14 18:18:55Z rcallaha $
 * $Date: 2013-03-14 14:18:55 -0400 (Thu, 14 Mar 2013) $
 * $Author: rcallaha $
 * $Revision: 73209 $
 * $HeadURL: https://svn.ultradns.net/svn/sts_tools/sts-lib/trunk/STS/CMDB/CMDBList.php $
 *
 *******************************************************************************
 */

namespace STS\CMDB;

class CMDBListTable extends CMDBDAO
{
	protected static $nameMapping = array(
		'sys_id'   => 'sysId',
		'label'    => 'label',
		'value'    => 'value',
		'inactive' => 'inactive'
	);

	protected $ciTable;
	protected $listName;
	protected $tableName;

    /**
     * cmdb_ci_server install_status
     * [2] => On Order
     * [101] => Received
     * [105] => Staging
     * [130] => Validating
     * [110] => Live
     * [3] => In Maintenance
     * [117] => Decommissioning
     * [1501] => Disposed
     * [175] => Inventory
     * [1505] => Spare
     */
    /**
     * @param $listName
     * @param $tableName
     * @param mixed $arg
     */
    public function __construct($listName, $tableName, $arg=false)
	{
        $useUserCredentials = false;
        $config = null;
        if (is_bool($arg)) {
            $useUserCredentials = $arg;
            parent::__construct($useUserCredentials);
        }
        else if (is_array($arg)) {
            $config = $arg;
            parent::__construct($config);
        } else {
            parent::__construct($useUserCredentials);
        }
		$this->sysLog->debug();

		$this->ciTable   = "sys_choice";
		$this->listName  = $listName;
		$this->tableName = $tableName;
	}

	public function getArray()
	{
		$this->sysLog->debug();
		$query = "element={$this->listName}^name={$this->tableName}^inactive=false";
		return $this->getList($query, "value", "label");
	}

	public function getHash()
	{
		$this->sysLog->debug();
		$query = "element={$this->listName}^name={$this->tableName}^inactive=false";
		return $this->getList($query, "label", "value");
	}

	private function getList($query = null, $cmdbKey = null, $cmdbValue = null)
	{
		$this->sysLog->debug();

		// retrieve our query results
		$records = $this->getRecords($this->ciTable, $query);

		// clear our variable list and loop through our results
		$list = Array();

		// reset our array and iterate
		for ($i = 0; $i < count($records); $i++) {
			$rec = $records[$i];

			// don't attempt to add the item if it already exists
			if (!isset($list[$rec->$cmdbKey])) {
				// set our key field to the list key, value field to the value
				$list[$rec->$cmdbKey] = $rec->$cmdbValue;
			}
		}
		return $list;
	}


	// *******************************************************************************
	// * Getters and Setters
	// *****************************************************************************

    /**
     * @param $logLevel
     * @return $this|void
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
	 * @return array
	 */
	public static function getNameMapping()
	{
		return self::$nameMapping;
	}

}
