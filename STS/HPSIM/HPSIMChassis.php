<?php
/*******************************************************************************
 *
 * $Id: HPSIMChassis.php 74756 2013-04-26 17:08:54Z rcallaha $
 * $Date: 2013-04-26 13:08:54 -0400 (Fri, 26 Apr 2013) $
 * $Author: rcallaha $
 * $Revision: 74756 $
 * $HeadURL: https://svn.ultradns.net/svn/sts_tools/sts-lib/trunk/STS/HPSIM/HPSIMChassis.php $
 *
 *******************************************************************************
 */

namespace STS\HPSIM;

class HPSIMChassis
{
	protected $id;

	protected $deviceName;
	protected $fullDnsName;

	protected $hwStatus;
	protected $mpStatus;
	protected $deviceType;
	protected $productName;
	protected $assocDeviceName;
	protected $assocDeviceType;
	protected $assocDeviceKey;
	protected $assocType;

	// other meta data
	protected $distSwitchName;

	// CMDB data
	protected $sysId;
	protected $environment;
	protected $cmInstallStatus;
	protected $businessService;
	protected $subsystem;
	protected $opsSuppMgr;
	protected $opsSuppGrp;
	protected $comments;
	protected $shortDescr;


	public function __toString()
	{
		$return = "";
		foreach (HPSIMChassisTable::getColumnNames() as $prop) {
			$return .= sprintf("%-25s => %s\n", $prop, $this->$prop);
		}
		return $return;
	}

	public function toObject()
	{
		$obj = (object) array();
		foreach (HPSIMChassisTable::getColumnNames() as $prop) {
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

	public function setAssocDeviceKey($assocDeviceKey)
	{
		$this->assocDeviceKey = $assocDeviceKey;
	}

	public function getAssocDeviceKey()
	{
		return $this->assocDeviceKey;
	}

	public function setAssocDeviceName($assocDeviceName)
	{
		$this->assocDeviceName = $assocDeviceName;
	}

	public function getAssocDeviceName()
	{
		return $this->assocDeviceName;
	}

	public function setAssocDeviceType($assocDeviceType)
	{
		$this->assocDeviceType = $assocDeviceType;
	}

	public function getAssocDeviceType()
	{
		return $this->assocDeviceType;
	}

	public function setAssocType($assocType)
	{
		$this->assocType = $assocType;
	}

	public function getAssocType()
	{
		return $this->assocType;
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

	public function setComments($comments)
	{
		$this->comments = $comments;
	}

	public function getComments()
	{
		return $this->comments;
	}

	public function setDeviceName($deviceName)
	{
		$this->deviceName = $deviceName;
	}

	public function getDeviceName()
	{
		return $this->deviceName;
	}

	public function setDeviceType($deviceType)
	{
		$this->deviceType = $deviceType;
	}

	public function getDeviceType()
	{
		return $this->deviceType;
	}

	public function setDistSwitchName($distSwitchName)
	{
		$this->distSwitchName = $distSwitchName;
	}

	public function getDistSwitchName()
	{
		return $this->distSwitchName;
	}

	public function setEnvironment($environment)
	{
		$this->environment = $environment;
	}

	public function getEnvironment()
	{
		return $this->environment;
	}

	public function setFullDnsName($fullDnsName)
	{
		$this->fullDnsName = $fullDnsName;
	}

	public function getFullDnsName()
	{
		return $this->fullDnsName;
	}

	public function setHwStatus($hwStatus)
	{
		$this->hwStatus = $hwStatus;
	}

	public function getHwStatus()
	{
		return $this->hwStatus;
	}

	public function setId($id)
	{
		$this->id = $id;
	}

	public function getId()
	{
		return $this->id;
	}

	public function setMpStatus($mpStatus)
	{
		$this->mpStatus = $mpStatus;
	}

	public function getMpStatus()
	{
		return $this->mpStatus;
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

	public function setProductName($productName)
	{
		$this->productName = $productName;
	}

	public function getProductName()
	{
		return $this->productName;
	}

	public function setShortDescr($shortDescr)
	{
		$this->shortDescr = $shortDescr;
	}

	public function getShortDescr()
	{
		return $this->shortDescr;
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
}
