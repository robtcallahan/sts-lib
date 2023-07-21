<?php
/*******************************************************************************
 *
 * $Id: PageView.php 74756 2013-04-26 17:08:54Z rcallaha $
 * $Date: 2013-04-26 13:08:54 -0400 (Fri, 26 Apr 2013) $
 * $Author: rcallaha $
 * $Revision: 74756 $
 * $HeadURL: https://svn.ultradns.net/svn/sts_tools/sts-lib/trunk/STS/Login/PageView.php $
 *
 *******************************************************************************
 */

namespace STS\Login;

class PageView
{
	protected $userId; // foreign key to id in user table
	protected $accessTime; // timestamp
	protected $page; // varchar


	public function __toString()
	{
		$return = "";
		foreach (UserTable::getColumnNames() as $prop) {
			$return .= sprintf("%-25s => %s\n", $prop, $this->$prop);
		}
		return $return;
	}

	public function toObject()
	{
		$obj = (object) array();
		foreach (UserTable::getColumnNames() as $prop) {
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

	public function setUserId($userId)
	{
		$this->userId = $userId;
	}

	public function getUserId()
	{
		return $this->userId;
	}

	public function setAccessTime($accessTime)
	{
		$this->accessTime = $accessTime;
	}

	public function getAccessTime()
	{
		return $this->accessTime;
	}

	public function setPage($page)
	{
		$this->page = $page;
	}

	public function getPage()
	{
		return $this->page;
	}
}
