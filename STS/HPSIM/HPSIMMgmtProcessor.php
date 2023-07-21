<?php
/*******************************************************************************
 *
 * $Id: HPSIMMgmtProcessor.php 74756 2013-04-26 17:08:54Z rcallaha $
 * $Date: 2013-04-26 13:08:54 -0400 (Fri, 26 Apr 2013) $
 * $Author: rcallaha $
 * $Revision: 74756 $
 * $HeadURL: https://svn.ultradns.net/svn/sts_tools/sts-lib/trunk/STS/HPSIM/HPSIMMgmtProcessor.php $
 *
 *******************************************************************************
 */

namespace STS\HPSIM;

class HPSIMMgmtProcessor
{
	protected $id;
	protected $deviceName;

	protected $hwStatus;
	protected $mpStatus;
	protected $deviceType;
	protected $deviceAddress;
	protected $productName;
	protected $osName;
	protected $fullDnsName;
	protected $assocDeviceName;
	protected $assocDeviceType;
	protected $chassisId;
	protected $assocType;

	protected $version;

	protected $role;

	public function __toString()
	{
		$return = "";
		foreach (HPSIMMgmtProcessorTable::getColumnNames() as $prop) {
			$return .= sprintf("%-25s => %s\n", $prop, $this->$prop);
		}
		return $return;
	}

	public function toObject()
	{
		$obj = (object) array();
		foreach (HPSIMMgmtProcessorTable::getColumnNames() as $prop) {
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

	public function setChassisId($chassisId)
	{
		$this->chassisId = $chassisId;
	}

	public function getChassisId()
	{
		return $this->chassisId;
	}

	public function setDeviceAddress($deviceAddress)
	{
		$this->deviceAddress = $deviceAddress;
	}

	public function getDeviceAddress()
	{
		return $this->deviceAddress;
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

	public function setOsName($osName)
	{
		$this->osName = $osName;
	}

	public function getOsName()
	{
		return $this->osName;
	}

	public function setProductName($productName)
	{
		$this->productName = $productName;
	}

	public function getProductName()
	{
		return $this->productName;
	}

	public function setVersion($version)
	{
		$this->version = $version;
	}

	public function getVersion()
	{
		return $this->version;
	}

	public function setRole($role)
	{
		$this->role = $role;
	}

	public function getRole()
	{
		return $this->role;
	}
}

