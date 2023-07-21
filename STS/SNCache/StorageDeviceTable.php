<?php

namespace STS\SNCache;

use STS\DB\DBTable;

class StorageDeviceTable extends DBTable
{
    const MULTIPLE_ENTRIES    = 69;

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
        'dv_u_cmdb_subsystem_list' => 'subsystemList',
		'u_cmdb_subsystem_list'    => 'subsystemListId',

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

    /**
     * @param bool $idAutoIncremented
     */
    public function __construct($idAutoIncremented = false)
    {
        $this->dbIndex = 'sncache';
        $this->tableName = 'cmdb_ci_msd';
        $this->idAutoIncremented = $idAutoIncremented;
		parent::__construct();

        $this->sysLog->debug();

        // create reverse name mapping hash
        foreach (self::$nameMapping as $cmdbProp => $thisProp) {
            self::$reverseNameMapping[$thisProp] = $cmdbProp;
        }
	}

    /**
     * @param  $sysId
     * @return StorageDevice
     */
    public function getById($sysId)
    {
        return $this->getBySysId($sysId);
    }


    /**
     * @param   $sysId
     * @return  StorageDevice
     */
    public function getBySysId($sysId)
    {
        $sql = "select {$this->getQueryColumnsStr()}
		        from   \"{$this->schemaName}\".\"{$this->tableName}\"
		        where  sys_id = '" . $sysId . "';";
        $row = $this->sqlQueryRow($sql);
        return $this->_set($row);
    }

    /**
     * @param $name
     * @throws \ErrorException
     * @return StorageDevice
     */
    public function getByName($name)
	{
        $sql = "select {$this->getQueryColumnsStr()}
		        from   \"{$this->schemaName}\".\"{$this->tableName}\"
		        where  name = '" . $name . "'
		          and  install_status != 117
		          and  install_status != 1501;";
        $row = $this->sqlQueryRow($sql);
        if (count($row) > 1) {
            throw new \ErrorException("More than one record returned", self::MULTIPLE_ENTRIES);
        }
        return $this->_set($row);
	}

    /**
     * @param $name
     * @throws \ErrorException
     * @return StorageDevice
     */
    public function getByNameLike($name)
	{
        $sql = "select {$this->getQueryColumnsStr()}
		        from   \"{$this->schemaName}\".\"{$this->tableName}\"
		        where  name LIKE '%" . $name . "%'
		          and  install_status != 117
		          and  install_status != 1501;";
        $row = $this->sqlQueryRow($sql);
        if (count($row) > 1) {
            throw new \ErrorException("More than one record returned", self::MULTIPLE_ENTRIES);
        }
        return $this->_set($row);
	}

    /**
     * @param $name
     * @return StorageDevice
     * @throws \ErrorException
     */
    public function getByNameStartsWith($name)
	{
        $sql = "select {$this->getQueryColumnsStr()}
		        from   \"{$this->schemaName}\".\"{$this->tableName}\"
		        where  name = '" . $name . "%'
		          and  install_status != 117
		          and  install_status != 1501;";
        $row = $this->sqlQueryRow($sql);
        if (count($row) > 1) {
            throw new \ErrorException("More than one record returned", self::MULTIPLE_ENTRIES);
        }
        return $this->_set($row);
	}

    /**
     * @param $name
     * @return StorageDevice[]
     */
    public function getAllByNameLike($name)
	{
        $sql = "select {$this->getQueryColumnsStr()}
		        from   \"{$this->schemaName}\".\"{$this->tableName}\"
		        where  name = '%" . $name . "%'
		          and  install_status != 117
		          and  install_status != 1501
		        order by name;";
        $rows = $this->sqlQueryRow($sql);
        $results = array();
        foreach ($rows as $row) {
            $results[] = $this->_set($row);
        }
        return $results;
	}

    /**
     * @param $serialNumber
     * @throws \ErrorException
     * @return StorageDevice
     */
    public function getBySerialNumber($serialNumber)
	{
        $sql = "select {$this->getQueryColumnsStr()}
		        from   \"{$this->schemaName}\".\"{$this->tableName}\"
		        where  serial_number = '" . $serialNumber . "'
		          and  install_status != 117
		          and  install_status != 1501;";
        $row = $this->sqlQueryRow($sql);
        if (count($row) > 1) {
            throw new \ErrorException("More than one record returned", self::MULTIPLE_ENTRIES);
        }
        return $this->_set($row);
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
     * @return StorageDevice
     */
    private function _set($dbRowObj = null)
	{
		$this->sysLog->debug();

		$o = new StorageDevice();
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
