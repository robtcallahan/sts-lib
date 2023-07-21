<?php
/*******************************************************************************
 *
 * $Id: CMDBSyncUpdate.php 73209 2013-03-14 18:18:55Z rcallaha $
 * $Date: 2013-03-14 14:18:55 -0400 (Thu, 14 Mar 2013) $
 * $Author: rcallaha $
 * $Revision: 73209 $
 * $HeadURL: https://svn.ultradns.net/svn/sts_tools/sts-lib/trunk/STS/CMDB/CMDBSyncUpdate.php $
 *
 *******************************************************************************
 */

namespace STS\CMDB;



class CMDBSyncUpdateTable extends CMDBDAO
{
    protected static $reverseNameMapping = array();
    protected static $nameMapping = array(
        'sys_id'                  => 'sysId',

        'dv_u_cmdb'               => 'cmdb',
        'u_cmdb'                  => 'cmdbId',

        'u_most_recent_discovery' => 'mostRecentDiscovery',
        'u_backup_directories'    => 'backupDirectories',
        'u_last_backup_date'      => 'lastBackupDate',
        'u_last_sync_update'      => 'lastSyncUpdate',
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
        $this->ciTable = 'u_sync_update';
        $this->format  = 'JSON';

        // create reverse name mapping hash
        foreach (self::$nameMapping as $cmdbProp => $thisProp) {
            self::$reverseNameMapping[$thisProp] = $cmdbProp;
        }
    }

    /**
     * @param      $serverId
     * @param bool $raw
     * @return mixed|CMDBSyncUpdate
     */
    public function getByServerId($serverId, $raw = false)
    {
        $this->sysLog->debug('serverId=' . $serverId);
        $query  = "u_cmdb={$serverId}";
        $result = $this->getRecord($this->ciTable, $query);
        if (!$raw) {
            return $this->_set($result);
        } else {
            return $result;
        }
    }

    /**
     * @param      $sysId
     * @param bool $raw
     * @return mixed|CMDBSyncUpdate
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
     * @param CMDBSyncUpdate $syncUpdate
     * @return CMDBSyncUpdate
     * @throws \ErrorException
     */
    public function update(CMDBSyncUpdate $syncUpdate)
    {
        $this->sysLog->debug();
        if (count($syncUpdate->getChanges()) > 0) {
            // build json
            $json = '';
            foreach ($syncUpdate->getChanges() as $prop => $o) {
                if (array_key_exists($prop, self::$reverseNameMapping)) {
                    if ($json != "") $json .= ',';
                    $json .= '"' . self::$reverseNameMapping[$prop] . '":"' . $o->modifiedValue . '"';
                } else {
                    throw new \ErrorException("Trying to set a non-existent property: " . $prop);
                }
            }
            $json = '{' . $json . '}';

            if (!property_exists($syncUpdate, 'sysId')) {
                throw new \ErrorException("Server instance does not have a sysId defined");
            }

            $syncUpdate->clearChanges();
            $this->updateByJson($syncUpdate->getSysId(), $json);
            return $this->getBySysId($syncUpdate->getSysId());
        } else {
            return $syncUpdate;
        }
    }

    public function create(CMDBSyncUpdate $syncUpdate)
    {
        $this->sysLog->debug();
        if (count($syncUpdate->getChanges()) > 0) {
            // build json
            $json = '';
            foreach ($syncUpdate->getChanges() as $prop => $o) {
                if (array_key_exists($prop, self::$reverseNameMapping)) {
                    if ($json != "") $json .= ',';
                    $json .= '"' . self::$reverseNameMapping[$prop] . '":"' . $o->modifiedValue . '"';
                } else {
                    throw new \ErrorException("Trying to set a non-existent property: " . $prop);
                }
            }
            $json = '{' . $json . '}';

            if (!property_exists($syncUpdate, 'sysId')) {
                throw new \ErrorException("Server instance does not have a sysId defined");
            }

            $syncUpdate->clearChanges();
            $return = $this->createByJson($json);
            if (property_exists($return, 'records')) {
                if (array_key_exists(0, $return->records)) {
                    return $this->_set($return->records[0]);
                } else {
                    return $syncUpdate;
                }
            } else {
                return $this->getByServerId($syncUpdate->getCmdbId());
            }
        } else {
            return $syncUpdate;
        }
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


    // *******************************************************************************
    // * Getters and Setters
    // *****************************************************************************

    /**
     * @param $logLevel
     * @return $this|void
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
     * @return CMDBSyncUpdate
     */
    private function _set($dbRowObj = null)
    {
        $this->sysLog->debug();

        $o = new CMDBSyncUpdate();
        foreach (self::$nameMapping as $cmdbProp => $modelProp) {
            if ($dbRowObj && property_exists($dbRowObj, $cmdbProp)) {
                $o->set($modelProp, $dbRowObj->$cmdbProp);
            } else {
                $o->set($modelProp, null);
            }
        }
        $o->clearChanges();
        return $o;
    }
}
