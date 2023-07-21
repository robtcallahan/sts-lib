<?php
/*******************************************************************************
 *
 * $Id: CMDBRequestItem.php 73209 2013-03-14 18:18:55Z rcallaha $
 * $Date: 2013-03-14 14:18:55 -0400 (Thu, 14 Mar 2013) $
 * $Author: rcallaha $
 * $Revision: 73209 $
 * $HeadURL: https://svn.ultradns.net/svn/sts_tools/sts-lib/trunk/STS/CMDB/CMDBRequestItem.php $
 *
 *******************************************************************************
 */

namespace STS\CMDB;

use STS\Util\SysLog;

class CMDBRequestItemTable extends CMDBDAO
{
    protected static $nameMapping = array(
        'sys_id'                => 'sysId',
        'sys_class_name'        => 'sysClassName',
        'number'                => 'number',

        'request'               => 'requestId',
        'dv_request'            => 'request',
        'u_request_category'    => 'requestCategory',
        'u_request_subcategory' => 'requestSubcategory',

        'dv_u_business_service' => 'businessService',
        'u_business_service'    => 'businessServiceId',
        'dv_u_cmdb_subsystem'   => 'subsystem',
        'u_cmdb_subsystem'      => 'subsystemId',

        'active'                => 'active',
        'approval'              => 'approval',
        'assigned_to'           => 'assignedToId',
        'dv_assigned_to'        => 'assignedTo',
        'cat_item'              => 'catItemId',
        'dv_cat_item'           => 'catItem',

        'opened_at'             => 'openedAt',
        'opened_by'             => 'openedBy',
        'dv_opened_by'          => 'openedById',
        'due_date'              => 'dueDate',
        'closed_at'             => 'closedAt',
        'dv_closed_by'          => 'closedBy',
        'close_by'              => 'closedById',

        'sys_created_by'        => 'sysCreatedBy',
        'sys_created_on'        => 'sysCreatedOn',
        'sys_updated_by'        => 'sysUpdatedBy',
        'sys_updated_on'        => 'sysUpdatedOn',
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
        $this->ciTable = "sc_req_item";
        $this->format  = "JSON";
    }

    /**
     * @param      $num
     * @param bool $raw
     * @return mixed|CMDBRequestItem
     */
    public function getByNumber($num, $raw = false)
    {
        $this->sysLog->debug("num=" . $num);
        $query  = "number={$num}";
        $result = $this->getRecord($this->ciTable, $query);
        if (!$raw) {
            return $this->_set($result);
        } else {
            return $result;
        }
    }

    /**
     * @param      $id
     * @param bool $raw
     * @return mixed|CMDBRequestItem
     */
    public function getById($id, $raw = false)
    {
        $this->sysLog->debug("id=" . $id);
        return $this->getBySysId($id, $raw);
    }

    /**
     * @param      $sysId
     * @param bool $raw
     * @return mixed|CMDBRequestItem
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
     * @param      $reqId
     * @param bool $raw
     * @return mixed|CMDBRequestItem
     */
    public function getByRequestId($reqId, $raw = false)
    {
        $this->sysLog->debug("reqId=" . $reqId);
        $query  = "request={$reqId}";
        $result = $this->getRecord($this->ciTable, $query);
        if (!$raw) {
            return $this->_set($result);
        } else {
            return $result;
        }
    }

    /**
     * @return CMDBRequestItem[]
     */
    public function getOpenAccTerms()
    {
        $query   = "u_request_category=Access+Management^u_request_subcategory=Access+Termination^active=true^approval=approved";
        $records = $this->getRecords($this->ciTable, $query);
        $objects = array();
        for ($i = 0; $i < count($records); $i++) {
            $objects[] = $this->_set($records[$i]);
        }
        return $objects;
    }


    // *****************************************************************************
    // * Getters and Setters
    // *****************************************************************************

    /**
     * @param int $logLevel
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
     * @param $format
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
     * @param null $dbRowObj
     * @return CMDBRequestItem
     */
    private function _set($dbRowObj = null)
    {
        $this->sysLog->debug();

        $o = new CMDBRequestItem();
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
