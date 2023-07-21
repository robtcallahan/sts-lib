<?php
/*******************************************************************************
 *
 * $Id: CMDBTaskCITable.php 81814 2013-12-09 19:40:39Z rcallaha $
 * $Date: 2013-12-09 14:40:39 -0500 (Mon, 09 Dec 2013) $
 * $Author: rcallaha $
 * $Revision: 81814 $
 * $HeadURL: https://svn.ultradns.net/svn/sts_tools/sts-lib/trunk/STS/CMDB/CMDBTaskCITable.php $
 *
 *******************************************************************************
 */

namespace STS\CMDB;



class CMDBTaskCITable extends CMDBDAO
{
	protected static $nameMapping = array(
		'sys_id'         => 'sysId',
		'task'           => 'taskId',
		'dv_task'        => 'task',
		'ci_item'        => 'ciItemId',
		'dv_ci_item'     => 'ciItem',
		'applied'        => 'applied',
		'sys_created_by' => 'sysCreatedBy',
		'sys_created_on' => 'sysCreatedOn',
		'sys_updated_by' => 'sysUpdatedBy',
		'sys_updated_on' => 'sysUpdatedOn',
	);

	protected $ciTable;
	protected $format;

    /**
     * @param mixed $arg
     */
    public function __construct($arg = null)
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

		// define CMDB table and return format
		$this->ciTable = "task_ci";
		$this->format  = "JSON";
	}

	/**
	 * @param $taskId
	 * @return CMDBTaskCI[]
	 */
	public function getByTaskId($taskId)
	{
		$this->sysLog->debug("taskId=" . $taskId);
		$query   = "task={$taskId}";
		$records = $this->getRecords($this->ciTable, $query);
		$objects = array();
		for ($i = 0; $i < count($records); $i++) {
			$objects[] = $this->_set($records[$i]);
		}
		return $objects;
	}

	/**
	 * @param $task
	 * @param $ciItem
	 * @return mixed|object
	 */
	public function create($task, $ciItem)
	{
		$this->sysLog->debug("task=" . $task . ", ciItem=" . $ciItem);
		$props   = array();
		$props[] = '"task":"' . $task . '"ci_item":"' . $ciItem . '"';
		$json    = '{' . implode(",", $props) . '}';
		return parent::createCI($this->ciTable, $json);
	}

	/**
	 * @param $json
	 * @return mixed|object
	 */
	public function createMultiple($json)
	{
		$this->sysLog->debug();
		return $this->createMultiple($this->ciTable, $json);
	}

	/**
	 * @param $sysId
	 * @param $task
	 * @param $ciItem
	 * @return mixed|object
	 */
	public function update($sysId, $task, $ciItem)
	{
		$this->sysLog->debug("sysId=" . $sysId . ", task=" . $task . ", ciItem=" . $ciItem);
		$props   = array();
		$props[] = '"task":"' . $task . '"ci_item":"' . $ciItem . '"';
		$json    = '{' . implode(",", $props) . '}';
		return parent::updateCI($this->ciTable, $sysId, $json);
	}

	/**
	 * @param $sysId
	 * @return mixed|object
	 */
	public function delete($sysId)
	{
		$this->sysLog->debug("sysId=" . $sysId);
		return parent::deleteCI($this->ciTable, $sysId);
	}

	/**
	 * @param $sysparmQuery
	 * @return mixed|object
	 */
	public function deleteMultiple($sysparmQuery)
	{
		$this->sysLog->debug("sysparmQuery=" . $sysparmQuery);
		return parent::deleteMultipleCIs($this->ciTable, $sysparmQuery);
	}

	/**
	 * @param $sysId
	 * @param $json
	 * @return mixed|object
	 */
	public function updateByJson($sysId, $json)
	{
		$this->sysLog->debug("sysId=" . $sysId);
		return parent::updateCI($this->ciTable, $sysId, $json);
	}

	// *******************************************************************************
	// * Getters and Setters
	// *****************************************************************************

	/**
	 * @param $logLevel
	 * @return void
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

	/**
	 * @param null $dbRowObj
	 * @return CMDBTaskCI
	 */
	private function _set($dbRowObj = null)
	{
		$this->sysLog->debug();

		$o = new CMDBTaskCI();
		foreach (self::$nameMapping as $cmdbProp => $modelProp) {
			if ($dbRowObj && property_exists($dbRowObj, $cmdbProp)) {
				$o->set($modelProp, $dbRowObj->$cmdbProp);
			}
			else {
				$o->set($modelProp, null);
			}
		}
		return $o;
	}
}
