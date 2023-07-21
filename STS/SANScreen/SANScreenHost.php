<?php
/*******************************************************************************
 *
 * $Id: SANScreenHost.php 74756 2013-04-26 17:08:54Z rcallaha $
 * $Date: 2013-04-26 13:08:54 -0400 (Fri, 26 Apr 2013) $
 * $Author: rcallaha $
 * $Revision: 74756 $
 * $HeadURL: https://svn.ultradns.net/svn/sts_tools/sts-lib/trunk/STS/SANScreen/SANScreenHost.php $
 *
 *******************************************************************************
 */

namespace STS\SANScreen;

class SANScreenHost
{
	protected $id;
	protected $name;
	protected $objectType;
	protected $ip;
	protected $dead;
	protected $startTime;
	protected $endTime;

	protected $sysId;
	protected $cmdbName;
	protected $modelNumber;
	protected $environment;
	protected $cmInstallStatus;
	protected $businessService;
	protected $subsystem;
	protected $opsSuppMgr;
	protected $opsSuppGrp;

	public function __toString()
	{
		$return = "";
		foreach (SANScreenHostTable::getColumnNames() as $prop) {
			$return .= sprintf("%-25s => %s\n", $prop, $this->$prop);
		}
		return $return;
	}

	public function toObject()
	{
		$obj = (object) array();
		foreach (SANScreenHostTable::getColumnNames() as $prop) {
			$obj->$prop = $this->$prop;
		}
		return $obj;
	}


	// *******************************************************************************
	// Getters and Setters
	// *******************************************************************************

	public function get($prop)
	{
		return $this->$prop;
	}

	public function set($prop, $value)
	{
		return $this->$prop = $value;
	}

	public function setBusinessService($businessService)
	{
		$this->businessService = $businessService;
	}

	public function getBusinessService()
	{
		return $this->businessService;
	}

	public function setCmInstallStatus($cmInstallStatus)
	{
		$this->cmInstallStatus = $cmInstallStatus;
	}

	public function getCmInstallStatus()
	{
		return $this->cmInstallStatus;
	}

	public function setDead($dead)
	{
		$this->dead = $dead;
	}

	public function getDead()
	{
		return $this->dead;
	}

	public function setEndTime($endTime)
	{
		$this->endTime = $endTime;
	}

	public function getEndTime()
	{
		return $this->endTime;
	}

	public function setEnvironment($environment)
	{
		$this->environment = $environment;
	}

	public function getEnvironment()
	{
		return $this->environment;
	}

	public function setId($id)
	{
		$this->id = $id;
	}

	public function getId()
	{
		return $this->id;
	}

	public function setIp($ip)
	{
		$this->ip = $ip;
	}

	public function getIp()
	{
		return $this->ip;
	}

	public function setName($name)
	{
		$this->name = $name;
	}

	public function getName()
	{
		return $this->name;
	}

	public function setObjectType($objectType)
	{
		$this->objectType = $objectType;
	}

	public function getObjectType()
	{
		return $this->objectType;
	}

	public function setOpsSuppGrp($opsSuppGrp)
	{
		$this->opsSuppGrp = $opsSuppGrp;
	}

	public function getOpsSuppGrp()
	{
		return $this->opsSuppGrp;
	}

	public function setOpsSuppMgr($opsSuppMgr)
	{
		$this->opsSuppMgr = $opsSuppMgr;
	}

	public function getOpsSuppMgr()
	{
		return $this->opsSuppMgr;
	}

	public function setStartTime($startTime)
	{
		$this->startTime = $startTime;
	}

	public function getStartTime()
	{
		return $this->startTime;
	}

	public function setSubsystem($subsystem)
	{
		$this->subsystem = $subsystem;
	}

	public function getSubsystem()
	{
		return $this->subsystem;
	}

	public function setSysId($sysId)
	{
		$this->sysId = $sysId;
	}

	public function getSysId()
	{
		return $this->sysId;
	}

	public function setModelNumber($modelNumber)
	{
		$this->modelNumber = $modelNumber;
	}

	public function getModelNumber()
	{
		return $this->modelNumber;
	}

	public function setCmdbName($cmdbName)
	{
		$this->cmdbName = $cmdbName;
	}

	public function getCmdbName()
	{
		return $this->cmdbName;
	}
}