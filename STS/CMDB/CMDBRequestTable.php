<?php
/*******************************************************************************
 *
 * $Id: CMDBRequest.php 73209 2013-03-14 18:18:55Z rcallaha $
 * $Date: 2013-03-14 14:18:55 -0400 (Thu, 14 Mar 2013) $
 * $Author: rcallaha $
 * $Revision: 73209 $
 * $HeadURL: https://svn.ultradns.net/svn/sts_tools/sts-lib/trunk/STS/CMDB/CMDBRequest.php $
 *
 *******************************************************************************
 */

namespace STS\CMDB;

class CMDBRequestTable extends CMDBDAO
{
	protected static $nameMapping = array(
		'sys_id'                => 'sysId',
		'sys_class_name'        => 'sysClassName',
		'number'                => 'number',

		'u_request_category'    => 'requestCategory',
		'u_request_subcategory' => 'requestSubcategory',
        'dv_requested_for'      => 'requestedFor',
		'requested_for'         => 'requestedForId',

		'active'                => 'active',
		'approval'              => 'approval',
		'request_state'         => 'requestState',

		'opened_at'             => 'openedAt',
		'opened_by'             => 'openedBy',
		'dv_opened_by'          => 'openedById',
		'due_date'              => 'dueDate',
		'closed_at'             => 'closedAt',

		"sys_created_by"        => "sysCreatedBy",
		"sys_created_on"        => "sysCreatedOn",
		"sys_updated_by"        => "sysUpdatedBy",
		"sys_updated_on"        => "sysUpdatedOn",
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
		$this->ciTable = "sc_request";
		$this->format  = "JSON";
	}

	public function getByNumber($num, $raw = false)
	{
		$this->sysLog->debug("num=" . $num);
		$query  = "number={$num}";
		$result = $this->getRecord($this->ciTable, $query);
		if (!$raw) {
			return $this->_set($result);
		}
		else {
			return $result;
		}
	}

	public function getById($id, $raw = false)
	{
		$this->sysLog->debug("id=" . $id);
		$query  = "sys_id={$id}";
		$result = $this->getRecord($this->ciTable, $query);
		if (!$raw) {
			return $this->_set($result);
		}
		else {
			return $result;
		}
	}

	public function getOpenAccTerms()
	{
		$query   = "u_request_category=Access+Management^u_request_subcategory=Access+Termination^active=true^approval=approved";
		$records = $this->getRecords($this->ciTable, $query);
		$objects = array();
		for ($i = 0; $i < count($records); $i++) {
			$objects[] = $this->_set($records[$i]);
		}
		return $objects;
	}


	// *******************************************************************************
	// * Getters and Setters
	// *****************************************************************************

	/**
	 * @param int $logLevel
	 * @return void
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
	 * @param $format
	 */
	public function setFormat($format)
	{
		$this->format = $format;
	}

	/**
	 * @return string
	 */
	public function getFormat()
	{
		return $this->format;
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
	 * @return CMDBRequest
	 */
	private function _set($dbRowObj = null)
	{
		$this->sysLog->debug();

		$o = new CMDBRequest();
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
