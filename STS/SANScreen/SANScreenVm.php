<?php
/*******************************************************************************
 *
 * $Id: SANScreenVm.php 74756 2013-04-26 17:08:54Z rcallaha $
 * $Date: 2013-04-26 13:08:54 -0400 (Fri, 26 Apr 2013) $
 * $Author: rcallaha $
 * $Revision: 74756 $
 * $HeadURL: https://svn.ultradns.net/svn/sts_tools/sts-lib/trunk/STS/SANScreen/SANScreenVm.php $
 *
 *******************************************************************************
 */

namespace STS\SANScreen;

class SANScreenVm
{
	protected $id;
	protected $hostId;
	protected $sysId;

	protected $name;

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
		foreach (SANScreenVmTable::getColumnNames() as $prop) {
			$return .= sprintf("%-25s => %s\n", $prop, $this->$prop);
		}
		return $return;
	}

	public function toObject()
	{
		$obj = (object) array();
		foreach (SANScreenVmTable::getColumnNames() as $prop) {
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

	public function setEnvironment($environment)
	{
		$this->environment = $environment;
	}

	public function getEnvironment()
	{
		return $this->environment;
	}

	public function setHostId($hostId)
	{
		$this->hostId = $hostId;
	}

	public function getHostId()
	{
		return $this->hostId;
	}

	public function setId($id)
	{
		$this->id = $id;
	}

	public function getId()
	{
		return $this->id;
	}

	public function setName($name)
	{
		$this->name = $name;
	}

	public function getName()
	{
		return $this->name;
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
}