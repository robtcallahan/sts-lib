<?php
/*******************************************************************************
 *
 * $Id: CMDBSANSwitchTable.php 82444 2014-01-03 14:28:59Z rcallaha $
 * $Date: 2014-01-03 09:28:59 -0500 (Fri, 03 Jan 2014) $
 * $Author: rcallaha $
 * $Revision: 82444 $
 * $HeadURL: https://svn.ultradns.net/svn/sts_tools/sts-lib/trunk/STS/CMDB/CMDBSANSwitchTable.php $
 *
 *******************************************************************************
 */

namespace STS\CMDB;



class CMDBSANSwitchTable extends CMDBDAO
{
    protected static $reverseNameMapping = array();
	protected static $nameMapping = array(
		'sys_id'                   => 'sysId',
		'sys_class_name'           => 'sysClassName',
		'name'                     => 'name',

        'serial_number'            => 'serialNumber',
		'asset_tag'                => 'assetTag',

        "dv_install_status"        => "installStatus",
        "install_status"           => "installStatusId",

        "device_type"              => "deviceTypeId",
        "dv_device_type"           => "deviceType",

        'dv_u_business_service'    => 'businessService',
		'u_business_service'       => 'businessServiceId',
        'dv_u_business_service_s_' => 'businessServices',
        'u_business_service_s_'    => 'businessServicesIds',
		'u_cmdb_subsystem_list'    => 'subsystemListId',
		'dv_u_cmdb_subsystem_list' => 'subsystemList',

		'dv_u_environment'         => 'environment',
        "u_environment"            => "environmentId",

        "dv_u_location_type"       => "locationType",
        "u_location_type"          => "locationTypeId",
		'location'                 => 'locationId',
		'dv_location'              => 'location',
		'u_rack'                   => 'rackId',
		'dv_u_rack'                => 'rack',
		'u_rack_position'          => 'rackPosition',
		'u_number_of_rack_units'   => 'numberOfRackUnits',

		'manufacturer'             => 'manufacturerId',
		'dv_manufacturer'          => 'manufacturer',
		'model_number'             => 'modelNumber',

        "u_data_source"            => "dataSource",

		'sys_created_by'           => 'sysCreatedBy',
		'sys_created_on'           => 'sysCreatedOn',
		'sys_updated_by'           => 'sysUpdatedBy',
		'sys_updated_on'           => 'sysUpdatedOn',
	);

	protected $ciTable;
	protected $format;

    private $_baseFilter = 'sys_class_name!=^install_statusNOT%20IN117,1501';
    
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
		$this->ciTable = "u_san_switches_storage";
		$this->format  = "JSON";

