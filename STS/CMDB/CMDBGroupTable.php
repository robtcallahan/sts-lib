<?php
/*******************************************************************************
 *
 * $Id: CMDBGroupTable.php 81814 2013-12-09 19:40:39Z rcallaha $
 * $Date: 2013-12-09 14:40:39 -0500 (Mon, 09 Dec 2013) $
 * $Author: rcallaha $
 * $Revision: 81814 $
 * $HeadURL: https://svn.ultradns.net/svn/sts_tools/sts-lib/trunk/STS/CMDB/CMDBGroupTable.php $
 *
 *******************************************************************************
 */

namespace STS\CMDB;

class CMDBGroupTable extends CMDBDAO
{
	protected static $nameMapping = array(
		"sys_id"         => "sysId",
		"name"           => "name",
		"active"         => "active",
		"manager"        => "managerId",
		"dv_manager"     => "manager",
		"email"          => "email",
		"sys_created_by" => "sysCreatedBy",
		"sys_created_on" => "sysCreatedOn",
		"sys_updated_by" => "sysUpdatedBy",
		"sys_updated_on" => "sysUpdatedOn",
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
		$this->ciTable = "sys_user_group_list";
		$this->format  = "JSON";
	}

	/**
	 * @param      $sysId
	 * @param bool $raw
	 * @return mixed|CMDBGroup
	 */
	public function getById($sysId, $raw = false)
	{
		$this->sysLog->debug("sysId=" . $sysId);
		return $this->getBySysId($sysId, $raw);
	}

	/**
	 * @param      $sysId
	 * @param bool $raw
	 * @return mixed|CMDBGroup
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
	 * @param $name
	 * @return CMDBGroup
	 */
	public function getByName($name)
	{
		$this->sysLog->debug("name=" . $name);
		$query  = "name=" . $name;
		$result = $this->getRecord($this->ciTable, $query);
		return $this->_set($result);
	}

	/**
	 * @param $name
	 * @return CMDBGroup[]
	 */
	public function getByNameLike($name)
	{
		$this->sysLog->debug("name=" . $name);
		$query   = "nameLIKE" . $name;
		$records = $this->getRecords($this->ciTable, $query);
		$objects = array();
		for ($i = 0; $i < count($records); $i++) {
			$objects[] = $this->_set($records[$i]);
		}
		return $objects;
	}

	/**
	 * @param      $query
	 * @param bool $raw
	 * @return CMDBGroup[]
	 */
	public function getByQueryString($query, $raw = false)
	{
		$this->sysLog->debug();
		$records = $this->getRecords($this->ciTable, $query);
		$objects = array();
		for ($i = 0; $i < count($records); $i++) {
			if (!$raw) {
				$objects[] = $this->_set($records[$i]);
			}
			else {
				$objects[] = $records[$i];
			}
		}
		return $objects;
	}

	/**
	 * @return CMDBGroup[]
	 */
	public function getAll()
	{
		$this->sysLog->debug();
		$query   = "";
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
	 * @return array
	 */
	public static function getNameMapping()
	{
		return self::$nameMapping;
	}

	/**
	 * @param null $dbRowObj
	 * @return CMDBGroup
	 */
	private function _set($dbRowObj = null)
	{
		$this->sysLog->debug();

		$o = new CMDBGroup();
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
