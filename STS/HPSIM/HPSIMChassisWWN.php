<?php
/*******************************************************************************
 *
 * $Id: HPSIMChassisWWN.php 74756 2013-04-26 17:08:54Z rcallaha $
 * $Date: 2013-04-26 13:08:54 -0400 (Fri, 26 Apr 2013) $
 * $Author: rcallaha $
 * $Revision: 74756 $
 * $HeadURL: https://svn.ultradns.net/svn/sts_tools/sts-lib/trunk/STS/HPSIM/HPSIMChassisWWN.php $
 *
 *******************************************************************************
 */

namespace STS\HPSIM;

class HPSIMChassisWWN
{
	protected $id;
	protected $chassisId;

	protected $wwn;
	protected $type;
	protected $usedBy;
	protected $speed;
	protected $status;

	public function __toString()
	{
		$return = "";
		foreach (HPSIMChassisWWNTable::getColumnNames() as $prop) {
			$return .= sprintf("%-25s => %s\n", $prop, $this->$prop);
		}
		return $return;
	}

	public function toObject()
	{
		$obj = (object) array();
		foreach (HPSIMChassisWWNTable::getColumnNames() as $prop) {
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

	public function setChassisId($chassisId)
	{
		$this->chassisId = $chassisId;
	}

	public function getChassisId()
	{
		return $this->chassisId;
	}

	public function setId($id)
	{
		$this->id = $id;
	}

	public function getId()
	{
		return $this->id;
	}

	public function setSpeed($speed)
	{
		$this->speed = $speed;
	}

	public function getSpeed()
	{
		return $this->speed;
	}

	public function setStatus($status)
	{
		$this->status = $status;
	}

	public function getStatus()
	{
		return $this->status;
	}

	public function setType($type)
	{
		$this->type = $type;
	}

	public function getType()
	{
		return $this->type;
	}

	public function setUsedBy($usedBy)
	{
		$this->usedBy = $usedBy;
	}

	public function getUsedBy()
	{
		return $this->usedBy;
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

