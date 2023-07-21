<?php
/*******************************************************************************
 *
 * $Id: SANScreenVolume.php 74756 2013-04-26 17:08:54Z rcallaha $
 * $Date: 2013-04-26 13:08:54 -0400 (Fri, 26 Apr 2013) $
 * $Author: rcallaha $
 * $Revision: 74756 $
 * $HeadURL: https://svn.ultradns.net/svn/sts_tools/sts-lib/trunk/STS/SANScreen/SANScreenVolume.php $
 *
 *******************************************************************************
 */

namespace STS\SANScreen;

class SANScreenVolume
{
	protected $id;
	protected $storageID;
	protected $name;
	protected $label;
	protected $type;
	protected $diskType;
	protected $diskSize;
	protected $diskSpeed;
	protected $redundancy;
	protected $virtual;
	protected $capacityGB;
	protected $rawCapacityGB;
	protected $consumedCapacityGB;
	protected $startTime;
	protected $endTime;


	public function __toString()
	{
		$return = "";
		foreach (SANScreenVolumeTable::getColumnNames() as $prop) {
			$return .= sprintf("%-25s => %s\n", $prop, $this->$prop);
		}
		return $return;
	}

	public function toObject()
	{
		$obj = (object) array();
		foreach (SANScreenVolumeTable::getColumnNames() as $prop) {
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

	public function setCapacityGB($capacityGB)
	{
		$this->capacityGB = $capacityGB;
	}

	public function getCapacityGB()
	{
		return $this->capacityGB;
	}

	public function setConsumedCapacityGB($consumedCapacityGB)
	{
		$this->consumedCapacityGB = $consumedCapacityGB;
	}

	public function getConsumedCapacityGB()
	{
		return $this->consumedCapacityGB;
	}

	public function setDiskSize($diskSize)
	{
		$this->diskSize = $diskSize;
	}

	public function getDiskSize()
	{
		return $this->diskSize;
	}

	public function setDiskSpeed($diskSpeed)
	{
		$this->diskSpeed = $diskSpeed;
	}

	public function getDiskSpeed()
	{
		return $this->diskSpeed;
	}

	public function setDiskType($diskType)
	{
		$this->diskType = $diskType;
	}

	public function getDiskType()
	{
		return $this->diskType;
	}

	public function setEndTime($endTime)
	{
		$this->endTime = $endTime;
	}

	public function getEndTime()
	{
		return $this->endTime;
	}

	public function setId($id)
	{
		$this->id = $id;
	}

	public function getId()
	{
		return $this->id;
	}

	public function setLabel($label)
	{
		$this->label = $label;
	}

	public function getLabel()
	{
		return $this->label;
	}

	public function setName($name)
	{
		$this->name = $name;
	}

	public function getName()
	{
		return $this->name;
	}

	public function setRawCapacityGB($rawCapacityGB)
	{
		$this->rawCapacityGB = $rawCapacityGB;
	}

	public function getRawCapacityGB()
	{
		return $this->rawCapacityGB;
	}

	public function setRedundancy($redundancy)
	{
		$this->redundancy = $redundancy;
	}

	public function getRedundancy()
	{
		return $this->redundancy;
	}

	public function setStartTime($startTime)
	{
		$this->startTime = $startTime;
	}

	public function getStartTime()
	{
		return $this->startTime;
	}

	public function setStorageID($storageID)
	{
		$this->storageID = $storageID;
	}

	public function getStorageID()
	{
		return $this->storageID;
	}

	public function setType($type)
	{
		$this->type = $type;
	}

	public function getType()
	{
		return $this->type;
	}

	public function setVirtual($virtual)
	{
		$this->virtual = $virtual;
	}

	public function getVirtual()
	{
		return $this->virtual;
	}
}
