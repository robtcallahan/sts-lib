<?php
/*******************************************************************************
 *
 * $Id: BladeReservation.php 73611 2013-03-26 13:23:06Z rcallaha $
 * $Date: 2013-03-26 09:23:06 -0400 (Tue, 26 Mar 2013) $
 * $Author: rcallaha $
 * $Revision: 73611 $
 * $HeadURL: https://svn.ultradns.net/svn/sts_tools/bladerunner/trunk/classes/BladeReservation.php $
 *
 *******************************************************************************
 */

namespace STS\HPSIM;

class HPSIMBladeReservation
{
	protected $id;
	protected $bladeId;

	protected $taskNumber;
	protected $taskSysId;
	protected $taskShortDescr;

	protected $projectName;

	protected $dateReserved;
	protected $userReserved;
	protected $dateUpdated;
	protected $userUpdated;
	protected $dateCompleted;
	protected $userCompleted;
	protected $dateCancelled;
	protected $userCancelled;

	public function __toString()
	{
		$return = "";
		foreach (HPSIMBladeReservationTable::getColumnNames() as $prop) {
			$return .= sprintf("%-25s => %s\n", $prop, $this->$prop);
		}
		return $return;
	}

	public function toObject()
	{
		$obj = (object) array();
		foreach (HPSIMBladeReservationTable::getColumnNames() as $prop) {
			$obj->$prop = $this->get($prop);
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

	public function setBladeId($bladeId)
	{
		$this->bladeId = $bladeId;
	}

	public function getBladeId()
	{
		return $this->bladeId;
	}

	public function setDateCancelled($dateCancelled)
	{
		$this->dateCancelled = $dateCancelled;
	}

	public function getDateCancelled()
	{
		return $this->dateCancelled;
	}

	public function setDateCompleted($dateCompleted)
	{
		$this->dateCompleted = $dateCompleted;
	}

	public function getDateCompleted()
	{
		return $this->dateCompleted;
	}

	public function setDateReserved($dateReserved)
	{
		$this->dateReserved = $dateReserved;
	}

	public function getDateReserved()
	{
		return $this->dateReserved;
	}

	public function setDateUpdated($dateUpdated)
	{
		$this->dateUpdated = $dateUpdated;
	}

	public function getDateUpdated()
	{
		return $this->dateUpdated;
	}

	public function setId($id)
	{
		$this->id = $id;
	}

	public function getId()
	{
		return $this->id;
	}

	public function setProjectName($projectName)
	{
		$this->projectName = $projectName;
	}

	public function getProjectName()
	{
		return $this->projectName;
	}

	public function setTaskNumber($taskNumber)
	{
		$this->taskNumber = $taskNumber;
	}

	public function getTaskNumber()
	{
		return $this->taskNumber;
	}

	public function setTaskShortDescr($taskShortDescr)
	{
		$this->taskShortDescr = $taskShortDescr;
	}

	public function getTaskShortDescr()
	{
		return $this->taskShortDescr;
	}

	public function setTaskSysId($taskSysId)
	{
		$this->taskSysId = $taskSysId;
	}

	public function getTaskSysId()
	{
		return $this->taskSysId;
	}

	public function setUserCancelled($userCancelled)
	{
		$this->userCancelled = $userCancelled;
	}

	public function getUserCancelled()
	{
		return $this->userCancelled;
	}

	public function setUserCompleted($userCompleted)
	{
		$this->userCompleted = $userCompleted;
	}

	public function getUserCompleted()
	{
		return $this->userCompleted;
	}

	public function setUserReserved($userReserved)
	{
		$this->userReserved = $userReserved;
	}

	public function getUserReserved()
	{
		return $this->userReserved;
	}

	public function setUserUpdated($userUpdated)
	{
		$this->userUpdated = $userUpdated;
	}

	public function getUserUpdated()
	{
		return $this->userUpdated;
	}
}

