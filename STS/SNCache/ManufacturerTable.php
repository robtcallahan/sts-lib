<?php

namespace STS\SNCache;

use STS\DB\DBTable;

class ManufacturerTable extends DBTable
{
    const MULTIPLE_ENTRIES    = 69;

    protected $dbIndex = 'sncache';
    protected $tableName = 'core_company';
    protected $idAutoIncremented = false;

    protected static $reverseNameMapping = array();
	protected static $nameMapping = array(
		"sys_id"         => "sysId",
		"sys_class_name" => "sysClassName",
		"name"           => "name",
        "manufacturer"   => "manufacturer",

		"sys_created_by" => "sysCreatedBy",
		"sys_created_on" => "sysCreatedOn",
		"sys_updated_by" => "sysUpdatedBy",
		"sys_updated_on" => "sysUpdatedOn",
	);

    /**
     * @param null $config
     * @throws \ErrorException
     */
    public function __construct($config=null)
    {
        if ($config && is_array($config)) {
            $this->schemaName = $config['databases'][$this->dbIndex]['schema'];
        }
        if ($config && is_object($config)) {
            $dbIndex          = $this->dbIndex;
            if (isset($config->databases)) {
                $this->schemaName = $config->databases->$dbIndex->schema;
            }
        }

        if ($config && is_array($config)) {
            // need to add these to the config since won't be in the config file
            $config['tableName']         = $this->tableName;
            $config['dbIndex']           = $this->dbIndex;
            $config['idAutoIncremented'] = $this->idAutoIncremented;
        }

        parent::__construct($config);

        // create reverse name mapping hash
        foreach (self::$nameMapping as $cmdbProp => $thisProp) {
            self::$reverseNameMapping[$thisProp] = $cmdbProp;
        }
	}

    /**
     * @param  $sysId
     * @return Manufacturer
     */
    public function getById($sysId)
    {
        return $this->getBySysId($sysId);
    }


    /**
     * @param   $sysId
     * @return  Manufacturer
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
     * @return Manufacturer
     * @throws \ErrorException
     */
    public function getByName($name)
	{
        $sql = "select {$this->getQueryColumnsStr()}
		        from   \"{$this->schemaName}\".\"{$this->tableName}\"
		        where  name = '" . $name . "'
		          and  manufacturer = TRUE;";
        $row = $this->sqlQueryRow($sql);
        if (count($row) > 1) {
            throw new \ErrorException("More than one record returned", self::MULTIPLE_ENTRIES);
        }
        return $this->_set($row);
	}

    /**
     * @return Manufacturer[]
     */
    public function getAll()
	{
        $sql = "select {$this->getQueryColumnsStr()}
		        from   \"{$this->schemaName}\".\"{$this->tableName}\"
		        where  manufacturer = TRUE
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
	 * @return Manufacturer
	 */
	private function _set($dbRowObj = null)
	{
		$this->sysLog->debug();

		$o = new Manufacturer();
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
