<?php
/*******************************************************************************
 *
 * $Id: SANScreenPort.php 74756 2013-04-26 17:08:54Z rcallaha $
 * $Date: 2013-04-26 13:08:54 -0400 (Fri, 26 Apr 2013) $
 * $Author: rcallaha $
 * $Revision: 74756 $
 * $HeadURL: https://svn.ultradns.net/svn/sts_tools/sts-lib/trunk/STS/SANScreen/SANScreenPort.php $
 *
 *******************************************************************************
 */

namespace STS\SANScreen;

class SANScreenPort
{
	protected $id;
	protected $name;
	protected $connectedPortId;
	protected $deviceId;
	protected $nodeId;
	protected $speed;
	protected $state;
	protected $status;
	protected $wwn;
	protected $dead;

	public function __toString()
	{
		$return = "";
		foreach (SANScreenPortTable::getColumnNames() as $prop) {
			$return .= sprintf("%-25s => %s\n", $prop, $this->$prop);
		}
		return $return;
	}

	public function toObject()
	{
		$obj = (object) array();
		foreach (SANScreenPortTable::getColumnNames() as $prop) {
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

	public function setConnectedPortId($connectedPortId)
	{
		$this->connectedPortId = $connectedPortId;
	}

	public function getConnectedPortId()
	{
		return $this->connectedPortId;
	}

	public function setDead($dead)
	{
		$this->dead = $dead;
	}

	public function getDead()
	{
		return $this->dead;
	}

	public function setDeviceId($deviceId)
	{
		$this->deviceId = $deviceId;
	}

	public function getDeviceId()
	{
		return $this->deviceId;
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

	public function setNodeId($nodeId)
	{
		$this->nodeId = $nodeId;
	}

	public function getNodeId()
	{
		return $this->nodeId;
	}

	public function setSpeed($speed)
	{
		$this->speed = $speed;
	}

	public function getSpeed()
	{
		return $this->speed;
	}

	public function setState($state)
	{
		$this->state = $state;
	}

	public function getState()
	{
		return $this->state;
	}

	public function setStatus($status)
	{
		$this->status = $status;
	}

	public function getStatus()
	{
		return $this->status;
	}

	public function setWwn($wwn)
	{
		$this->wwn = $wwn;
	}

	public function getWwn()
	{
		return $this->wwn;
	}
}
