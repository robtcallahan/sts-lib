<?php
/*******************************************************************************
 *
 * $Id: BladeRunner.php 73761 2013-04-01 16:30:07Z rcallaha $
 * $Date: 2013-04-01 12:30:07 -0400 (Mon, 01 Apr 2013) $
 * $Author: rcallaha $
 * $Revision: 73761 $
 * $HeadURL: https://svn.ultradns.net/svn/sts_tools/bladerunner/trunk/classes/BladeRunner.php $
 *
 *******************************************************************************
 */

namespace STS\HPSIM;

use STS\DB\MySqlDB;
use STS\Util\SysLog;

class HPSIM
{
	protected $db;
	protected $saHost;
	protected $tmpDir;
	protected $dataDir;
	protected $hostsFile;

	protected $sysLog;
	protected $logLevel;

    public function __construct($config = null)
   	{
           if ($config && is_array($config)) {
               // new config method: passing the config into the constructor
               // check for all needed config params

               // appName & logLevel. If missing, assign defaults
               $config['appName'] = array_key_exists('appName', $config) ? $config['appName'] : 'hpsim';
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

               $dbIndex = 'hpsim';
               $config['dbIndex'] = $dbIndex;

               // check for all needed config params
               if (is_object($configOld)) {
                   // config is an object - the "old" way of doing things
                   $config['appName'] = property_exists($configOld, 'appName') ? $configOld->appName : $dbIndex;
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
                           'server' => $configOld->databases->$dbIndex->server,
                           'type'     => $configOld->databases->$dbIndex->type,
                           'username' => $configOld->databases->$dbIndex->username,
                           'password' => $configOld->databases->$dbIndex->password,
                           'database' => $configOld->databases->$dbIndex->database
                       )
                   );
               } else {
                   // config is an array
                   $config['appName'] = array_key_exists('appName', $configOld) ? $configOld['appName'] : $dbIndex;
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
                           'server' => $configOld['databases'][$dbIndex]['server'],
                           'type' => $configOld['databases'][$dbIndex]['type'],
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
     * @param $chassisId int
     * @return int
     */
    public function getChassisSlotsAvailable($chassisId) {
        $this->db->connect();
        $sql = "select b.slotNumber, b.productName,
                       case when b.productName like '%BL68%G5%'
                           then true
                           else false
                       end as fullHeight
                from   chassis c, blade b
                where  b.chassisId = c.id
                  and  c.id = {$chassisId};";
        $results = $this->db->getAllObjects($sql);
        $slotsAvailable = 16;
        foreach ($results as $r) {
            $slotsAvailable -= $r->fullHeight ? 2 : 1;
        }
        $this->db->close();
        return $slotsAvailable;
    }


    /**
	 * @return array
	 */
	public function getAllMgmtProcessorExceptions()
	{
		$this->sysLog->debug();
		$this->db->connect();
		$sql  = "select mpe.id, mp.id as mpId,
                       mp.deviceName as mpName,
                       etype.exceptionDescr as excepDescr,
                       mpe.dateUpdated, mpe.userUpdated
                from   mgmt_processor mp,
                       mgmt_processor_exception mpe,
                       exception_type etype
		        where  etype.id = mpe.exceptionTypeId
		          and  mp.id = mpe.mgmtProcessorId
		        order by mp.deviceName;";
		$rows = $this->db->getAllObjects($sql);
		$this->db->close();
		return $rows;
	}

	/**
	 * @return array
	 */
	public function getAllCoreHostingBladeExceptions()
	{
		$this->sysLog->debug();
		$this->db->connect();
		$sql  = "select be.id, b.id as bladeId, c.id as chassisId,
                       c.deviceName as chassisName,
                       b.deviceName as hpSimName,
                       case when b.cmdbName is not null then b.cmdbName
                            else b.fullDnsName
                       end as cmdbName,
                       b.sysId as cmdbSysId,
                       b.cmInstallStatus,
                       b.productName, b.slotNumber, b.powerStatus,
                       b.isInventory,
                       betype.exceptionDescr as excepDescr,
                       be.errorText,
                       be.dateUpdated, be.userUpdated
                from   chassis c,
                       blade b,
                       blade_exception be,
                       exception_type betype
		        where  betype.id = be.exceptionTypeId
		          and  b.id = be.bladeId
		          and  c.id = b.chassisId
		          and  betype.exceptionNumber != 11;"; // exclude hypervisor exception errors;
		$results = $this->db->getAllObjects($sql);
		$objects  = array();
		for ($i = 0; $i < count($results); $i++) {
			if (preg_match("/^(st|ch)(de|ds|mc|nt|ul)/", $results[$i]->chassisName)) continue;
			$objects[] = $results[$i];
		}
		return $objects;
	}

    /**
   	 * @return array
   	 */
   	public function getAllHypervisorConnectionExceptions()
   	{
   		$this->sysLog->debug();
   		$this->db->connect();
   		$sql  = "select be.id, b.id as bladeId, c.id as chassisId,
                          c.deviceName as chassisName,
                          b.deviceName as hpSimName,
                          case when b.cmdbName is not null then b.cmdbName
                               else b.fullDnsName
                          end as cmdbName,
                          b.sysId as cmdbSysId,
                          b.productName, b.slotNumber, b.powerStatus,
                          b.isInventory,
                          betype.exceptionDescr as excepDescr,
                          be.errorText,
                          be.dateUpdated, be.userUpdated
                   from   chassis c,
                          blade b,
                          blade_exception be,
                          exception_type betype
   		        where  betype.id = be.exceptionTypeId
   		          and  b.id = be.bladeId
   		          and  c.id = b.chassisId
   		          and  betype.exceptionNumber = 11;"; // only include hypervisor exception errors;
   		$results = $this->db->getAllObjects($sql);
   		$objects  = array();
   		for ($i = 0; $i < count($results); $i++) {
   			if (preg_match("/^(st|ch)(de|ds|mc|nt|ul)/", $results[$i]->chassisName)) continue;
   			$objects[] = $results[$i];
   		}
   		return $objects;
   	}

	/**
	 * @return array
	 */
	public function getAllBladeExceptions()
	{
		$this->sysLog->debug();
		$this->db->connect();
		$sql  = "select be.id, b.id as bladeId, c.id as chassisId,
                       c.deviceName as chassisName,
                       b.deviceName as hpSimName,
                       case when b.cmdbName is not null then b.cmdbName
                            else b.fullDnsName
                       end as cmdbName,
                       b.sysId as cmdbSysId,
                       b.productName, b.slotNumber, b.powerStatus,
                       b.isInventory,
                       betype.exceptionDescr as excepDescr,
                       be.dateUpdated, be.userUpdated
                from   chassis c,
                       blade b,
                       blade_exception be,
                       exception_type betype
		        where  betype.id = be.exceptionTypeId
		          and  b.id = be.bladeId
		          and  c.id = b.chassisId;";
		$rows = $this->db->getAllObjects($sql);
		$this->db->close();
		return $rows;
	}

	/**
	 * @return array
	 */
	public function getAllCoreHostingVmExceptions()
	{
		$this->sysLog->debug();
		$this->db->connect();
		$sql  = "select vme.id, vm.id as vmId, b.id as bladeId, c.id as chassisId,
                       c.deviceName as chassisName,
                       b.deviceName as bladeName,
                       vm.deviceName as vmName,
                       etype.exceptionDescr,
                       vme.dateUpdated, vme.userUpdated
                from   chassis c,
                       blade b,
                       vm,
                       vm_exception vme,
                       exception_type etype
		        where  etype.id = vme.exceptionTypeId
		          and  vm.id = vme.vmId
		          and  b.id = vm.bladeId
		          and  c.id = b.chassisId;";
		$results = $this->db->getAllObjects($sql);
		$objects  = array();
		for ($i = 0; $i < count($results); $i++) {
			if (preg_match("/^(st|ch)(de|mc|nt|ul)/", $results[$i]->vmName)) continue;
			$objects[] = $results[$i];
		}
		return $objects;
	}

	/**
	 * @return array
	 */
	public function getAllVmExceptions()
	{
		$this->sysLog->debug();
		$this->db->connect();
		$sql  = "select vme.id, vm.id as vmId, b.id as bladeId, c.id as chassisId,
                       c.deviceName as chassisName,
                       b.deviceName as bladeName,
                       vm.deviceName as vmName,
                       etype.exceptionDescr,
                       vme.dateUpdated, vme.userUpdated
                from   chassis c,
                       blade b,
                       vm,
                       vm_exception vme,
                       exception_type etype
		        where  etype.id = vme.exceptionTypeId
		          and  vm.id = vme.vmId
		          and  b.id = vm.bladeId
		          and  c.id = b.chassisId;";
		$rows = $this->db->getAllObjects($sql);
		$this->db->close();
		return $rows;
	}

	/**
	 * @param null $query
	 * @return array
	 */
	public function getDistSwitches($query = null)
	{
		$this->sysLog->debug();
		$sql = "select distinct distSwitchName
                from   chassis\n";
		if ($query) {
			$sql .= "where distSwitchName like '{$query}%'\n";
		}
		else {
			$sql .= "where distSwitchName is not null\n";
		}
		$sql .= "order by distSwitchName;";

		$this->db->connect();
		$results = $this->db->getAllObjects($sql);
		$this->db->close();
		return $results;
	}

	/**
	 * @param $command
	 * @return null
	 * @throws \ErrorException
	 */
	public function execCommand($command)
	{
		$this->sysLog->debug();
		$out    = null;
		$retVar = null;
		exec($command, $out, $retVar);
		if ($retVar != 0) {
			throw new \ErrorException("Could not execute remote command: {$command}");
		}
		return $out;
	}

	public function getChassisBladesByChassisId($chassisId)
	{
		$fullHeightSlots = array(); // keep track of full slots populated by full-height blades

		$bladeTable = new HPSIMBladeTable();
		$blades = $bladeTable->getByChassisId($chassisId, "slotNumber", "asc");

		$resTable = new HPSIMBladeReservationTable();
		$bladeResObj = (object) array();

		$bladeExceptionTable = new HPSIMBladeExceptionTable();

		$slots = array();
		$slots[0] = null;

		// data rows
		$targetSlot = 0;
		$currentSlot = null;
		for ($i=0; $i<count($blades); $i++)
		{
			$b = $blades[$i];

			$slotNumber = $b->getSlotNumber();
			$targetSlot++;

			// check for a reservation
			$bladeRes = $resTable->getOpenByBladeId($b->getId());

			// check for exception
			$bladeException = $bladeExceptionTable->getByBladeId($b->getId());

			// denote empty slots
			while ($slotNumber > $targetSlot)
			{
				if ($targetSlot <=8 || ($targetSlot > 8 && !array_key_exists($targetSlot, $fullHeightSlots)))
				{
					$nodeObj = array(
						"dbId" => null,
						"slot" => $targetSlot,
						"type" => "empty",
						"deviceName" => "",
						"fullHeight" => 0,
						"bladeRes" => $bladeResObj,
						);
					$slots[] = $nodeObj;
				}
				$targetSlot++;
			}

			// actual blades
			$totalVmMemory = $bladeTable->getTotalVmMemory($b->getId());
			$memory = $totalVmMemory ? $totalVmMemory . "/" . $b->getMemorySizeGB() : $b->getMemorySizeGB();

			// check for USE* as deviceName
			$deviceName = $b->getDeviceName();
			$deviceLink = "";
			if (preg_match("/[Uu][Ss][Ee].*/", $b->getDeviceName())) {
				if ($b->getCmdbName() != null) {
					$url = "https://neustar.service-now.com/nav_to.do?uri=cmdb_ci_server.do?sys_id={$b->getSysId()}";
					if (preg_match("/^(.*?)\./", $b->getCmdbName(), $m)) {
						$link = "<span style='font-size:8pt;padding:0;'>" .
							"<span title='Click to go to CMDB entry' style='text-decoration:underline;padding:0;' " .
							"onclick='window.open(\"{$url}\", \"_blank\");'>" . strtolower($m[1]) . "</span></span>";
						$deviceLink = "{$b->getDeviceName()} ({$link})";
					}
					else {
						$link = "<span style='font-size:8pt;padding:0;'>" .
							"<span title='Click to go to CMDB entry' style='text-decoration:underline;padding:0;' " .
							"onclick='window.open(\"{$url}\", \"_blank\");'>" . strtolower($b->getCmdbName()) . "</span></span>";
						$deviceLink = "{$b->getDeviceName()} ({$link})";
					}
				}
				else {
					$deviceLink = "{$b->getDeviceName()} (<span style='font-size:8pt;'>N/A)</span>";
				}
			}

			$exceptionDescr = "";

			// exception
			if ($bladeException->getId() != "") {
				$type = "active-excep";
				$exceptionDescr = $bladeException->getExceptionTypeDescr();
			} else {
				$type = "active";
			}

			// inventory
			if ($b->getIsInventory()) {
				if ($bladeException->getId() != "") {
					$type = "inventory-excep";
					$exceptionDescr = $bladeException->getExceptionTypeDescr();
				} else {
					$type = "inventory";
				}
			}
			// reserved
			if ($bladeRes && $bladeRes->getId() != "") {
				if ($bladeException->getId() != "") {
					$type    = "reserved-excep";
					$exceptionDescr = $bladeException->getExceptionTypeDescr();
				} else {
					$type    = "reserved";
				}
				if ($bladeRes->getTaskSysId()) {
					$url        = "https://neustar.service-now.com/nav_to.do?uri=sc_task.do?sys_id={$bladeRes->getTaskSysId()}";
					$ticketNum  = preg_replace("/TASK0+/", "", $bladeRes->getTaskNumber());
					$deviceLink = "{$b->getDeviceName()} <span style='font-size:8pt;'>[{$bladeRes->getProjectName()} <span title='Click to go to ServiceNow ticket' style='text-decoration: underline;' onclick='window.open(\"{$url}\", \"_blank\");'>{$ticketNum}</span>]</span>";
				}
				else {
					$deviceLink = "{$b->getDeviceName()} <span style='font-size:8pt;'>[{$bladeRes->getProjectName()}</span>]";
				}
			}
			// spare
			if ($b->getIsSpare())
			{
				$type = "spare";
			}

			$nodeObj = array(
				"dbId" => $b->getId(),
				"type" => $type,
				"deviceName" => $deviceName,
				"deviceLink" => $deviceLink,
				"fqdn" => $b->getFullDnsName(),
				"productName" => $b->getProductName(),
				"serialNumber" => $b->getSerialNumber(),
				"slot" => $slotNumber,
				"fullHeight" => preg_match("/BL68.*G5/", $b->getProductName()) ? 1 : 0,
				"address" => $b->getDeviceAddress(),
				"numCpus" => $b->getNumCpus() ? "{$b->getNumCpus()} x {$b->getNumCoresPerCpu()}" : "&nbsp;",
				"memory" => $memory,
				"hwStatus" => $b->getHwStatus(),
				"powerStatus" => $b->getPowerStatus() == "On" ? "Normal" : "Critical",
				"bladeRes" => $bladeRes->toObject(),
				"bladeException" => $exceptionDescr
				);
			$slots[] = $nodeObj;
			$currentSlot = $b->getSlotNumber();
		}

		// any remaining empty slots
		while ($currentSlot < 16)
		{
			$currentSlot++;

			if ($currentSlot <=8 || ($currentSlot > 8 && !array_key_exists($currentSlot, $fullHeightSlots)))
			{
				$nodeObj = array(
					"dbId" => null,
					"type" => "empty",
					"slot" => $currentSlot,
					"deviceName" => "empty",
					"fullHeight" => 0,
					"bladeRes" => $bladeResObj
					);
				$slots[] = $nodeObj;
			}
		}
		return $slots;
	}
}