        // create reverse name mapping hash
        foreach (self::$nameMapping as $cmdbProp => $thisProp) {
            self::$reverseNameMapping[$thisProp] = $cmdbProp;
        }
	}

	/**
	 * @param      $sysId
	 * @param bool $raw
	 * @return mixed|CMDBSANSwitch
	 */
	public function getById($sysId, $raw = false)
	{
		$this->sysLog->debug("sysId=" . $sysId);
		return $this->getBySysId($sysId, $raw);
	}

	/**
	 * @param      $sysId
	 * @param bool $raw
	 * @return mixed|CMDBSANSwitch
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
	 * @return mixed|CMDBSANSwitch
	 */
	public function getByName($name, $raw = false)
	{
		$this->sysLog->debug("name=" . $name);
		$query  = $this->_baseFilter . "^name={$name}";
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
	 * @return CMDBSANSwitch
	 */
	public function getByNameLike($name)
	{
		$this->sysLog->debug("name=" . $name);
		$query  = $this->_baseFilter . "^nameLIKE{$name}";
		$result = $this->getRecord($this->ciTable, $query);
		return $this->_set($result);
	}

	/**
	 * @param $name
	 * @return CMDBSANSwitch
	 */
	public function getByNameStartsWith($name)
	{
		$this->sysLog->debug("name=" . $name);
		$query  = $this->_baseFilter . "^nameSTARTSWITH{$name}";
		$result = $this->getRecord($this->ciTable, $query);
		return $this->_set($result);
	}

	/**
	 * @param $name
	 * @return CMDBSANSwitch[]
	 */
	public function getAllByNameLike($name)
	{
		$this->sysLog->debug("name=" . $name);
		$query   = $this->_baseFilter . "^nameLIKE{$name}";
		$records = $this->getRecords($this->ciTable, $query);
		$array   = array();
		for ($i = 0; $i < count($records); $i++) {
			$array[] = $this->_set($records[$i]);
		}
		return $array;
	}

	/**
	 * @param $serialNumber
	 * @return CMDBSANSwitch
	 */
	public function getBySerialNumber($serialNumber)
	{
		$this->sysLog->debug("serialNumber=" . $serialNumber);
		$query  = $this->_baseFilter . "^serial_number={$serialNumber}";
		$result = $this->getRecord($this->ciTable, $query);
		return $this->_set($result);
	}

	/**
	 * @param $query
	 * @return CMDBSANSwitch[]
	 */
	public function getByQueryString($query)
	{
		$records = $this->getRecords($this->ciTable, $query);
		$array   = array();
		for ($i = 0; $i < count($records); $i++) {
			$array[] = $this->_set($records[$i]);
		}
		return $array;
	}

	/**
	 * @param $sysId
	 * @param $json
	 * @return mixed|object
	 */
	public function updateByJson($sysId, $json)
	{
		return parent::updateCI($this->ciTable, $sysId, $json);
	}

    /**
     * @param CMDBSANSwitch $ci
     * @return CMDBSANSwitch
     * @throws \ErrorException
     */
    public function update(CMDBSANSwitch $ci)
    {
        if (count($ci->getChanges()) > 0) {
            $json = $this->buildJson($ci);

            if (!property_exists($ci, 'sysId')) {
                throw new \ErrorException("Server instance does not have a sysId defined");
            }

            $ci->clearChanges();
            $this->updateByJson($ci->getSysId(), $json);
            return $this->getBySysId($ci->getSysId());
        } else {
            return $ci;
        }
    }

    /**
     * @param $json
     * @return mixed|object
     */
    public function createByJson($json)
    {
        $this->sysLog->debug("json=" . $json);
        return parent::createCI($this->ciTable, $json);
    }

    /**
     * @param CMDBSANSwitch $ci
     * @return mixed|CMDBServer
     * @throws \ErrorException
     */
    public function create(CMDBSANSwitch $ci)
    {
        $this->sysLog->debug();
        if (count($ci->getChanges()) > 0) {
            $json = $this->buildJson($ci);

            $ci->clearChanges();
            $return = $this->createByJson($json);
            if (property_exists($return, 'records')) {
                if (array_key_exists(0, $return->records)) {
                    return $this->_set($return->records[0]);
                } else {
                    return $ci;
                }
            } else {
                return $this->getByName($ci->getName());
            }
        } else {
            return $ci;
        }
    }

    private function buildJson(CMDBSANSwitch $ci)
    {
        $json = '';
        foreach ($ci->getChanges() as $prop => $o) {
            if (array_key_exists($prop, self::$reverseNameMapping)) {
                if ($json != "") $json .= ',';
                $json .= '"' . self::$reverseNameMapping[$prop] . '":"' . $o->modifiedValue . '"';
            } else {
                throw new \ErrorException("Trying to set a non-existent property: " . $prop);
            }
        }
        $json = '{' . $json . '}';
        return $json;
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
     * @return string
     */
    public function getCiTable()
    {
        return $this->ciTable;
    }

	/**
	 * @param null $dbRowObj
	 * @return CMDBSANSwitch
	 */
	private function _set($dbRowObj = null)
	{
		$this->sysLog->debug();

		$o = new CMDBSANSwitch();
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
