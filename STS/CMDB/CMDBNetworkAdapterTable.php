<?php
/*******************************************************************************
 *
 * $Id: CMDBNetworkAdapter.php 73209 2013-03-14 18:18:55Z rcallaha $
 * $Date: 2013-03-14 14:18:55 -0400 (Thu, 14 Mar 2013) $
 * $Author: rcallaha $
 * $Revision: 73209 $
 * $HeadURL: https://svn.ultradns.net/svn/sts_tools/sts-lib/trunk/STS/CMDB/CMDBNetworkAdapter.php $
 *
 *******************************************************************************
 */

namespace STS\CMDB;



class CMDBNetworkAdapterTable extends CMDBDAO
{
    protected static $nameMapping = array(
        'sys_id'             => 'sysId',
        'sys_class_name'     => 'sysClassName',
        'name'               => 'name',
        'cmdb_ci'            => 'cmdbCi',

        'ip_address'         => 'ipAddress',
        'netmask'            => 'netmask',
        'ip_default_gateway' => 'ipDefaultGateway',
        'mac_address'        => 'macAddress',
        'u_type'             => 'uType',
        'short_description'  => 'shortDescription',

        "sys_created_by"     => "sysCreatedBy",
        "sys_created_on"     => "sysCreatedOn",
        "sys_updated_by"     => "sysUpdatedBy",
        "sys_updated_on"     => "sysUpdatedOn",
    );

    protected $ciTable;
    protected $format;
    protected $json;

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
        $this->ciTable = "cmdb_ci_network_adapter";
        $this->format  = "JSON";
    }

    /**
     * @param string $serverId
     * @param bool $raw
     * @return CMDBNetworkAdapter[]
     */
    public function getByServerId($serverId, $raw = false)
    {
        $this->sysLog->debug("serverId=" . $serverId);

        $query   = "cmdb_ci=" . $serverId;
        $records = $this->getRecords($this->ciTable, $query);
        $objects = array();
        for ($i = 0; $i < count($records); $i++) {
            if (!$raw) {
                $objects[] = $this->_set($records[$i]);
            } else {
                $objects[] = $records[$i];
            }
        }
        return $objects;
    }

    /**
     * @param CMDBNetworkAdapter $networkAdapter
     * @return mixed
     */
    public function create(CMDBNetworkAdapter $networkAdapter)
    {
        $props = array();
        foreach (array("cmdb_ci", "name", "ip_address", "netmask", "mac_address", "u_type", "short_description") as $p) {
            $prop    = self::$nameMapping[$p];
            $props[] = '"' . $p . '":"' . $networkAdapter->get($prop) . '"';
        }
        $json = '{' . implode(",", $props) . '}';
        $this->json = $json;
        return parent::createCI($this->ciTable, $json);
    }

    /**
     * @param CMDBNetworkAdapter $networkAdapter
     * @return mixed
     */
    public function update(CMDBNetworkAdapter $networkAdapter)
    {
        $props = array();
        foreach (array("name", "netmask", "mac_address", "u_type", "short_description") as $p) {
            $prop    = self::$nameMapping[$p];
            $props[] = '"' . $p . '":"' . $networkAdapter->get($prop) . '"';
        }
        $json = '{' . implode(",", $props) . '}';
        $this->json = $json;
        return parent::updateCI($this->ciTable, $networkAdapter->getSysId(), $json);
    }

    /**
     * @param string $sysId
     * @return mixed
     */
    public function delete($sysId)
    {
        $this->sysLog->debug("sysId=" . $sysId);
        return parent::deleteCI($this->ciTable, $sysId);
    }

    /**
     * @param string $sysId
     * @param string $json
     * @return mixed
     */
    public function updateByJson($sysId, $json)
    {
        $this->sysLog->debug("json=" . $json);
        $this->json = $json;
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
     * @return int
     */
    public function getLogLevel()
    {
        return $this->logLevel;
    }

    /**
     * @param int $format
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
     * @return mixed
     */
    public function getJson()
    {
        return $this->json;
    }

    /**
     * @param null $dbRowObj
     * @return CMDBNetworkAdapter
     */
    private function _set($dbRowObj = null)
    {
        $this->sysLog->debug();

        $o = new CMDBNetworkAdapter();
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
