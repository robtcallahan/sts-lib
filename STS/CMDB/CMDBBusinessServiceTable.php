<?php
/*******************************************************************************
 *
 * $Id: CMDBBusinessServiceTable.php 81814 2013-12-09 19:40:39Z rcallaha $
 * $Date: 2013-12-09 14:40:39 -0500 (Mon, 09 Dec 2013) $
 * $Author: rcallaha $
 * $Revision: 81814 $
 * $HeadURL: https://svn.ultradns.net/svn/sts_tools/sts-lib/trunk/STS/CMDB/CMDBBusinessServiceTable.php $
 *
 *******************************************************************************
 */

namespace STS\CMDB;



class CMDBBusinessServiceTable extends CMDBDAO
{
    protected static $reverseNameMapping = array();
    protected static $nameMapping = array(
        'sys_id'                            => 'sysId',
        'sys_class_name'                    => 'sysClassName',
        'name'                              => 'name',

        'dv_operational_status'             => 'operationalStatus',
        'operational_status'                => 'operationalStatusId',

        'dv_u_business_service_grouping'    => 'businessServiceGrouping',
        'u_business_service_grouping'       => 'businessServiceGroupingId',

        'u_change_notification'             => 'changeNotification',

        'dv_u_incident_owners'              => 'incidentOwners',
        'u_incident_owners'                 => 'incidentOwnersId',

        'dv_u_incident_executives'          => 'incidentExecutives',
        'u_incident_executives'             => 'incidentExecutivesId',

        'du_incident_notification'          => 'incidentNotification',

        'dv_u_operational_sensitivity'      => 'operationalSensitivity',
        'u_operational_sensitivity'         => 'operationalSensitivityId',

        'dv_u_product'                      => 'product',
        'u_product'                         => 'productId',
        'dv_u_product_leader'               => 'productLeader',
        'u_product_leader'                  => 'productLeaderId',

        'dv_u_systems_administration_leade' => 'systemsAdminLeader',
        'u_systems_administration_leade'    => 'systemsAdminLeaderId',
        'dv_u_operations_leader'            => 'operationsLeader',
        'u_operations_leader'               => 'operationsLeaderId',

        'sys_created_by'                    => 'sysCreatedBy',
        'sys_created_on'                    => 'sysCreatedOn',
        'sys_updated_by'                    => 'sysUpdatedBy',
        'sys_updated_on'                    => 'sysUpdatedOn',
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
        $this->ciTable = 'cmdb_ci_service';
        $this->format  = 'JSON';

        // create reverse name mapping hash
        foreach (self::$nameMapping as $cmdbProp => $thisProp) {
            self::$reverseNameMapping[$thisProp] = $cmdbProp;
        }
    }

    /**
     * @param      $sysId
     * @param bool $raw
     * @return mixed|CMDBBusinessService
     */
    public function getById($sysId, $raw = false)
    {
        $this->sysLog->debug('sysId=' . $sysId);
        return $this->getBySysId($sysId, $raw);
    }

    /**
     * @param      $sysId
     * @param bool $raw
     * @return mixed|CMDBBusinessService
     */
    public function getBySysId($sysId, $raw = false)
    {
        $this->sysLog->debug('sysId=' . $sysId);
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
     * @return mixed|CMDBBusinessService
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
     * @param      $name
     * @param bool $raw
     * @return CMDBBusinessService[]|mixed
     */
    public function getByNameLike($name, $raw = false)
    {
        $this->sysLog->debug("name=" . $name);
        $query   = "nameLIKE" . $name . "^ORDERBYname";
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
     * @param bool $raw
     * @return CMDBBusinessService[]|mixed
     */
    public function getAll($raw = false)
    {
        $this->sysLog->debug();
        $query   = "operational_status=1^ORDERBYname";
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
     * @param string $query
     * @return CMDBBusinessService[]
     */
    public function getByQueryString($query)
    {
        $this->sysLog->debug();
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
        return parent::updateCI($this->ciTable, $sysId, $json);
    }

    /**
     * @param CMDBBusinessService $businessService
     * @return CMDBBusinessService
     * @throws \ErrorException
     */
    public function update(CMDBBusinessService $businessService)
    {
        if (count($businessService->getChanges()) > 0) {
            // build json
            $json = '';
            foreach ($businessService->getChanges() as $prop => $o) {
                if (array_key_exists($prop, self::$reverseNameMapping)) {
                    if ($json != "") $json .= ',';
                    $json .= '"' . self::$reverseNameMapping[$prop] . '":"' . $o->modifiedValue . '"';
                } else {
                    throw new \ErrorException("Trying to set a non-existent property: " . $prop);
                }
            }
            $json = '{' . $json . '}';

            if (!property_exists($businessService, 'sysId')) {
                throw new \ErrorException("Server instance does not have a sysId defined");
            }

            $businessService->clearChanges();
            $this->updateByJson($businessService->getSysId(), $json);
            return $this->getBySysId($businessService->getSysId());
        } else {
            return $businessService;
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
     * @return CMDBBusinessService
     */
    private function _set($dbRowObj = null)
    {
        $this->sysLog->debug();

        $o = new CMDBBusinessService();
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
