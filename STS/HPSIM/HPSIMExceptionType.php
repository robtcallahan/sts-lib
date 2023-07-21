<?php
/*******************************************************************************
 *
 * $Id: HPSIMExceptionType.php 74756 2013-04-26 17:08:54Z rcallaha $
 * $Date: 2013-04-26 13:08:54 -0400 (Fri, 26 Apr 2013) $
 * $Author: rcallaha $
 * $Revision: 74756 $
 * $HeadURL: https://svn.ultradns.net/svn/sts_tools/sts-lib/trunk/STS/HPSIM/HPSIMExceptionType.php $
 *
 *******************************************************************************
 */

namespace STS\HPSIM;

class HPSIMExceptionType
{
	protected $id;

	protected $exceptionNumber;
	protected $exceptionObject;
	protected $exceptionDescr;

	public function __toString()
	{
		$return = "";
		foreach (HPSIMExceptionTypeTable::getColumnNames() as $prop) {
			$return .= sprintf("%-25s => %s\n", $prop, $this->$prop);
		}
		return $return;
	}

	public function toObject()
	{
		$obj = (object) array();
		foreach (HPSIMExceptionTypeTable::getColumnNames() as $prop) {
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

	public function setId($id)
	{
		$this->id = $id;
	}

	public function getId()
	{
		return $this->id;
	}

	public function setExceptionDescr($exceptionDescr)
	{
		$this->exceptionDescr = $exceptionDescr;
	}

	public function getExceptionDescr()
	{
		return $this->exceptionDescr;
	}

	public function setExceptionObject($exceptionObject)
	{
		$this->exceptionObject = $exceptionObject;
	}

	public function getExceptionObject()
	{
		return $this->exceptionObject;
	}

	public function setExceptionNumber($exceptionNumber)
	{
		$this->exceptionNumber = $exceptionNumber;
	}

	public function getExceptionNumber()
	{
		return $this->exceptionNumber;
	}
}
