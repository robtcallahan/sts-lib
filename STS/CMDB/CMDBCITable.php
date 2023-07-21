<?php
/*******************************************************************************
 *
 * $Id: CMDBCITable.php 81814 2013-12-09 19:40:39Z rcallaha $
 * $Date: 2013-12-09 14:40:39 -0500 (Mon, 09 Dec 2013) $
 * $Author: rcallaha $
 * $Revision: 81814 $
 * $HeadURL: https://svn.ultradns.net/svn/sts_tools/sts-lib/trunk/STS/CMDB/CMDBCITable.php $
 *
 *******************************************************************************
 */

namespace STS\CMDB;



class CMDBCITable extends CMDBDAO
{
    protected static $reverseNameMapping = array();
    protected static $nameMapping = array(
        "sys_id"                    => "sysId",
        "sys_class_name"            => "sysClassName",

        "name"                      => "name",
        "serial_number"             => "serialNumber",
        "asset_tag"                 => "assetTag",

        "location"                  => "locationId",
        "dv_location"               => "location",

        "dv_install_status"         => "installStatus",
        "install_status"            => "installStatusId",

        "manufacturer"              => "manufacturerId",
        "dv_manufacturer"           => "manufacturer",
        "model_number"              => "modelNumber",

        "delivery_date"             => "deliveryDate",
        "po_number"                 => "poNumber",
        "u_asset_id"                => "assetId",
        "u_asset_receipt_date_time" => "assetReceiptDateTime",
        "dv_u_p_o__requestor"       => "poRequestor",
        "u_p_o__requestor"          => "poRequestorId",

        "sys_created_by"            => "sysCreatedBy",
        "sys_created_on"            => "sysCreatedOn",
        "sys_updated_by"            => "sysUpdatedBy",
        "sys_updated_on"            => "sysUpdatedOn",
    );

    protected $ciTable;
    protected $format;

    private $_baseFilter = 'sys_class_name!=^install_statusNOT IN117,1501';
    
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
        $this->ciTable = "cmdb_ci";
        $this->format = "JSON";

