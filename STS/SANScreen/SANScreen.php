<?php
/*******************************************************************************
 *
 * $Id: SANScreen.php 82460 2014-01-04 15:38:17Z rcallaha $
 * $Date: 2014-01-04 10:38:17 -0500 (Sat, 04 Jan 2014) $
 * $Author: rcallaha $
 * $Revision: 82460 $
 * $HeadURL: https://svn.ultradns.net/svn/sts_tools/sts-lib/trunk/STS/SANScreen/SANScreen.php $
 *
 *******************************************************************************
 */

namespace STS\SANScreen;

use STS\Util\SysLog;
use STS\DB\MySqlDB;

class SANScreen
{
    protected $db;

    protected $sysLog;
    protected $logLevel;

    public function __construct($config = null)
    {
        if ($config && is_array($config)) {
            // new config method: passing the config into the constructor
            // check for all needed config params

            // appName & logLevel. If missing, assign defaults
            $config['appName']  = array_key_exists('appName', $config) ? $config['appName'] : 'sanscreen';
            $config['logLevel'] = array_key_exists('logLevel', $config) ? $config['logLevel'] : SysLog::NOTICE;

            // check for database details
            if (!array_key_exists('dbIndex', $config) || !$config['dbIndex']) throw new \ErrorException("dbIndex not defined in config");
            $dbIndex = $config['dbIndex'];

            // check for database connection credentials
            if (!array_key_exists('databases', $config)) throw new \ErrorException("databases category not defined in config");
            if (!array_key_exists($dbIndex, $config['databases'])) throw new \ErrorException("hpsim database not defined in config['databases");
            if (!array_key_exists('server', $config['databases'][$dbIndex])) throw new \ErrorException("server not defined in config['databases'][{$dbIndex}]");
            if (!array_key_exists('type', $config['databases'][$dbIndex])) throw new \ErrorException("type not defined in config['databases'][{$dbIndex}]");
            if (!array_key_exists('username', $config['databases'][$dbIndex])) throw new \ErrorException("username not defined in config['databases'][{$dbIndex}]");
            if (!array_key_exists('password', $config['databases'][$dbIndex])) throw new \ErrorException("password not defined in config['databases'][{$dbIndex}]");
            if (!array_key_exists('database', $config['databases'][$dbIndex])) throw new \ErrorException("database not defined in config['databases'][{$dbIndex}]");
        } else {
            $config = array();

            // old method of $GLOBALS or a local config file
            if (array_key_exists('config', $GLOBALS)) {
                $configOld = $GLOBALS['config'];
            } else {
                $configDir  = __DIR__ . "/config";
                $configFile = "config.php";
                if (is_dir($configDir) && is_file($configDir . "/" . $configFile)) {
                    $configOld = require($configDir . "/" . $configFile);
                } else {
                    throw new \ErrorException("Could not find config file: " . $configDir . "/" . $configFile);
                }
            }

            $dbIndex           = 'sanscreen';
            $config['dbIndex'] = $dbIndex;

            // check for all needed config params
            if (is_object($configOld)) {
                // config is an object - the "old" way of doing things
                $config['appName']  = property_exists($configOld, 'appName') ? $configOld->appName : $dbIndex;
                $config['logLevel'] = property_exists($configOld, 'logLevel') ? $configOld->logLevel : SysLog::NOTICE;

                if (!property_exists($configOld, 'databases')) throw new \ErrorException("databases category not defined in config");
                if (!property_exists($configOld->databases, $dbIndex)) throw new \ErrorException("{$dbIndex} database not defined in config->databases");
                if (!property_exists($configOld->databases->$dbIndex, 'server')) throw new \ErrorException("server not defined in config->databases->{$dbIndex}");
                if (!property_exists($configOld->databases->$dbIndex, 'type')) throw new \ErrorException("type not defined in config->databases->{$dbIndex}");
                if (!property_exists($configOld->databases->$dbIndex, 'username')) throw new \ErrorException("username not defined in config->databases->{$dbIndex}");
                if (!property_exists($configOld->databases->$dbIndex, 'password')) throw new \ErrorException("password not defined in config->databases->{$dbIndex}");
                if (!property_exists($configOld->databases->$dbIndex, 'database')) throw new \ErrorException("database not defined in config->databases->{$dbIndex}");

                $config['databases'] = array(
                    $dbIndex => array(
                        'server'   => $configOld->databases->$dbIndex->server,
                        'type'     => $configOld->databases->$dbIndex->type,
                        'username' => $configOld->databases->$dbIndex->username,
                        'password' => $configOld->databases->$dbIndex->password,
                        'database' => $configOld->databases->$dbIndex->database
                    )
                );
            } else {
                // config is an array
                $config['appName']  = array_key_exists('appName', $configOld) ? $configOld['appName'] : $dbIndex;
                $config['logLevel'] = array_key_exists('logLevel', $configOld) ? $configOld['logLevel'] : SysLog::NOTICE;

                if (!array_key_exists('databases', $configOld)) throw new \ErrorException("databases category not defined in config");
                if (!array_key_exists($dbIndex, $configOld['databases'])) throw new \ErrorException("{$dbIndex} database not defined in config['databases']");
                if (!array_key_exists('server', $configOld['databases'][$dbIndex])) throw new \ErrorException("server not defined in config['databases'][{$dbIndex}]");
                if (!array_key_exists('type', $configOld['databases'][$dbIndex])) throw new \ErrorException("type not defined in config['databases'][{$dbIndex}]");
                if (!array_key_exists('username', $configOld['databases'][$dbIndex])) throw new \ErrorException("username not defined in config['databases'][{$dbIndex}]");
                if (!array_key_exists('password', $configOld['databases'][$dbIndex])) throw new \ErrorException("password not defined in config['databases'][{$dbIndex}]");
                if (!array_key_exists('database', $configOld['databases'][$dbIndex])) throw new \ErrorException("database not defined in config['databases'][{$dbIndex}]");

                $config['databases'] = array(
                    $dbIndex => array(
                        'server'   => $configOld['databases'][$dbIndex]['server'],
                        'type'     => $configOld['databases'][$dbIndex]['type'],
                        'username' => $configOld['databases'][$dbIndex]['username'],
                        'password' => $configOld['databases'][$dbIndex]['password'],
                        'database' => $configOld['databases'][$dbIndex]['database']
                    )
                );
            }
        }

        // Set up SysLog
        $this->sysLog   = SysLog::singleton($config['appName']);
        $this->logLevel = $config['logLevel'];
        $this->sysLog->debug();

        // instantiate our database connection
        $this->db = new MySqlDB($config);
    }

