<?php
/*******************************************************************************
 *
 * $Id: CMDBRelationshipType.php 73467 2013-03-21 11:29:38Z rcallaha $
 * $Date: 2013-03-21 07:29:38 -0400 (Thu, 21 Mar 2013) $
 * $Author: rcallaha $
 * $Revision: 73467 $
 * $HeadURL: https://svn.ultradns.net/svn/sts_tools/sts-lib/trunk/STS/CMDB/CMDBRelationshipType.php $
 *
 *******************************************************************************
 */

namespace STS\CMDB;



class CMDBRelationshipTypeTable extends CMDBDAO
{
	protected static $nameMapping = array(
		'sys_id'            => 'sysId',
		'sys_class_name'    => 'sysClassName',
		'name'              => 'name',

		'parent_descriptor' => 'parentDescriptor',
		'child_descriptor'  => 'childDescriptor',

		"sys_created_by"    => "sysCreatedBy",
		"sys_created_on"    => "sysCreatedOn",
		"sys_updated_by"    => "sysUpdatedBy",
		"sys_updated_on"    => "sysUpdatedOn",
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
        $this->ciTable = "cmdb_rel_type_list";
        $this->format  = "JSON";
	}

	/**
	 * @param $name
	 * @return CMDBRelationshipType
	 */
	public function getByName($name)
	{
		$this->sysLog->debug("name=" . $name);
		$query  = "name=" . $name;
		$result = $this->getRecord($this->ciTable, $query);
		return $this->_set($result);
	}

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
	 * @return CMDBRelationshipType
	 */
	private function _set($dbRowObj = null)
	{
		$this->sysLog->debug();
		$o = new CMDBRelationshipType();
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