        // create reverse name mapping hash
        foreach (self::$nameMapping as $cmdbProp => $thisProp) {
            self::$reverseNameMapping[$thisProp] = $cmdbProp;
        }
    }

    /**
     * @param      $sysId
     * @param bool $raw
     * @return mixed|CMDBCI
     */
    public function getById($sysId, $raw = false)
    {
        $this->sysLog->debug("sysId=" . $sysId);
        return $this->getBySysId($sysId, $raw);
    }

    /**
     * @param      $sysId
     * @param bool $raw
     * @return mixed|CMDBCI
     */
    public function getBySysId($sysId, $raw = false)
    {
        $this->sysLog->debug("sysId=" . $sysId);
        $query = "sys_id={$sysId}";
        $result = $this->getRecord($this->ciTable, $query);
        if (!$raw) {
            return $this->_set($result);
        } else {
            return $result;
        }
    }

    /**
     * @param      $name
     * @param bool $raw
     * @return mixed|CMDBCI
     */
    public function getByName($name, $raw = false)
    {
        $this->sysLog->debug("name=" . $name);
        $query = $this->_baseFilter . "^name=" . $name;
        $result = $this->getRecord($this->ciTable, $query);
        if (!$raw) {
            return $this->_set($result);
        } else {
            return $result;
        }
    }

    /**
     * @param      $name
     * @param bool $raw
     * @return mixed|CMDBCI
     */
    public function getByNameLike($name, $raw = false)
    {
        $this->sysLog->debug("name=" . $name);
        $query = $this->_baseFilter . "^nameLIKE" . $name;
        $result = $this->getRecord($this->ciTable, $query);
        if (!$raw) {
            return $this->_set($result);
        } else {
            return $result;
        }
    }

    /**
     * @param      $name
     * @param bool $raw
     * @return mixed|CMDBCI
     */
    public function getByNameStartsWith($name, $raw = false)
    {
        $this->sysLog->debug("name=" . $name);
        $query  = $this->_baseFilter . "^nameSTARTSWITH" . $name;
        $result = $this->getRecord($this->ciTable, $query);
        if (!$raw) {
            return $this->_set($result);
        } else {
            return $result;
        }
    }

    /**
   	 * @param $name
   	 * @param $anyStatus
   	 * @return CMDBCI[]
   	 */
   	public function getAllByNameLike($name, $anyStatus = false)
   	{
   		$this->sysLog->debug("name=" . $name);
   		if ($anyStatus) {
   			$query = "sys_class_name!=^nameLIKE" . $name;
   		}
   		else {
   			$query = $this->_baseFilter . "^nameLIKE" . $name;
   		}
   		$records = $this->getRecords($this->ciTable, $query);
   		$objects = array();
   		for ($i = 0; $i < count($records); $i++) {
   			$objects[] = $this->_set($records[$i]);
   		}
   		return $objects;
   	}

    /**
     * @param string $serialNumber
     * @param bool $raw
     * @return mixed|CMDBCI
     */
    public function getBySerialNumber($serialNumber, $raw = false)
    {
        $this->sysLog->debug("serialNumber=" . $serialNumber);
        $query  = $this->_baseFilter . "^serial_number=" . $serialNumber;
        $result = $this->getRecord($this->ciTable, $query);
        if (!$raw) {
            return $this->_set($result);
        } else {
            return $result;
        }
    }

    /**
     * @param string $serialNumber
     * @return mixed|CMDBCI
     */
    public function getBySerialNumberLike($serialNumber)
    {
        $this->sysLog->debug("serialNumber=" . $serialNumber);
        $query  = $this->_baseFilter . "^serial_numberLIKE" . $serialNumber;
        $records = $this->getRecords($this->ciTable, $query);
        $objects = array();
        for ($i = 0; $i < count($records); $i++) {
            $objects[] = $this->_set($records[$i]);
        }
        return $objects;
    }

    /**
     * @param string $assetTag
     * @param bool $raw
     * @return mixed|CMDBCI
     */
    public function getByAssetTag($assetTag, $raw = false)
    {
        $this->sysLog->debug("assetTag=" . $assetTag);
        $query  = $this->_baseFilter . "^asset_tag=" . $assetTag;
        $result = $this->getRecord($this->ciTable, $query);
        if (!$raw) {
            return $this->_set($result);
        } else {
            return $result;
        }
    }

    /**
     * @param string $assetTag
     * @return mixed|CMDBCI
     */
    public function getByAssetTagLike($assetTag)
    {
        $this->sysLog->debug("assetTag=" . $assetTag);
        $query  = $this->_baseFilter . "^asset_tagLIKE" . $assetTag;
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
     * @return mixed|CMDBCI
     */
    public function getByAssetId($name, $raw = false)
    {
        $this->sysLog->debug("name=" . $name);
        $query  = $this->_baseFilter . "^u_asset_id=" . $name;
        $result = $this->getRecord($this->ciTable, $query);
        if (!$raw) {
            return $this->_set($result);
        } else {
            return $result;
        }
    }

    /**
     * @param $query
     * @param bool $raw
     * @return CMDBCI[]|array
     */
    public function getByQueryString($query, $raw = false)
    {
        $this->sysLog->debug("query=" . $query);
        $records = $this->getRecords($this->ciTable, $query);
        if (!$raw) {
            $objects = array();
            for ($i = 0; $i < count($records); $i++) {
                $objects[] = $this->_set($records[$i]);
            }
            return $objects;
        } else {
            return $records;
        }
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
     * @param CMDBCI $server
     * @return CMDBCI
     * @throws \ErrorException
     */
    public function update(CMDBCI $server)
    {
        if (count($server->getChanges()) > 0) {
            // build json
            $json = '{';
            foreach ($server->getChanges() as $prop => $o) {
                if (array_key_exists($prop, self::$reverseNameMapping)) {
                    $json .= '"' . self::$reverseNameMapping[$prop] . '":"' . $o->modifiedValue . '"';
                } else {
                    throw new \ErrorException("Trying to set a non-existent property: " . $prop);
                }
            }
            $json .= '}';

            if (!property_exists($server, 'sysId')) {
                throw new \ErrorException("Server instance does not have a sysId defined");
            }

            $server->clearChanges();
            $this->updateByJson($server->getSysId(), $json);
            return $this->getBySysId($server->getSysId());
        } else {
            return $server;
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
     * @param string $format
     */
    public function setFormat($format)
    {
        $this->format = $format;
    }

    /**
     * @return string
     */
    public function getFormat()
    {
        return $this->format;
    }

    /**
     * @return array
     */
    public static function getNameMapping()
    {
        return self::$nameMapping;
    }

    /**
     * @return array
     */
    public static function getReverseNameMapping()
    {
        return self::$reverseNameMapping;
    }

    /**
     * @param null $dbRowObj
     * @return CMDBCI
     */
    private function _set($dbRowObj = null)
    {
        $this->sysLog->debug();

        $o = new CMDBCI();
        foreach (self::$nameMapping as $cmdbProp => $modelProp) {
            if ($dbRowObj && property_exists($dbRowObj, $cmdbProp)) {
                $o->set($modelProp, $dbRowObj->$cmdbProp);
            } else {
                $o->set($modelProp, null);
            }
        }
        return $o;
    }
}
