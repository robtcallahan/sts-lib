<?php
/*******************************************************************************
 *
 * $Id: CMDBChangeRequestTable.php 81814 2013-12-09 19:40:39Z rcallaha $
 * $Date: 2013-12-09 14:40:39 -0500 (Mon, 09 Dec 2013) $
 * $Author: rcallaha $
 * $Revision: 81814 $
 * $HeadURL: https://svn.ultradns.net/svn/sts_tools/sts-lib/trunk/STS/CMDB/CMDBChangeRequestTable.php $
 *
 *******************************************************************************
 */

namespace STS\CMDB;



class CMDBChangeRequestTable extends CMDBDAO
{
	protected static $nameMapping = array(
		"sys_id"                 => "sysId",
		"sys_class_name"         => "sysClassName",
		"number"                 => "number",
		"assignment_group"       => "assignmentGroupId",
		"dv_assignment_group"    => "assignmentGroup",
		"assigned_to"            => "assignedToId",
		"dv_assigned_to"         => "assignedTo",
		"u_change_owner"         => "changeOwnerId",
		"dv_u_change_owner"      => "changeOwner",
		"u_service"              => "serviceId",
		"dv_u_service"           => "service",
		"u_business_services"    => "businessServicesId",
		"dv_u_business_services" => "businessServices",
		"u_additional_cis"       => "additionalCisId",
		"dv_u_additional_cis"    => "additionalCis",
		"u_ci_not_listed"        => "ciNotListed",
		"requested_by"           => "requestedById",
		"dv_requested_by"        => "requestedBy",
		"approval"               => "approval",
		"category"               => "category",
		"u_sub_category"         => "subCategory",
		"type"                   => "type",
		"short_description"      => "shortDescription",
		"start_date"             => "startDate",
		"end_date"               => "endDate",
		"sys_created_by"         => "sysCreatedBy",
		"sys_created_on"         => "sysCreatedOn",
		"sys_updated_by"         => "sysUpdatedBy",
		"sys_updated_on"         => "sysUpdatedOn",
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
		$this->ciTable = "change_request_list";
		$this->format  = "JSON";
	}

	/**
	 * @param      $sysId
	 * @param bool $raw
	 * @return mixed|CMDBChangeRequest
	 */
	public function getById($sysId, $raw = false)
	{
		$this->sysLog->debug("sysId=" . $sysId);
		return $this->getBySysId($sysId, $raw);
	}

	/**
	 * @param      $sysId
	 * @param bool $raw
	 * @return mixed|CMDBChangeRequest
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
	 * @param $number
	 * @return CMDBChangeRequest
	 */
	public function getByNumber($number)
	{
		$this->sysLog->debug("number=" . $number);
		$query  = "number={$number}";
		$result = $this->getRecord($this->ciTable, $query);
		return $this->_set($result);
	}

	/**
	 * @param $query
	 * @return CMDBChangeRequest[]
	 */
	public function getByQueryString($query)
	{
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
		$this->sysLog->debug("json=" . $json);
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
	 * @return CMDBChangeRequest
	 */
	private function _set($dbRowObj = null)
	{
		$this->sysLog->debug();
		$o = new CMDBChangeRequest();
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