    /**
     * @param        $hostId
     * @param string $orderBy
     * @param string $dir
     * @return SANScreenArray[]
     */
    public function getArraysByHostId($hostId, $orderBy = "name", $dir = "asc")
    {
        $this->sysLog->debug();
        $sql = "SELECT    DISTINCT
                          a.*
                FROM      host h,
                          path p,
                          array a
                WHERE     p.hostId = h.id
                  AND     p.arrayId = a.id
                  AND     h.id = {$hostId}
                ORDER BY  a.{$orderBy} $dir;";
        $this->db->connect();
        $rows = $this->db->getAllObjects($sql);
        $this->db->close();
        $arrays = array();
        for ($i = 0; $i < count($rows); $i++) {
            $arrays[] = $this->_setArray($rows[$i]);
        }
        return $arrays;
    }

    /**
     * @param        $arrayId
     * @param string $orderBy
     * @param string $dir
     * @return SANScreenHost[]
     */
    public function getHostsByArrayId($arrayId, $orderBy = "name", $dir = "asc")
    {
        $this->sysLog->debug();
        $sql = "SELECT    DISTINCT
                          h.*
                FROM      host h,
                          path p,
                          array a
                WHERE     p.hostId = h.id
                  AND     p.arrayId = a.id
                  AND     a.id = {$arrayId}
                ORDER BY  a.{$orderBy} $dir;";
        $this->db->connect();
        $rows = $this->db->getAllObjects($sql);
        $this->db->close();
        $hosts = array();
        for ($i = 0; $i < count($rows); $i++) {
            $hosts[] = $this->_setHost($rows[$i]);
        }
        return $hosts;
    }

    /**
     * @param $tableName
     * @return array
     */
    public function getIdHash($tableName)
    {
        $this->sysLog->debug();
        $sql = "SELECT id FROM {$tableName} ORDER BY id";
        $this->db->connect();
        $rows = $this->db->getAllObjects($sql);
        $this->db->close();

        $idHash = array();
        for ($i = 0; $i < count($rows); $i++) {
            $idHash[$rows[$i]->id] = $rows[$i]->id;
        }
        return $idHash;
    }

