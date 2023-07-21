<?php
/*******************************************************************************
 *
 * $Id: CMDBRelationship.php 73209 2013-03-14 18:18:55Z rcallaha $
 * $Date: 2013-03-14 14:18:55 -0400 (Thu, 14 Mar 2013) $
 * $Author: rcallaha $
 * $Revision: 73209 $
 * $HeadURL: https://svn.ultradns.net/svn/sts_tools/sts-lib/trunk/STS/CMDB/CMDBRelationship.php $
 *
 *******************************************************************************
 */

namespace STS\CMDB;



class CMDBRelationshipTable extends CMDBDAO
{
    protected static $nameMapping = array(
        'sys_id'              => 'sysId',
        'sys_class_name'      => 'sysClassName',
        'cmdb_ci'             => 'cmdbCi',

        'parent'              => 'parent',
        'child'               => 'child',
        'type'                => 'type',
        'connection_strength' => 'connectionStrength',
        'percent_outage'      => 'percentOutage',

        "sys_created_by"      => "sysCreatedBy",
        "sys_created_on"      => "sysCreatedOn",
        "sys_updated_by"      => "sysUpdatedBy",
        "sys_updated_on"      => "sysUpdatedOn",
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
        $this->ciTable = "cmdb_rel_ci";
        $this->format  = "JSON";
    }

    /**
     * @param      $parentId
     * @param      $childId
     * @param      $typeId
     * @param bool $raw
     * @return mixed|CMDBBusinessService
     */
    public function getByParentChildType($parentId, $childId, $typeId, $raw = false)
    {
        $this->sysLog->debug("parentId=" . $parentId . ", childId=" . $childId . ", typeId=" . $typeId);
        $query  = "parent.sys_id={$parentId}^child.sys_id={$childId}^type.sys_id={$typeId}";
        $result = $this->getRecord($this->ciTable, $query);
        if (!$raw) {
            return $this->_set($result);
        } else {
            return $result;
        }
    }

    /**
     * @param      $childId
     * @param      $typeId
     * @param bool $raw
     * @return CMDBRelationship[]
     */
    public function getParents($childId, $typeId, $raw = false)
    {
        $this->sysLog->debug("childId=" . $childId . ", typeId=" . $typeId);
        $query   = "child.sys_id={$childId}^type.sys_id={$typeId}";
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
     * @param      $parentId
     * @param      $typeId
     * @param bool $raw
     * @return CMDBRelationship[]
     */
    public function getChildren($parentId, $typeId, $raw = false)
    {
        $this->sysLog->debug("parentId=" . $parentId . ", typeId=" . $typeId);
        $query   = "parent.sys_id={$parentId}^type.sys_id={$typeId}";
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
     * @param CMDBRelationship $relation
     * @return mixed|object
     */
    public function create(CMDBRelationship $relation)
    {
        $this->sysLog->debug();
        $props = array();
        foreach (array("parent", "child", "type") as $p) {
            $props[] = '"' . $p . '":"' . $relation->get($p) . '"';
        }
        $json = '{' . implode(",", $props) . '}';
        return parent::createCI($this->ciTable, $json);
    }

    /**
     * @param CMDBRelationship $relation
     * @return mixed|object
     */
    public function update(CMDBRelationship $relation)
    {
        $this->sysLog->debug();
        $props = array();
        foreach (array("parent", "child", "type") as $p) {
            $props[] = '"' . $p . '":"' . $relation->get($p) . '"';
        }
        $json = '{' . implode(",", $props) . '}';
        return parent::updateCI($this->ciTable, $relation->getSysId(), $json);
    }

    /**
     * @param $sysId
     * @return mixed|object
     */
    public function delete($sysId)
    {
        $this->sysLog->debug("sysId=" . $sysId);
        return parent::deleteCI($this->ciTable, $sysId);
    }

    /**
     * @param $sysId
     * @param $json
     * @return mixed|object
     */
    public function updateByJson($sysId, $json)
    {
        $this->sysLog->debug("sysId=" . $sysId);
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
     * @return CMDBBusinessService
     */
    private function _set($dbRowObj = null)
    {
        $this->sysLog->debug();

        $o = new CMDBRelationship();
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
