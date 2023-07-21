<?php

namespace STS\SNCache;

use STS\DB\DBTable;

class LocationTable extends DBTable
{
    const MULTIPLE_ENTRIES    = 69;

    protected static $reverseNameMapping = array();
	protected static $nameMapping = array(
		"sys_id"         => "sysId",
		"sys_class_name" => "sysClassName",
		"name"           => "name",
		"street"         => "street",
		"city"           => "city",
		"state"          => "state",
		"zip"            => "zip",
		"u_type"         => "type",

		"sys_created_by" => "sysCreatedBy",
		"sys_created_on" => "sysCreatedOn",
		"sys_updated_by" => "sysUpdatedBy",
		"sys_updated_on" => "sysUpdatedOn",
	);

    protected $queryColumnsStr;

    /**
     * @param $config
     */
    public function __construct($config=null)
    {
        $this->dbIndex = 'sncache';
        $this->tableName = 'cmn_location';
        if ($config && is_array($config)) {
            // need to add these to the config since won't be in the config file
            $config['tableName'] = $this->tableName;
            $config['dbIndex'] = $this->dbIndex;
            $config['idAutoIncremented'] = false;
        }
        $this->schemaName = $config['databases'][$this->dbIndex]['schema'];

		parent::__construct($config);

        $this->sysLog->debug();

        // create reverse name mapping hash and a queryColumnsString
        $this->queryColumnsStr = '';
        $tmpArray = array();
        foreach (self::$nameMapping as $cmdbProp => $thisProp) {
            self::$reverseNameMapping[$thisProp] = $cmdbProp;
            $tmpArray[] = $cmdbProp;
        }
        $this->queryColumnsStr = implode(',', $tmpArray);
	}

    /**
     * @param  $sysId
     * @return Location
     */
    public function getById($sysId)
    {
        return $this->getBySysId($sysId);
    }


    /**
     * @param   $sysId
     * @return  Location
     */
    public function getBySysId($sysId)
    {
        $sql = "select {$this->queryColumnsStr}
		        from   \"{$this->schemaName}\".\"{$this->tableName}\"
		        where  sys_id = '" . $sysId . "';";
        $row = $this->sqlQueryRow($sql);
        return $this->_set($row);
    }

    /**
     * @param $name
     * @throws \ErrorException
     * @return Location
     */
	public function getByName($name)
	{
        $sql = "select {$this->queryColumnsStr}
		        from   \"{$this->schemaName}\".\"{$this->tableName}\"
		        where  lower(name) = lower('" . $name . "');";
        $row = $this->sqlQueryRow($sql);
        if (count($row) > 1) {
            throw new \ErrorException("More than one record returned", self::MULTIPLE_ENTRIES);
        }
        return $this->_set($row);
	}

    /**
     * @param $name
     * @throws \ErrorException
     * @return Location[]
     */
	public function getByNameLike($name)
	{
        $sql = "select {$this->queryColumnsStr}
		        from   \"{$this->schemaName}\".\"{$this->tableName}\"
		        where  lower(name) LIKE lower('%" . $name . "%');";
        $rows = $this->sqlQuery($sql);
        $results = array();
        foreach ($rows as $row) {
            $results[] = $this->_set($row);
        }
        return $results;
	}

	/**
	 * @return Location[]
	 */
	public function getAll()
	{
        $sql = "select {$this->queryColumnsStr}
		        from   \"{$this->schemaName}\".\"{$this->tableName}\"
		        WHERE u_type = 'Neustar Data Center'
		           or u_type = 'Colocated'
		           or u_type = 'Remotely Hosted'
		        order by name;";
        $rows = $this->sqlQuery($sql);
        $results = array();
        foreach ($rows as $row) {
            $results[] = $this->_set($row);
        }
        return $results;
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
	 * @return Location
	 */
	private function _set($dbRowObj = null)
	{
		$this->sysLog->debug();

		$o = new Location();
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
