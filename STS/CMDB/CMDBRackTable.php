<?php
/*******************************************************************************
 *
 * $Id: CMDBRackTable.php 81814 2013-12-09 19:40:39Z rcallaha $
 * $Date: 2013-12-09 14:40:39 -0500 (Mon, 09 Dec 2013) $
 * $Author: rcallaha $
 * $Revision: 81814 $
 * $HeadURL: https://svn.ultradns.net/svn/sts_tools/sts-lib/trunk/STS/CMDB/CMDBRackTable.php $
 *
 *******************************************************************************
 */

namespace STS\CMDB;



class CMDBRackTable extends CMDBDAO
{
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
		$this->ciTable = "cmdb_ci_rack";
		$this->format  = "JSON";

        // create reverse name mapping hash
        foreach (self::$nameMapping as $cmdbProp => $thisProp) {
            self::$reverseNameMapping[$thisProp] = $cmdbProp;
        }
	}

	/**
	 * @param      $sysId
	 * @param bool $raw
	 * @return mixed|CMDBRack
	 */
	public function getById($sysId, $raw = false)
	{
		$this->sysLog->debug("sysId=" . $sysId);
		return $this->getBySysId($sysId, $raw);
	}

	/**
	 * @param      $sysId
	 * @param bool $raw
	 * @return mixed|CMDBRack
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
	 * @param  string $nameId
	 * @param  string $locationId
	 * @return        CMDBRack
	 */
	public function getByNameIdAndLocationId($nameId, $locationId)
	{
		$this->sysLog->debug("nameId=" . $nameId . ", locationId=" . $locationId);
		$query  = "u_rack_location={$nameId}^location={$locationId}";
		$result = $this->getRecord($this->ciTable, $query);
		return $this->_set($result);
	}

	/**
	 * @param $name
	 * @param $locationId
	 * @return CMDBRack
	 */
	public function getByNameAndLocationId($name, $locationId)
	{
		$this->sysLog->debug("name=" . $name . ", locationId=" . $locationId);
		$query  = "name={$name}^location={$locationId}";
		$result = $this->getRecord($this->ciTable, $query);
		return $this->_set($result);
	}

	/**
	 * @param      $name
	 * @param bool $raw
	 * @return mixed|CMDBRack
	 */
	public function getByName($name, $raw = false)
	{
		$this->sysLog->debug("name=" . $name);
		$query  = "name={$name}";
		$result = $this->getRecord($this->ciTable, $query);
		if (!$raw) {
			return $this->_set($result);
		}
		else {
			return $result;
		}
	}

	/**
	 * @param string $name
	 * @param int    $locationId
	 * @return mixed|CMDBRack
	 */
	public function getMostRecentByNameAndLocationId($name, $locationId)
	{
		$this->sysLog->debug("name=" . $name . ", locationId" . $locationId);
		$query  = "name={$name}^location={$locationId}";
		$result = $this->getRecords($this->ciTable, $query);
		$mostRecent = 0;
		$hash = array();
		for ($i=0; $i<count($result); $i++) {
			$o = $this->_set($result[$i]);
			$hash[$o->getSysUpdatedOn()] = $o;
			if ($o->getSysUpdatedOn() > $mostRecent) {
				$mostRecent = $o->getSysUpdatedOn();
			}
		}
		if ($mostRecent) {
			return $hash[$mostRecent];
		} else {
			return new CMDBRack();
		}
	}

	/**
	 * @param int    $locationId
	 * @param string $queryString
	 * @return CMDBRack[]
	 */
	public function getByLocationId($locationId, $queryString="")
	{
		$this->sysLog->notice("locationId=" . $locationId . ", queryString=" . $queryString);
		if ($queryString) {
			$query = "nameLIKE" . $queryString . "^location=" . $locationId . "^ORDERBYname";
		} else {
			$query = "name!=^location=" . $locationId . "^ORDERBYname";
		}
		$records = $this->getRecords($this->ciTable, $query);
		$objects = array();
		for ($i = 0; $i < count($records); $i++) {
			$objects[] = $this->_set($records[$i]);
		}
		return $objects;
	}

	/**
	 * @param      $name
	 * @param bool $raw
	 * @return mixed|CMDBRack
	 */
	public function getByNameLike($name, $raw = false)
	{
		$this->sysLog->debug("name=" . $name);
		$query  = "nameLIKE{$name}";
		$result = $this->getRecord($this->ciTable, $query);
		if (!$raw) {
			return $this->_set($result);
		}
		else {
			return $result;
		}
	}

	/**
	 * @param $query
	 * @return CMDBRack[]
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
        $this->sysLog->debug("json=" . $json);
        return parent::updateCI($this->ciTable, $sysId, $json);
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
     * @param CMDBRack $rack
     * @return CMDBRack
     * @throws \ErrorException
     */
    public function update(CMDBRack $rack)
    {
        $this->sysLog->debug();
        if (count($rack->getChanges()) > 0) {
            // build json
            $json = '';
            foreach ($rack->getChanges() as $prop => $o) {
                if (array_key_exists($prop, self::$reverseNameMapping)) {
                    if ($json != "") $json .= ',';
                    $json .= '"' . self::$reverseNameMapping[$prop] . '":"' . $o->modifiedValue . '"';
                } else {
                    throw new \ErrorException("Trying to set a non-existent property: " . $prop);
                }
            }
            $json = '{' . $json . '}';

            if (!property_exists($rack, 'sysId')) {
                throw new \ErrorException("Rack instance does not have a sysId defined");
            }

            $rack->clearChanges();
            $this->updateByJson($rack->getSysId(), $json);
            return $this->getBySysId($rack->getSysId());
        } else {
            return $rack;
        }
    }

    /**
     * @param CMDBRack $rack
     * @return mixed|CMDBRack
     * @throws \ErrorException
     */
    public function create(CMDBRack $rack)
    {
        $this->sysLog->debug();
        if (count($rack->getChanges()) > 0) {
            // build json
            $json = '';
            foreach ($rack->getChanges() as $prop => $o) {
                if (array_key_exists($prop, self::$reverseNameMapping)) {
                    if ($json != "") $json .= ',';
                    $json .= '"' . self::$reverseNameMapping[$prop] . '":"' . $o->modifiedValue . '"';
                } else {
                    throw new \ErrorException("Trying to set a non-existent property: " . $prop);
                }
            }
            $json = '{' . $json . '}';

            $rack->clearChanges();
            $return = $this->createByJson($json);
            if (property_exists($return, 'records')) {
                if (array_key_exists(0, $return->records)) {
                    return $this->_set($return->records[0]);
                } else {
                    return $rack;
                }
            } else {
                return $this->getByName($rack->getName());
            }
        } else {
            return $rack;
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
	 * @return CMDBRack
	 */
	private function _set($dbRowObj = null)
	{
		$this->sysLog->debug();

		$o = new CMDBRack();
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