    /**
     * @param $arrayId
     * @return array
     */
    public function getLobsByArrayId($arrayId)
    {
        $this->sysLog->debug();
        $this->db->connect();
        $sql  = "SELECT DISTINCT
		               h.businessService, h.subsystem, h.opsSuppMgr, h.opsSuppGrp
                FROM   array a,
                       path p,
                       host h
		        WHERE  arrayId = {$arrayId}
		          AND  p.arrayId = a.id
		          AND  h.id = p.hostId
		        ORDER BY h.businessService, h.subsystem ASC;";
        $rows = $this->db->getAllObjects($sql);
        $this->db->close();
        return $rows;
    }

    /**
     * @param null $query
     * @return array
     */
    public function getArraysAndNodeIdsWithSwitchId($query = null)
    {
        $this->sysLog->debug();
        $this->db->connect();
        if ($query) {
            $sql = "SELECT    DISTINCT
			                  lower(a.name) AS name,
							  concat(s.id,'/arrays/',a.id) AS node
					FROM      array a
					LEFT JOIN port p1 ON a.id = p1.deviceId
					LEFT JOIN port p2 ON p1.connectedPortId = p2.id
					LEFT JOIN switch s ON p2.deviceId = s.id
					WHERE     lower(a.name) LIKE lower('{$query}%')
					  AND     s.id IS NOT null
					ORDER BY  a.name;";
        } else {
            $sql = "SELECT    DISTINCT
			                  lower(a.name) AS name,
							  concat(s.id,'/arrays/',a.id) AS node
					FROM      array a
					LEFT JOIN port p1 ON a.id = p1.deviceId
					LEFT JOIN port p2 ON p1.connectedPortId = p2.id
					LEFT JOIN switch s ON p2.deviceId = s.id
					WHERE     s.id IS NOT null
					ORDER BY  a.name;";
        }
        $rows    = $this->db->getAllObjects($sql);
        $results = array();
        for ($i = 0; $i < count($rows); $i++) {
            $results[] = array(
                "name" => $rows[$i]->name,
                "node" => $rows[$i]->node
            );
        }
        $this->db->close();
        return $results;
    }

    /**
     * @param null $query
     * @return array
     */
    public function getHostNamesAndNodeIdsWithSwitchId($query = null)
    {
        $this->sysLog->debug();
        $this->db->connect();
        if ($query) {
            $sql = "SELECT    DISTINCT
			                  lower(h.name) AS name,
							  concat(s.id,'/hosts/',h.id) AS node
					FROM      host h
					LEFT JOIN port p1 ON h.id = p1.deviceId
					LEFT JOIN port p2 ON p1.connectedPortId = p2.id
					LEFT JOIN switch s ON p2.deviceId = s.id
					WHERE     lower(h.name) LIKE lower('{$query}%')
					  AND     s.id IS NOT null
					ORDER BY  h.name;";
        } else {
            $sql = "SELECT    DISTINCT
			                  lower(h.name) AS name,
							  concat(s.id,'/hosts/',h.id) AS node
					FROM      host h
					LEFT JOIN port p1 ON h.id = p1.deviceId
					LEFT JOIN port p2 ON p1.connectedPortId = p2.id
					LEFT JOIN switch s ON p2.deviceId = s.id
					WHERE     s.id IS NOT null
					ORDER BY  h.name;";
        }
        $rows    = $this->db->getAllObjects($sql);
        $results = array();
        for ($i = 0; $i < count($rows); $i++) {
            $results[] = array(
                "name" => $rows[$i]->name,
                "node" => $rows[$i]->node
            );
        }
        $this->db->close();
        return $results;
    }

    /**
     * @param null $query
     * @return array
     */
    public function getVMsAndNodeIdsWithSwitchId($query = null)
    {
        $this->sysLog->debug();
        $this->db->connect();
        if ($query) {
            $sql = "SELECT    DISTINCT
			                  lower(v.name) AS name,
							  concat(s.id,'/hosts/',h.id,'/',v.id) AS node
					FROM      host h
					LEFT JOIN port p1 ON h.id = p1.deviceId
					LEFT JOIN port p2 ON p1.connectedPortId = p2.id
					LEFT JOIN switch s ON p2.deviceId = s.id
					LEFT JOIN vm v ON v.hostId = h.id
					WHERE     lower(v.name) LIKE lower('{$query}%')
					  AND     s.id IS NOT null
					ORDER BY  v.name;";
        } else {
            $sql = "SELECT    DISTINCT
			                  lower(v.name) AS name,
							  concat(s.id,'/hosts/',h.id,'/',v.id) AS node
					FROM      host h
					LEFT JOIN port p1 ON h.id = p1.deviceId
					LEFT JOIN port p2 ON p1.connectedPortId = p2.id
					LEFT JOIN switch s ON p2.deviceId = s.id
					LEFT JOIN vm v ON v.hostId = h.id
					WHERE     s.id IS NOT null
					ORDER BY  v.name;";
        }
        $rows    = $this->db->getAllObjects($sql);
        $results = array();
        for ($i = 0; $i < count($rows); $i++) {
            $results[] = array(
                "name" => $rows[$i]->name,
                "node" => $rows[$i]->node
            );
        }
        $this->db->close();
        return $results;
    }

