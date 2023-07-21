<?php
/*******************************************************************************
 *
 * $Id: CMDBUserGroupTable.php 81814 2013-12-09 19:40:39Z rcallaha $
 * $Date: 2013-12-09 14:40:39 -0500 (Mon, 09 Dec 2013) $
 * $Author: rcallaha $
 * $Revision: 81814 $
 * $HeadURL: https://svn.ultradns.net/svn/sts_tools/sts-lib/trunk/STS/CMDB/CMDBUserGroupTable.php $
 *
 *******************************************************************************
 */

namespace STS\CMDB;



class CMDBUserGroupTable extends CMDBDAO
{
    protected static $reverseNameMapping = array();
    protected static $nameMapping = array(
        'sys_id'              => 'sysId',
        'name'                => 'name',

        'active'              => 'active',

        'dv_manager'          => 'manager',
        'manager'             => 'managerId',
        'email'               => 'email',

        'u_netcool_gid_name'  => 'netcoolGidName',
        'u_netcool_owner_gid' => 'netcoolOwnerGid',
        'u_oncall_team'       => 'oncallTeam', // true or false

        'sys_created_by'      => 'sysCreatedBy',
        'sys_created_on'      => 'sysCreatedOn',
        'sys_updated_by'      => 'sysUpdatedBy',
        'sys_updated_on'      => 'sysUpdatedOn',
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
        $this->ciTable = "sys_user_group_list";
        $this->format  = "JSON";

        // create reverse name mapping hash
        foreach (self::$nameMapping as $cmdbProp => $thisProp) {
            self::$reverseNameMapping[$thisProp] = $cmdbProp;
        }
    }

    /**
     * @param      $sysId
     * @param bool $raw
     * @return mixed|CMDBUser
     */
    public function getById($sysId, $raw = false)
    {
        $this->sysLog->debug("sysId=" . $sysId);
        return $this->getBySysId($sysId, $raw);
    }

    /**
     * @param      $sysId
     * @param bool $raw
     * @return mixed|CMDBUser
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
     * @return mixed|CMDBUser
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
     * @param CMDBUserGroup $userGroup
     * @return CMDBUserGroup
     * @throws \ErrorException
     */
    public function update(CMDBUserGroup $userGroup)
    {
        if (count($userGroup->getChanges()) > 0) {
            // build json
            $json = '';
            foreach ($userGroup->getChanges() as $prop => $o) {
                if (array_key_exists($prop, self::$reverseNameMapping)) {
                    if ($json != "") $json .= ',';
                    $json .= '"' . self::$reverseNameMapping[$prop] . '":"' . $o->modifiedValue . '"';
                } else {
                    throw new \ErrorException("Trying to set a non-existent property: " . $prop);
                }
            }
            $json = '{' . $json . '}';

            if (!property_exists($userGroup, 'sysId')) {
                throw new \ErrorException("Server instance does not have a sysId defined");
            }

            $userGroup->clearChanges();
            $this->updateByJson($userGroup->getSysId(), $json);
            return $this->getBySysId($userGroup->getSysId());
        } else {
            return $userGroup;
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
     * @return CMDBUserGroup
     */
    private function _set($dbRowObj = null)
    {
        $this->sysLog->debug();

        $o = new CMDBUserGroup();
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
