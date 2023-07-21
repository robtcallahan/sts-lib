<?php
/*******************************************************************************
 *
 * $Id: CMDBTaskListTable.php 81814 2013-12-09 19:40:39Z rcallaha $
 * $Date: 2013-12-09 14:40:39 -0500 (Mon, 09 Dec 2013) $
 * $Author: rcallaha $
 * $Revision: 81814 $
 * $HeadURL: https://svn.ultradns.net/svn/sts_tools/sts-lib/trunk/STS/CMDB/CMDBTaskListTable.php $
 *
 *******************************************************************************
 */

namespace STS\CMDB;


class CMDBTaskListTable extends CMDBDAO
{
	protected static $nameMapping = array(
		'sys_id'              => 'sysId',
		'sys_class_name'      => 'sysClassName',
		'number'              => 'number',
		'short_description'   => 'shortDescription',
		'active'              => 'active',
		'state'               => 'stateId',
		'dv_state'            => 'state',
		'approval'            => 'approval',
		'assignment_group'    => 'assignmentGroupId',
		'dv_assignment_group' => 'assignmentGroup',
		'u_action_taken'      => 'actionTaken',
		'u_category'          => 'category',
		'request_item'        => 'requestItemId',
		'dv_request_item'     => 'requestItem',
		'opened_at'           => 'openedAt',
		'opened_by'           => 'openedById',
		'dv_opened_by'        => 'openedBy',
		'due_date'            => 'dueDate',
		'work_end'            => 'workEnd',
		'closed_at'           => 'closedAt',
		'closed_by'           => 'closedById',
		'dv_closed_by'        => 'closedBy',
		'dv_work_notes'       => 'workNotes',
		'sys_created_by'      => 'sysCreatedBy',
		'sys_created_on'      => 'sysCreatedOn',
		'sys_updated_by'      => 'sysUpdatedBy',
		'sys_updated_on'      => 'sysUpdatedOn',
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
		$this->ciTable = 'sc_task_list';
		$this->format  = 'JSON';
	}

	/**
	 * @param      $sysId
	 * @param bool $raw
	 * @return mixed|CMDBTaskList
	 */
	public function getById($sysId, $raw = false)
	{
		$this->sysLog->debug("sysId=" . $sysId);
		return $this->getBySysId($sysId, $raw);
	}

	/**
	 * @param      $sysId
	 * @param bool $raw
	 * @return mixed|CMDBTaskList
	 */
	public function getBySysId($sysId, $raw = false)
	{
		$this->sysLog->debug("sysId=" . $sysId);
		$query  = "sys_id={$sysId}";
		$result = $this->getRecord($this->ciTable, $query);
		if (!$raw) {
			return $this->_set($result);
		}
		else {
			return $result;
		}
	}

	/**
	 * @param $num
	 * @return CMDBTaskList
	 */
	public function getByNumber($num)
	{
		$query  = "number={$num}";
		$result = $this->getRecord($this->ciTable, $query);
		return $this->_set($result);
	}

	/**
	 * @param CMDBUser
	 * @return CMDBTaskList[]
	 */
	public function getCoreHostingTasks(CMDBUser $user = null)
	{
		$groupTable = new CMDBGroupTable();
		$coreHosting = $groupTable->getByName('Core Hosting');
		$coreHostingIcs = $groupTable->getByName('Core Hosting ICS');

		// request_item.cat_item!=2cc23d9521a110008e11f6e166e5d984 = 'Access Request (Internal Users - Servers, DBs, Apps, Facility)'
		// I forget how I looked that up.
		$query = "active=true^assignment_group=" . $coreHosting->getSysId() . "^ORassignment_group=" . $coreHostingIcs->getSysId();
		if ($user && $user->getSysId()) {
			$query .= "^ORopened_by=" . $user->getSysId();
		}
		$query .= "^request_item.cat_item.nameNOT%20LIKEAccess%20Termination^request_item.cat_item!=2cc23d9521a110008e11f6e166e5d984^ORDERBYnumber";

		$records = $this->getRecords($this->ciTable, $query);
		$objects = array();
		for ($i = 0; $i < count($records); $i++) {
			$objects[] = $this->_set($records[$i]);
		}
		return $objects;
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
     * @return CMDBTaskList
     */
    private function _set($dbRowObj = null)
	{
		$this->sysLog->debug();

		$o = new CMDBTaskList();
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