    /**
     * @param null $query
     * @return array
     */
    public function getHostNamesAndNodeIds($query = null)
    {
        $this->sysLog->debug();
        $this->db->connect();
        if ($query) {
            $sql = "select distinct
        	               lower(h.name) as name, concat(a.id, '/hosts/', h.id) as node
                    from   host h,
                           array a,
                           path p
                    where  h.name like '" . $query . "%'
                      and  p.hostId = h.id
                      and  a.id = p.arrayId
                    order  by h.name;";
        } else {
            $sql = "SELECT DISTINCT
        	               lower(h.name) AS name, concat(a.id, '/hosts/', h.id) AS node
                    FROM   host h,
                           array a,
                           path p
                    WHERE  p.hostId = h.id
                      AND  a.id = p.arrayId
                    ORDER  BY h.name;";
        }
        $rows    = $this->db->getAllObjects($sql);
        $results = array();
        for ($i = 0; $i < count($rows); $i++) {
            $results[] = array(
                "name" => $rows[$i]->name,
                "node" => $rows[$i]->node
            );
        }
        $this->db->close();
        return $results;
    }

    /**
     * @param null $query
     * @return array
     */
    public function getVMsAndNodeIds($query = null)
    {
        $this->sysLog->debug();
        $this->db->connect();
        if ($query) {
            $sql = "SELECT DISTINCT
        	               lower(v.name) AS name, concat(a.id, '/hosts/', h.id, '/vms/', v.id) AS node
                    FROM   host h,
                           array a,
                           path p,
                           vm v
                    WHERE  v.name LIKE '{$query}%'
                      AND  v.hostId = h.id
                      AND  p.hostId = h.id
                      AND  a.id = p.arrayId
                    ORDER  BY v.name;";
        } else {
            $sql = "SELECT DISTINCT
        	               lower(v.name) AS name, concat(a.id, '/hosts/', h.id, '/vms/', v.id) AS node
                    FROM   host h,
                           array a,
                           path p,
                           vm v
                    WHERE  v.hostId = h.id
                      AND  p.hostId = h.id
                      AND  a.id = p.arrayId
                    ORDER  BY v.name;";
        }
        $rows    = $this->db->getAllObjects($sql);
        $results = array();
        for ($i = 0; $i < count($rows); $i++) {
            $results[] = array(
                "name" => $rows[$i]->name,
                "node" => $rows[$i]->node
            );
        }
        $this->db->close();
        return $results;
    }

    /**
     * @param null $query
     * @return array
     */
    public function getWwnsAndNodeIds($query = null)
    {
        $this->sysLog->debug();
        $this->db->connect();
        if ($query) {
            $sql  = "SELECT DISTINCT
        	               p.wwn AS name, concat(a.id, '/hosts/', h.id, '/ports/', p.id) AS node
                    FROM   port p,
                           array a,
                           host h,
                           path t
                    WHERE  h.id = p.deviceId
                      AND  t.hostId = h.id
                      AND  a.id = t.arrayId
                      AND  p.wwn LIKE '{$query}%'
                    ORDER  BY p.wwn;";
            $rows = $this->db->getAllObjects($sql);
        } else {
            $sql  = "SELECT DISTINCT
        	               p.wwn AS name, concat(a.id, '/hosts/', h.id, '/ports/', p.id) AS node
                    FROM   port p,
                           array a,
                           host h,
                           path t
                    WHERE  h.id = p.deviceId
                      AND  t.hostId = h.id
                      AND  a.id = t.arrayId
                    ORDER  BY p.wwn;";
            $rows = $this->db->getAllObjects($sql);
        }
        $this->db->close();
        $results = array();
        for ($i = 0; $i < count($rows); $i++) {
            $results[] = array(
                "name" => $rows[$i]->name,
                "node" => $rows[$i]->node
            );
        }
        return $results;
    }

