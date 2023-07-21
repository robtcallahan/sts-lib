<?php
/*******************************************************************************
 *
 * $Id: CMDBSyncUpdate.php 79018 2013-09-19 00:06:03Z rcallaha $
 * $Date: 2013-09-18 20:06:03 -0400 (Wed, 18 Sep 2013) $
 * $Author: rcallaha $
 * $Revision: 79018 $
 * $HeadURL: https://svn.ultradns.net/svn/sts_tools/sts-lib/trunk/STS/CMDB/CMDBSyncUpdate.php $
 *
 *******************************************************************************
 */

namespace STS\CMDB;

class CMDBSyncUpdate
{
    protected $sysId;
    protected $cmdb;
    protected $cmdbId;
    protected $mostRecentDiscovery;
    protected $backupDirectories;
    protected $lastBackupDate;
    protected $lastSyncUpdate;

    protected $changes = array();

    /**
     * @return string
     */
    public function __toString() {
        $return = "";
        foreach (CMDBSyncUpdateTable::getNameMapping() as $prop) {
            $return .= sprintf("%-25s => %s\n", $prop, $this->$prop);
        }
        return $return;
    }

    /**
     * @return object
     */
    public function toObject() {
        $obj = (object)array();
        foreach (CMDBSyncUpdateTable::getNameMapping() as $prop) {
            $obj->$prop = $this->$prop;
        }
        return $obj;
    }


    // *******************************************************************************
    // * Getters and Setters
    // *****************************************************************************

    /**
     * @param $prop
     * @return mixed
     */
    public function get($prop) {
        return $this->$prop;
    }

    /**
     * @param $prop
     * @param $value
     * @return mixed
     */
    public function set($prop, $value) {
        // update the changes array to keep track of this properties orig and new values
        if ($value != $this->$prop) {
            if (!array_key_exists($prop, $this->changes)) {
                $this->changes[$prop] = (object)array(
                    'originalValue' => $this->$prop,
                    'modifiedValue' => $value
                );
            } else {
                $this->changes[$prop]->modifiedValue = $value;
            }
            $this->$prop = $value;
        }
    }

    /**
     * @return array
     */
    public function getChanges() {
        return $this->changes;
    }

    /**
     *
     */
    public function clearChanges() {
        $this->changes = array();
    }

    /**
     * @param $value
     */
    private function updateChanges($value) {
        $trace = debug_backtrace();

        // get the calling method name, eg., setSysId
        $callerMethod = $trace[1]["function"];

        // perform a replace to remove "set" from the method name and change first letter to lowercase
        // so, setSysId becomes sysId. This will be the property name that needs to be added to the changes array
        $prop = preg_replace_callback(
            "/^set(\w)/",
            function ($matches) {
                return strtolower($matches[1]);
            },
            $callerMethod
        );

        // update the changes array to keep track of this properties orig and new values
        if (!array_key_exists($prop, $this->changes)) {
            $this->changes[$prop] = (object)array(
                'originalValue' => $this->$prop,
                'modifiedValue' => $value
            );
        } else {
            $this->changes[$prop]->modifiedValue = $value;
        }
    }

    /**
     * @param $backupDirectories
     */
    public function setBackupDirectories($backupDirectories) {
        $this->updateChanges(func_get_arg(0));
        $this->backupDirectories = $backupDirectories;
    }

    /**
     * @return mixed
     */
    public function getBackupDirectories() {
        return $this->backupDirectories;
    }

    /**
     * @param $cmdb
     */
    public function setCmdb($cmdb) {
        $this->updateChanges(func_get_arg(0));
        $this->cmdb = $cmdb;
    }

    /**
     * @return mixed
     */
    public function getCmdb() {
        return $this->cmdb;
    }

    /**
     * @param $cmdbId
     */
    public function setCmdbId($cmdbId) {
        $this->updateChanges(func_get_arg(0));
        $this->cmdbId = $cmdbId;
    }

    /**
     * @return mixed
     */
    public function getCmdbId() {
        return $this->cmdbId;
    }

    /**
     * @param $lastBackupDate
     */
    public function setLastBackupDate($lastBackupDate) {
        $this->updateChanges(func_get_arg(0));
        $this->lastBackupDate = $lastBackupDate;
    }

    /**
     * @return mixed
     */
    public function getLastBackupDate() {
        return $this->lastBackupDate;
    }

    /**
     * @param $lastSyncUpdate
     */
    public function setLastSyncUpdate($lastSyncUpdate) {
        $this->updateChanges(func_get_arg(0));
        $this->lastSyncUpdate = $lastSyncUpdate;
    }

    /**
     * @return mixed
     */
    public function getLastSyncUpdate() {
        return $this->lastSyncUpdate;
    }

    /**
     * @param $mostRecentDiscovery
     */
    public function setMostRecentDiscovery($mostRecentDiscovery) {
        $this->updateChanges(func_get_arg(0));
        $this->mostRecentDiscovery = $mostRecentDiscovery;
    }

    /**
     * @return mixed
     */
    public function getMostRecentDiscovery() {
        return $this->mostRecentDiscovery;
    }

    /**
     * @param $sysId
     */
    public function setSysId($sysId) {
        $this->updateChanges(func_get_arg(0));
        $this->sysId = $sysId;
    }

    /**
     * @return mixed
     */
    public function getSysId() {
        return $this->sysId;
    }

}
