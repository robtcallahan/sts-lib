<?php
/*******************************************************************************
 *
 * $Id: CMDBTask.php 73209 2013-03-14 18:18:55Z rcallaha $
 * $Date: 2013-03-14 14:18:55 -0400 (Thu, 14 Mar 2013) $
 * $Author: rcallaha $
 * $Revision: 73209 $
 * $HeadURL: https://svn.ultradns.net/svn/sts_tools/sts-lib/trunk/STS/CMDB/CMDBTask.php $
 *
 *******************************************************************************
 */

namespace STS\CMDB;



class CMDBTaskTable extends CMDBDAO
{
    protected static $reverseNameMapping = array();
    protected static $nameMapping = array(
        'sys_id'               => 'sysId',
        'sys_class_name'       => 'sysClassName',
        'number'               => 'number',

        'active'               => 'active',
        'dv_state'             => 'state',
        'state'                => 'stateId',
        'approval'             => 'approval',
        'assignment_group'     => 'assignmentGroupId',
        'dv_assignment_group'  => 'assignmentGroup',
        'u_action_taken'       => 'actionTaken',

        'u_category'           => 'category',
        'u_subcategory'        => 'subCategory',

        'request_item'         => 'requestItemId',
        'dv_request_item'      => 'requestItem',

        'dv_u_request_number'  => 'requestNumber',
        'u_request_number'     => 'requestNumberId',

        'opened_at'            => 'openedAt',
        'opened_by'            => 'openedBy',
        'dv_opened_by'         => 'openedById',
        'due_date'             => 'dueDate',
        'work_end'             => 'workEnd',
        'closed_at'            => 'closedAt',
        'dv_closed_by'         => 'closedBy',
        'close_by'             => 'closedById',

        'short_description'    => 'shortDescription',
        'work_notes'           => 'workNotes',

        'sys_created_by'       => 'sysCreatedBy',
        'sys_created_on'       => 'sysCreatedOn',
        'sys_updated_by'       => 'sysUpdatedBy',
        'sys_updated_on'       => 'sysUpdatedOn',
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
        $this->ciTable = "sc_task";
        $this->format  = "JSON";

        // create reverse name mapping hash
        foreach (self::$nameMapping as $cmdbProp => $thisProp) {
            self::$reverseNameMapping[$thisProp] = $cmdbProp;
        }
    }

    /**
     * @param      $num
     * @param bool $raw
     * @return mixed|CMDBTask
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
     * @return mixed|CMDBTask
     */
    public function getById($id, $raw = false)
    {
        $this->sysLog->debug("id=" . $id);
        return $this->getBySysId($id, $raw);
    }

    /**
     * @param      $sysId
     * @param bool $raw
     * @return mixed|CMDBTask
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
     * @param      $reqItemId
     * @param      $groupId
     * @param bool $raw
     * @return mixed|CMDBTask
     */
    public function getByRequestItemIdAndGroupId($reqItemId, $groupId, $raw = false)
    {
        $query  = "request_item={$reqItemId}^assignment_group={$groupId}";
        $result = $this->getRecord($this->ciTable, $query);
        if (!$raw) {
            return $this->_set($result);
        } else {
            return $result;
        }
    }

    /**
     * @param $category
     * @param $groupId
     * @return CMDBTask[]
     */
    public function getActiveTasksByCategoryAndGroupId($category, $groupId)
    {
        $query   = "u_category=" . $category . "^assignment_group=" . $groupId . "^active=true";
        $records = $this->getRecords($this->ciTable, $query);
        $objects = array();
        for ($i = 0; $i < count($records); $i++) {
            $objects[] = $this->_set($records[$i]);
        }
        return $objects;
    }

    /**
     * @param $groupId
     * @return CMDBTask[]
     */
    public function getActiveTermTasksByGroupId($groupId)
    {
        $query   = "u_category=Access+Termination^assignment_group={$groupId}^active=true";
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
     * @param CMDBTask $task
     * @return CMDBTask
     * @throws \ErrorException
     */
    public function update(CMDBTask $task)
    {
        if (count($task->getChanges()) > 0) {
            // build json
            $json = '';
            foreach ($task->getChanges() as $prop => $o) {
                if (array_key_exists($prop, self::$reverseNameMapping)) {
                    if ($json != "") $json .= ',';
                    $json .= '"' . self::$reverseNameMapping[$prop] . '":"' . $o->modifiedValue . '"';
                } else {
                    throw new \ErrorException("Trying to set a non-existent property: " . $prop);
                }
            }
            $json = '{' . $json . '}';

            if (!property_exists($task, 'sysId')) {
                throw new \ErrorException("Server instance does not have a sysId defined");
            }

            $task->clearChanges();
            $this->updateByJson($task->getSysId(), $json);
            return $this->getBySysId($task->getSysId());
        } else {
            return $task;
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
     * @param null $dbRowObj
     * @return CMDBTask
     */
    private function _set($dbRowObj = null)
    {
        $this->sysLog->debug();

        $o = new CMDBTask();
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