    /**
     * @param $switchId
     * @return array
     */
    public function getHostsBySwitchId($switchId)
    {
        $this->sysLog->debug();
        $sql = "SELECT    DISTINCT
		                  h.id, lower(h.name) AS name, h.sysId, h.environment, h.cmInstallStatus,
		                  h.businessService, h.subsystem, h.opsSuppMgr, h.opsSuppGrp,
		                  p2.name AS port, p2.speed, p2.status, p2.state
                FROM      host h
				LEFT JOIN port p1 ON h.id = p1.deviceId
                LEFT JOIN port p2 ON p1.connectedPortId = p2.id
                LEFT JOIN switch s ON p2.deviceId = s.id
                WHERE     s.id = {$switchId}
                ORDER BY  h.name;";

        $this->db->connect();
        $rows = $this->db->getAllObjects($sql);
        $this->db->close();
        return $rows;
        /*
        $hosts = array();
        for ($i = 0; $i < count($rows); $i++) {
            $hosts[] = $this->_setHost($rows[$i]);
        }
        return $hosts;
        */
    }

    /* Used */
    /**
     * @param $switchId
     * @return array
     */
    public function getArraysBySwitchId($switchId)
    {
        $this->sysLog->debug();
        $sql = "SELECT    DISTINCT a.*, lower(a.name) AS name
                FROM      array a
				LEFT JOIN port p1 ON a.id = p1.deviceId
                LEFT JOIN port p2 ON p1.connectedPortId = p2.id
                LEFT JOIN switch s ON p2.deviceId = s.id
                WHERE     s.id = {$switchId}
                ORDER BY  a.name;";
        $this->db->connect();
        $rows = $this->db->getAllObjects($sql);
        $this->db->close();
        return $rows;
        /*
        $arrays = array();
        for ($i = 0; $i < count($rows); $i++) {
            $arrays[] = $this->_setArray($rows[$i]);
        }
        return $arrays;
        */
    }

    /* Used */
    /**
     * @param $switchId
     * @return array
     */
    public function getSwitchBladesBySwitchId($switchId)
    {
        $this->sysLog->debug();
        $sql = "SELECT DISTINCT substr(name, 3, locate('/', name) - 3) AS blade
                FROM   port
                WHERE  deviceId = {$switchId}
                  AND  locate('/', name) != 0
                ORDER BY blade+0;";
        $this->db->connect();
        $results = $this->db->getAllObjects($sql);
        $this->db->close();
        return $results;
    }

    /* Used */
    /**
     * @param $switchId
     * @param $slotNum
     * @return array
     */
    public function getHostsBySwitchIdAndSlotNumber($switchId, $slotNum)
    {
        $this->sysLog->debug();
        $sql = "SELECT    DISTINCT 'host' AS objType,
		                  h.id, lower(h.name) AS name,
		                  h.sysId, h.environment, h.cmInstallStatus,
		                  h.businessService, h.subsystem, h.opsSuppMgr, h.opsSuppGrp,
		                  substr(p2.name, locate('/', p2.name) + 1) AS port,
		                  p2.speed, p2.status, p2.state
                FROM      host h
				LEFT JOIN port p1 ON h.id = p1.deviceId
                LEFT JOIN port p2 ON p1.connectedPortId = p2.id
                LEFT JOIN switch s ON p2.deviceId = s.id
                WHERE     s.id = {$switchId}
                  AND     p2.name LIKE 'fc{$slotNum}/%'
                ORDER BY  abs(port);";
        $this->db->connect();
        $results = $this->db->getAllObjects($sql);
        $this->db->close();
        return $results;
    }

    /* Used */
    /**
     * @param $switchId
     * @param $slotNum
     * @return array
     */
    public function getArraysBySwitchIdAndSlotNumber($switchId, $slotNum)
    {
        $this->sysLog->debug();
        $sql = "SELECT    DISTINCT 'array' AS objType,
		                  a.id, lower(a.name) AS name,
		                  a.serialNumber, a.vendor, a.model, a.capacityGB, a.rawCapacityGB,
		                  substr(p2.name, locate('/', p2.name) + 1) AS port,
		                  p2.speed, p2.status, p2.state
                FROM      array a
				LEFT JOIN port p1 ON a.id = p1.deviceId
                LEFT JOIN port p2 ON p1.connectedPortId = p2.id
                LEFT JOIN switch s ON p2.deviceId = s.id
                WHERE     s.id = {$switchId}
                  AND     p2.name LIKE 'fc{$slotNum}/%'
                ORDER BY  abs(port);";
        $this->db->connect();
        $results = $this->db->getAllObjects($sql);
        $this->db->close();
        return $results;
    }

