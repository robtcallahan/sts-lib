<?php
/*******************************************************************************
 *
 * $Id: CMDBRelatedImpactedServiceTable.php 81814 2013-12-09 19:40:39Z rcallaha $
 * $Date: 2013-12-09 14:40:39 -0500 (Mon, 09 Dec 2013) $
 * $Author: rcallaha $
 * $Revision: 81814 $
 * $HeadURL: https://svn.ultradns.net/svn/sts_tools/sts-lib/trunk/STS/CMDB/CMDBRelatedImpactedServiceTable.php $
 *
 *******************************************************************************
 */

namespace STS\CMDB;



class CMDBRelatedImpactedServiceTable extends CMDBDAO
{
	protected static $nameMapping = array(
		"sys_id"              => "sysId",
		"u_change"            => "changeId",
		"dv_u_change"         => "change",
		"u_cmdb_subsystem"    => "subsystemId",
		"dv_u_cmdb_subsystem" => "subsystem",
		"sys_created_by"      => "sysCreatedBy",
		"sys_created_on"      => "sysCreatedOn",
		"sys_updated_by"      => "sysUpdatedBy",
		"sys_updated_on"      => "sysUpdatedOn",
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
		$this->ciTable = "u_related_impacted_services";
		$this->format  = "JSON";
	}

	/**
	 * @param $crId
	 * @return CMDBRelatedImpactedService[]
	 */
	public function getByCRId($crId)
	{
		$this->sysLog->debug("crId=" . $crId);
		$query   = "u_change={$crId}";
		$records = $this->getRecords($this->ciTable, $query);
		$array   = array();
		for ($i = 0; $i < count($records); $i++) {
			$array[] = $this->_set($records[$i]);
		}
		return $array;
	}

	/**
	 * @param      $sysId
	 * @param bool $raw
	 * @return mixed|CMDBRelatedImpactedService
	 */
	public function getById($sysId, $raw = false)
	{
		$this->sysLog->debug("sysId=" . $sysId);
		return $this->getBySysId($sysId, $raw);
	}

	/**
	 * @param      $sysId
	 * @param bool $raw
	 * @return mixed|CMDBRelatedImpactedService
	 */
	public function getBySysId($sysId, $raw = false)
	{
		$this->sysLog->debug("sysId=" . $sysId);
		$query = "sys_id={$sysId}";
		if (!$raw) {
			$result = $this->getRecord($this->ciTable, $query);
			return $this->_set($result);
		}
		else {
			return $this->getRecord($this->ciTable, $query);
		}
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
	 * @param $sysparmQuery
	 * @return mixed|object
	 */
	public function deleteMultiple($sysparmQuery)
	{
		$this->sysLog->debug("sysparmQuery=" . $sysparmQuery);
		return parent::deleteMultipleCIs($this->ciTable, $sysparmQuery);
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
	 * @return CMDBRelatedImpactedService
	 */
	private function _set($dbRowObj = null)
	{
		$this->sysLog->debug();

		$o = new CMDBRelatedImpactedService();
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
