<?php
/*******************************************************************************
 *
 * $Id: CMDBSubsystemTable.php 81814 2013-12-09 19:40:39Z rcallaha $
 * $Date: 2013-12-09 14:40:39 -0500 (Mon, 09 Dec 2013) $
 * $Author: rcallaha $
 * $Revision: 81814 $
 * $HeadURL: https://svn.ultradns.net/svn/sts_tools/sts-lib/trunk/STS/CMDB/CMDBSubsystemTable.php $
 *
 *******************************************************************************
 */

namespace STS\CMDB;



class CMDBSubsystemTable extends CMDBDAO
{
    protected static $reverseNameMapping = array();
    protected static $nameMapping = array(
        "sys_id"                            => "sysId",
        "name"                              => "name",

        'dv_operational_status'             => 'operationalStatus',
        'operational_status'                => 'operationalStatusId',

        "dv_u_business_service"             => "businessService",
        "u_business_service"                => "businessServiceId",

        "dv_u_owning_support_manager"       => "owningSupportManager",
        "u_owning_support_manager"          => "owningSupportManagerId",

        "dv_u_operations_support_group"     => "operationsSupportGroup",
        "u_operations_support_group"        => "operationsSupportGroupId",

        "dv_u_systems_administration_manag" => "systemsAdminManager",
        "u_systems_administration_manag"    => "systemsAdminManagerId",

        "dv_u_system_admin_group"           => "systemsAdminGroup",
        "u_system_admin_group"              => "systemsAdminGroupId",

        "dv_u_cm_director"                  => "cmDirector",
        "u_cm_director"                     => "cmDirectorId",

        "u_subsystem_category"              => "subsystemCategory",
        "u_service_business_class"          => "serviceBusinessClass",
        "u_service_sla_class"               => "serviceSlaClass",

        "sys_created_by"                    => "sysCreatedBy",
        "sys_created_on"                    => "sysCreatedOn",
        "sys_updated_by"                    => "sysUpdatedBy",
        "sys_updated_on"                    => "sysUpdatedOn"
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
        $this->ciTable = "u_subsystem";
        $this->format  = "JSON";

        // create reverse name mapping hash
        foreach (self::$nameMapping as $cmdbProp => $thisProp) {
            self::$reverseNameMapping[$thisProp] = $cmdbProp;
        }
    }

    /**
     * @param      $sysId
     * @param bool $raw
     * @return mixed|CMDBSubsystem
     */
    public function getById($sysId, $raw = false)
    {
        $this->sysLog->debug("sysId=" . $sysId);
        return $this->getBySysId($sysId, $raw);
    }

    /**
     * @param      $sysId
     * @param bool $raw
     * @return mixed|CMDBSubsystem
     */
    public function getBySysId($sysId, $raw = false)
    {
        $this->sysLog->debug("sysId=" . $sysId);
        $query  = "sys_id={$sysId}";
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
     * @return mixed|CMDBSubsystem
     */
    public function getByName($name, $raw = false)
    {
        $this->sysLog->debug("name=" . $name);
        $query  = "name=" . $name;
        $result = $this->getRecord($this->ciTable, $query);
        if (!$raw) {
            return $this->_set($result);
        } else {
            return $result;
        }
    }

    /**
     * @param int $businessServiceId
     * @return CMDBSubsystem[]
     */
    public function getByBusinessServiceId($businessServiceId)
    {
        $this->sysLog->debug("businessServiceId=" . $businessServiceId);
        $query  = "operational_status!=9^u_business_service=" . $businessServiceId . "^ORDERBYname";
        $records = $this->getRecords($this->ciTable, $query);
        $objects = array();
        for ($i = 0; $i < count($records); $i++) {
            $objects[] = $this->_set($records[$i]);
        }
        return $objects;
    }

    /**
   	 * @param $sysId
   	 * @param $json
   	 * @return mixed|object
   	 */
   	public function updateByJson($sysId, $json)
   	{
   		$this->sysLog->debug("json=" . $json);
   		return $this->updateCI($this->ciTable, $sysId, $json);
   	}

    /**
     * @param CMDBSubsystem $subsystem
     * @return CMDBSubsystem
     * @throws \ErrorException
     */
    public function update(CMDBSubsystem $subsystem)
    {
        if (count($subsystem->getChanges()) > 0) {
            // build json
            $json = '';
            foreach ($subsystem->getChanges() as $prop => $o) {
                if (array_key_exists($prop, self::$reverseNameMapping)) {
                    if ($json != "") $json .= ',';
                    $json .= '"' . self::$reverseNameMapping[$prop] . '":"' . $o->modifiedValue . '"';
                } else {
                    throw new \ErrorException("Trying to set a non-existent property: " . $prop);
                }
            }
            $json = '{' . $json . '}';

            if (!property_exists($subsystem, 'sysId')) {
                throw new \ErrorException("Server instance does not have a sysId defined");
            }

            $subsystem->clearChanges();
            $this->updateByJson($subsystem->getSysId(), $json);
            return $this->getBySysId($subsystem->getSysId());
        } else {
            return $subsystem;
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
     * @param $format
     * @return void
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
     * @return CMDBSubsystem
     */
    private function _set($dbRowObj = null)
    {
        $this->sysLog->debug();

        $o = new CMDBSubsystem();
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