    /* Used */
    /**
     * @param $switchId
     * @param $slotNum
     * @return array
     */
    public function getSwitchesBySwitchIdAndSlotNumber($switchId, $slotNum)
    {
        $this->sysLog->debug();
        $sql = 'SELECT    DISTINCT \'switch\' AS objType,
		                  s2.id,
		                  lower(s.name) AS swName,
		                  lower(s2.name) AS name,
		                  substr(p2.name, locate(\'/\', p2.name) + 1) AS port,
		                  p2.speed, p2.status, p2.state
                FROM      switch s2
				LEFT JOIN port p1 ON s2.id = p1.deviceId
                LEFT JOIN port p2 ON p1.connectedPortId = p2.id
                LEFT JOIN switch s ON p2.deviceId = s.id
                WHERE     s.id = ' . $switchId . '
                  AND     p2.name LIKE \'fc' . $slotNum . '/%\'
                ORDER BY  abs(port);';
        $this->db->connect();
        $results = $this->db->getAllObjects($sql);
        $this->db->close();
        return $results;
    }

    /**
     * @param $hostId
     * @return array
     */
    public function getHostPortsByHostId($hostId)
    {
        $this->sysLog->debug();
        $sql = "SELECT    p1.id AS hpId, p1.name AS hpName, p1.nodeId AS hpNodeId,
                          p1.state AS hpState, p1.status AS hpStatus, p1.wwn AS hpWwn,
                          p2.id AS spId, p2.name AS spName, p2.nodeId AS spNodeId,
                          p2.state AS spState, p2.status AS spStatus, p2.wwn AS spWwn,
                          s.name AS switchName, s.ip AS switchIp, s.dead AS switchDead
                FROM      host h
				LEFT JOIN port p1 ON h.id = p1.deviceId
                LEFT JOIN port p2 ON p1.connectedPortId = p2.id
                LEFT JOIN switch s ON p2.deviceId = s.id
                WHERE     h.id = {$hostId};";
        $this->db->connect();
        $results = $this->db->getAllObjects($sql);
        $this->db->close();
        return $results;
    }

    /**
     * @param $switchId
     * @param $arrayId
     * @return array
     */
    public function getArrayPortsByArrayId($switchId, $arrayId)
    {
        $this->sysLog->debug();
        $sql = "SELECT    p1.id AS apId, p1.name AS apName, p1.nodeId AS apNodeId,
                          p1.state AS apState, p1.status AS apStatus, p1.wwn AS apWwn,
                          p2.id AS spId, p2.name AS spName, p2.nodeId AS spNodeId,
                          p2.state AS spState, p2.status AS spStatus, p2.wwn AS spWwn,
                          s.name AS switchName, s.ip AS switchIp, s.dead AS switchDead
                FROM      array a
				LEFT JOIN port p1 ON a.id = p1.deviceId
                LEFT JOIN port p2 ON p1.connectedPortId = p2.id
                LEFT JOIN switch s ON p2.deviceId = s.id
                WHERE     a.id = {$arrayId}
                  AND     s.id = {$switchId};";
        $this->db->connect();
        $results = $this->db->getAllObjects($sql);
        $this->db->close();
        return $results;
    }

    /**
     * @param $switchId
     * @return array
     */
    public function getSwitchPortsBySwitchId($switchId)
    {
        $this->sysLog->debug();
        $sql = "SELECT    id, connectedPortId, deviceId, nodeId, name, state, status, wwn
                FROM      port
                WHERE     deviceId = {$switchId}
                ORDER BY  name;";
        $this->db->connect();
        $results = $this->db->getAllObjects($sql);
        $this->db->close();
        return $results;
    }

    /**
     * @param $arrayId
     * @param $hostId
     * @return array
     */
    public function getHostArrayVolumesByArrayIdAndHostId($arrayId, $hostId)
    {
        $this->sysLog->debug();
        $sql = "SELECT    v.id, v.capacityGB, v.diskType, v.name, v.rawCapacityGB, v.redundancy, v.type
                FROM      host h,
                          path p,
                          volume v,
                          array a
                WHERE     p.hostId = h.id
                  AND     p.volumeId = v.id
                  AND     p.arrayId = a.id
                  AND     h.id = {$hostId}
                  AND     a.id = {$arrayId}
                ORDER BY  v.name;";
        $this->db->connect();
        $results = $this->db->getAllObjects($sql);
        $this->db->close();
        return $results;
    }

    /**
     * @param $arrayId
     * @param $hostId
     * @return mixed
     */
    public function getHostArrayStorageByArrayIdAndHostId($arrayId, $hostId)
    {
        $this->sysLog->debug();
        $sql = "SELECT    sum(v.capacityGB) AS totalGB
                FROM      host h,
                          path p,
                          volume v,
                          array a
                WHERE     p.hostId = h.id
                  AND     p.volumeId = v.id
                  AND     p.arrayId = a.id
                  AND     h.id = {$hostId}
                  AND     a.id = {$arrayId};";
        $this->db->connect();
        $result = $this->db->getObject($sql);
        $this->db->close();
        return $result->totalGB;
    }

    /**
     * @param $hostId
     * @return array
     */
    public function getHostArrayStorageByHostId($hostId)
    {
        $this->sysLog->debug();
        $sql = "SELECT    a.id AS arrayId, a.name AS arrayName, a.sanName, a.tier,
                          a.capacityGB AS capacityGb, sum(v.capacityGB) AS allocatedGb
                FROM      host h,
                          path p,
                          volume v,
                          array a
                WHERE     p.hostId = h.id
                  AND     p.volumeId = v.id
                  AND     a.id = p.arrayId
                  AND     h.id = {$hostId}
                GROUP BY  a.id
                ORDER BY  a.id;";
        $this->db->connect();
        $rows = $this->db->getAllObjects($sql);
        $this->db->close();
        return $rows;
    }


    public function getArrayStorageByArrayId($arrayId)
    {
        $sql = "select sum(p.physicalDiskCapacityMB) / 1024 / 1024 as totalRawTb,
                       sum(p.totalAllocatedCapacityMB) / 1024 / 1024 as totalUseableTb,
                       sum(p.totalUsedCapacityMB) / 1024 / 1024 as totalProvisionedTb,
                       (sum(p.totalAllocatedCapacityMB) - sum(p.totalUsedCapacityMB)) / 1024 / 1024 as totalAvailableTb
                from   array a,
                       storage_pool p
                where  a.id = {$arrayId}
                  and  p.storageId = a.id;";
        $this->db->connect();
        $row = $this->db->getObject($sql);
        $this->db->close();
        return $row;
    }

    /**
     * @param $hostId
     * @return float
     */
    public function getTotalHostStorageByHostId($hostId)
    {
        $this->sysLog->debug();
        $sql = "SELECT    sum(v.capacityGB) AS allocatedGb
                FROM      host h,
                          path p,
                          volume v
                WHERE     p.hostId = h.id
                  AND     p.volumeId = v.id
                  AND     h.id = {$hostId}";
        $this->db->connect();
        $row = $this->db->getObject($sql);
        $this->db->close();
        return $row->allocatedGb;
    }

    /**
     * @param null $dbRowObj
     * @return SANScreenHost
     */
    private function _setHost($dbRowObj = null)
    {
        $this->sysLog->debug();

        $o = new SANScreenHost();
        if ($dbRowObj) {
            foreach (SANScreenHostTable::getColumnNames() as $prop) {
                if (property_exists($dbRowObj, $prop)) $o->set($prop, $dbRowObj->$prop);
            }
        } else {
            foreach (SANScreenHostTable::getColumnNames() as $prop) {
                $o->set($prop, null);
            }
        }
        return $o;
    }

    /**
     * @param null $dbRowObj
     * @return SANScreenArray
     */
    private function _setArray($dbRowObj = null)
    {
        $this->sysLog->debug();

        $o = new SANScreenArray();
        if ($dbRowObj) {
            foreach (SANScreenArrayTable::getColumnNames() as $prop) {
                if (property_exists($dbRowObj, $prop)) $o->set($prop, $dbRowObj->$prop);
            }
        } else {
            foreach (SANScreenArrayTable::getColumnNames() as $prop) {
                $o->set($prop, null);
            }
        }
        return $o;
    }
}
