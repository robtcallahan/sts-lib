<?php
/*******************************************************************************
 *
 * $Id: SANScreenPath.php 74756 2013-04-26 17:08:54Z rcallaha $
 * $Date: 2013-04-26 13:08:54 -0400 (Fri, 26 Apr 2013) $
 * $Author: rcallaha $
 * $Revision: 74756 $
 * $HeadURL: https://svn.ultradns.net/svn/sts_tools/sts-lib/trunk/STS/SANScreen/SANScreenPath.php $
 *
 *******************************************************************************
 */

namespace STS\SANScreen;

class SANScreenPath
{
	protected $id;
	protected $arrayId;
	protected $hostId;
	protected $volumeId;
	protected $minimumNumberOfHops;
	protected $numberOfFabrics;
	protected $numberOfHostPorts;
	protected $numberOfStoragePorts;
	protected $spf;
	protected $startTime;
	protected $endTime;

	public function __toString()
	{
		$return = "";
		foreach (SANScreenPathTable::getColumnNames() as $prop) {
			$return .= sprintf("%-25s => %s\n", $prop, $this->$prop);
		}
		return $return;
	}

	public function toObject()
	{
		$obj = (object) array();
		foreach (SANScreenPathTable::getColumnNames() as $prop) {
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

	public function setArrayId($arrayId)
	{
		$this->arrayId = $arrayId;
	}

	public function getArrayId()
	{
		return $this->arrayId;
	}

	public function setEndTime($endTime)
	{
		$this->endTime = $endTime;
	}

	public function getEndTime()
	{
		return $this->endTime;
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

	public function setMinimumNumberOfHops($minimumNumberOfHops)
	{
		$this->minimumNumberOfHops = $minimumNumberOfHops;
	}

	public function getMinimumNumberOfHops()
	{
		return $this->minimumNumberOfHops;
	}

	public function setNumberOfFabrics($numberOfFabrics)
	{
		$this->numberOfFabrics = $numberOfFabrics;
	}

	public function getNumberOfFabrics()
	{
		return $this->numberOfFabrics;
	}

	public function setNumberOfHostPorts($numberOfHostPorts)
	{
		$this->numberOfHostPorts = $numberOfHostPorts;
	}

	public function getNumberOfHostPorts()
	{
		return $this->numberOfHostPorts;
	}

	public function setNumberOfStoragePorts($numberOfStoragePorts)
	{
		$this->numberOfStoragePorts = $numberOfStoragePorts;
	}

	public function getNumberOfStoragePorts()
	{
		return $this->numberOfStoragePorts;
	}

	public function setSpf($spf)
	{
		$this->spf = $spf;
	}

	public function getSpf()
	{
		return $this->spf;
	}

	public function setStartTime($startTime)
	{
		$this->startTime = $startTime;
	}

	public function getStartTime()
	{
		return $this->startTime;
	}

	public function setVolumeId($volumeId)
	{
		$this->volumeId = $volumeId;
	}

	public function getVolumeId()
	{
		return $this->volumeId;
	}
}
