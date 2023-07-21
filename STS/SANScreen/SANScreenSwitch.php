<?php
/*******************************************************************************
 *
 * $Id: SANScreenSwitch.php 74756 2013-04-26 17:08:54Z rcallaha $
 * $Date: 2013-04-26 13:08:54 -0400 (Fri, 26 Apr 2013) $
 * $Author: rcallaha $
 * $Revision: 74756 $
 * $HeadURL: https://svn.ultradns.net/svn/sts_tools/sts-lib/trunk/STS/SANScreen/SANScreenSwitch.php $
 *
 *******************************************************************************
 */

namespace STS\SANScreen;

class SANScreenSwitch
{
	protected $id;
	protected $name;
	protected $objectType;
	protected $vendor;
	protected $model;
	protected $fabricId;
	protected $firmwareVersion;
	protected $status;
	protected $wwn;
	protected $ip;
	protected $dead;
	protected $startTime;
	protected $endTime;

	public function __toString()
	{
		$return = "";
		foreach (SANScreenSwitchTable::getColumnNames() as $prop) {
			$return .= sprintf("%-25s => %s\n", $prop, $this->$prop);
		}
		return $return;
	}

	public function toObject()
	{
		$obj = (object) array();
		foreach (SANScreenSwitchTable::getColumnNames() as $prop) {
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

	public function setFabricId($fabricId)
	{
		$this->fabricId = $fabricId;
	}

	public function getFabricId()
	{
		return $this->fabricId;
	}

	public function setFirmwareVersion($firmwareVersion)
	{
		$this->firmwareVersion = $firmwareVersion;
	}

	public function getFirmwareVersion()
	{
		return $this->firmwareVersion;
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

	public function setModel($model)
	{
		$this->model = $model;
	}

	public function getModel()
	{
		return $this->model;
	}

	public function setName($name)
	{
		$this->name = $name;
	}

	public function getName()
	{
		return $this->name;
	}

	public function setStartTime($startTime)
	{
		$this->startTime = $startTime;
	}

	public function getStartTime()
	{
		return $this->startTime;
	}

	public function setStatus($status)
	{
		$this->status = $status;
	}

	public function getStatus()
	{
		return $this->status;
	}

	public function setVendor($vendor)
	{
		$this->vendor = $vendor;
	}

	public function getVendor()
	{
		return $this->vendor;
	}

	public function setWwn($wwn)
	{
		$this->wwn = $wwn;
	}

	public function getWwn()
	{
		return $this->wwn;
	}

	public function setObjectType($objectType) {
		$this->objectType = $objectType;
	}

	public function getObjectType() {
		return $this->objectType;
	}

}