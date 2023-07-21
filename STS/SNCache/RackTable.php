<?php

namespace STS\SNCache;

use STS\DB\DBTable;


class RackTable extends DBTable
{
    const MULTIPLE_ENTRIES    = 69;

    protected static $reverseNameMapping = array();
	protected static $nameMapping = array(
		"sys_id"            => "sysId",
		"sys_class_name"    => "sysClassName",
		"name"              => "name",
		"location"          => "locationId",
		"dv_location"       => "location",
		"rack_units"        => "rackUnits",
		"rack_units_in_use" => "rackUnitsInUse",
		"u_rack_size"       => "rackSizeId",
		"dv_u_rack_size"    => "rackSize",
		"u_type_of_power"   => "typeOfPower",
		"u_voltage"         => "voltage",
		"sys_created_by"    => "sysCreatedBy",
		"sys_created_on"    => "sysCreatedOn",
		"sys_updated_by"    => "sysUpdatedBy",
		"sys_updated_on"    => "sysUpdatedOn",
	);

    /**
     * @param bool $idAutoIncremented
     */
    public function __construct($idAutoIncremented = false)
    {
        $this->dbIndex = 'sncache';
        $this->tableName = 'cmdb_ci_rack';
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
     * @return Rack
     */
    public function getById($sysId)
    {
        return $this->getBySysId($sysId);
    }


    /**
     * @param   $sysId
     * @return  Rack
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
     * @param $locationId
     * @throws \ErrorException
     * @return Rack
     */
	public function getByNameAndLocationId($name, $locationId)
	{
        $sql = "select {$this->getQueryColumnsStr()}
		        from   \"{$this->schemaName}\".\"{$this->tableName}\"
		        where  name = '" . $name . "'
		          and  location = '" . $locationId . "';";
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
	 * @return Rack
	 */
	private function _set($dbRowObj = null)
	{
		$this->sysLog->debug();

		$o = new Rack();
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
