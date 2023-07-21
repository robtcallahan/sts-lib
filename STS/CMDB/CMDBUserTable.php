<?php
/*******************************************************************************
 *
 * $Id: CMDBUserTable.php 81814 2013-12-09 19:40:39Z rcallaha $
 * $Date: 2013-12-09 14:40:39 -0500 (Mon, 09 Dec 2013) $
 * $Author: rcallaha $
 * $Revision: 81814 $
 * $HeadURL: https://svn.ultradns.net/svn/sts_tools/sts-lib/trunk/STS/CMDB/CMDBUserTable.php $
 *
 *******************************************************************************
 */

namespace STS\CMDB;

class CMDBUserTable extends CMDBDAO
{
	protected static $nameMapping = array(
		'sys_id'                         => 'sysId',
		'sys_class_name'                 => 'sysClassName',
		'active'                         => 'active',
		'source'                         => 'source',
		'name'                           => 'name',
		'first_name'                     => 'firstName',
		'last_name'                      => 'lastName',
		'user_name'                      => 'userName',
		'title'                          => 'title',
		'email'                          => 'email',
		'employee_number'                => 'employeeNumber',
		'manager'                        => 'managerId',
		'dv_manager'                     => 'manager',
		'department'                     => 'departmentId',
		'dv_department'                  => 'department',
		'phone'                          => 'phone',
		'home_phone'                     => 'homePhone',
		'mobile_phone'                   => 'mobilePhone',
		'u_other_phone'                  => 'otherPhone',
		'u_pager__direct_providor_email' => 'pagerDirectProviderEmail',
		'u_description'                  => 'description',
		'u_displayname'                  => 'displayName',
		'u_nick_name'                    => 'nickName',
		'sys_created_by'                 => 'sysCreatedBy',
		'sys_created_on'                 => 'sysCreatedOn',
		'sys_updated_by'                 => 'sysUpdatedBy',
		'sys_updated_on'                 => 'sysUpdatedOn',
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
		$this->ciTable = "sys_user";
		$this->format  = "JSON";
	}

	/**
	 * @param      $sysId
	 * @param bool $raw
	 * @return mixed|CMDBUser
	 */
	public function getById($sysId, $raw = false)
	{
		$this->sysLog->debug("sysId=" . $sysId);
		return $this->getBySysId($sysId, $raw);
	}

	/**
	 * @param      $sysId
	 * @param bool $raw
	 * @return mixed|CMDBUser
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
	 * @param      $name
	 * @param bool $raw
	 * @return mixed|CMDBUser
	 */
	public function getByUserName($name, $raw = false)
	{
		$this->sysLog->debug("name=" . $name);
		$query  = "user_name=" . $name;
		$result = $this->getRecord($this->ciTable, $query);
		if (!$raw) {
			return $this->_set($result);
		}
		else {
			return $result;
		}
	}

	/**
	 * @param      $name
	 * @param bool $raw
	 * @return mixed|CMDBUser
	 */
	public function getByLastName($name, $raw = false)
	{
		$this->sysLog->debug("name=" . $name);
		$query  = "last_name=" . $name;
		$result = $this->getRecord($this->ciTable, $query);
		if (!$raw) {
			return $this->_set($result);
		}
		else {
			return $result;
		}
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
	 * @return CMDBUser
	 */
	private function _set($dbRowObj = null)
	{
		$this->sysLog->debug();

		$o = new CMDBUser();
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
