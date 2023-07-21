<?php
/*******************************************************************************
 *
 * $Id: SANScreenSnapshotList.php 74756 2013-04-26 17:08:54Z rcallaha $
 * $Date: 2013-04-26 13:08:54 -0400 (Fri, 26 Apr 2013) $
 * $Author: rcallaha $
 * $Revision: 74756 $
 * $HeadURL: https://svn.ultradns.net/svn/sts_tools/sts-lib/trunk/STS/SANScreen/SANScreenSnapshotList.php $
 *
 *******************************************************************************
 */

namespace STS\SANScreen;

class SANScreenSnapshotList
{
	protected $id;
	protected $dateStamp;

	public function __toString()
	{
		$return = "";
		foreach (SANScreenSnapshotListTable::getColumnNames() as $prop) {
			$return .= sprintf("%-25s => %s\n", $prop, $this->$prop);
		}
		return $return;
	}

	public function toObject()
	{
		$obj = (object) array();
		foreach (SANScreenSnapshotListTable::getColumnNames() as $prop) {
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

	public function setDateStamp($dateStamp)
	{
		$this->dateStamp = $dateStamp;
	}

	public function getDateStamp()
	{
		return $this->dateStamp;
	}

	public function setId($id)
	{
		$this->id = $id;
	}

	public function getId()
	{
		return $this->id;
	}
}

