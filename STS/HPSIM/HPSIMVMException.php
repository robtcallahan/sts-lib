<?php
/*******************************************************************************
 *
 * $Id: HPSIMVMException.php 74541 2013-04-19 16:45:04Z rcallaha $
 * $Date: 2013-04-19 12:45:04 -0400 (Fri, 19 Apr 2013) $
 * $Author: rcallaha $
 * $Revision: 74541 $
 * $HeadURL: https://svn.ultradns.net/svn/sts_tools/sts-lib/trunk/STS/HPSIM/HPSIMVMException.php $
 *
 *******************************************************************************
 */

namespace STS\HPSIM;

class HPSIMVMException
{
	protected $id;
	protected $vmId;

	protected $exceptionTypeId;
	
	protected $exceptionTypeNumber;
	protected $exceptionTypeDescr;

	protected $dateUpdated;
	protected $userUpdated;


	public function __toString()
	{
		$return = "";
		foreach (HPSIMVMExceptionTable::getColumnNames() as $prop) {
			$return .= sprintf("%-25s => %s\n", $prop, $this->$prop);
		}
		return $return;
	}

	public function toObject()
	{
		$obj = (object) array();
		foreach (HPSIMVMExceptionTable::getColumnNames() as $prop)
		{
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

	public function setDateUpdated($dateUpdated)
	{
		$this->dateUpdated = $dateUpdated;
	}

	public function getDateUpdated()
	{
		return $this->dateUpdated;
	}

	public function setExceptionTypeDescr($exceptionTypeDescr)
	{
		$this->exceptionTypeDescr = $exceptionTypeDescr;
	}

	public function getExceptionTypeDescr()
	{
		return $this->exceptionTypeDescr;
	}

	public function setExceptionTypeId($exceptionTypeId)
	{
		$this->exceptionTypeId = $exceptionTypeId;
	}

	public function getExceptionTypeId()
	{
		return $this->exceptionTypeId;
	}

	public function setId($id)
	{
		$this->id = $id;
	}

	public function getId()
	{
		return $this->id;
	}

	public function setUserUpdated($userUpdated)
	{
		$this->userUpdated = $userUpdated;
	}

	public function getUserUpdated()
	{
		return $this->userUpdated;
	}

	public function setVmId($vmId) {
		$this->vmId = $vmId;
	}

	public function getVmId() {
		return $this->vmId;
	}

	public function setExceptionTypeNumber($exceptionTypeNumber) {
		$this->exceptionTypeNumber = $exceptionTypeNumber;
	}

	public function getExceptionTypeNumber() {
		return $this->exceptionTypeNumber;
	}

}
