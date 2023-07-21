<?php
/*******************************************************************************
 *
 * $Id: PageViewTable.php 82444 2014-01-03 14:28:59Z rcallaha $
 * $Date: 2014-01-03 09:28:59 -0500 (Fri, 03 Jan 2014) $
 * $Author: rcallaha $
 * $Revision: 82444 $
 * $HeadURL: https://svn.ultradns.net/svn/sts_tools/sts-lib/trunk/STS/Login/PageViewTable.php $
 *
 *******************************************************************************
 */

namespace STS\Login;


use STS\DB\DBTable;


class PageViewTable extends DBTable
{
	private static $columnNames = array("userId", "accessTime", "page");

	public function __construct($idAutoIncremented = false)
	{
		$this->dbIndex   = $GLOBALS['config']->appDB;
		$this->tableName = "page_view";
        $this->idAutoIncremented = $idAutoIncremented;
		parent::__construct();
		$this->sysLog->debug();
	}

	/**
	 * @param User $user
	 * @return Login
	 */
	public function getByUser(User $user)
	{
		$this->sysLog->debug("userId=" . $user->getId());
		$sql    = "select {$this->getQueryColumnsStr()}
                from   {$this->tableName}
    			where  userId = {$user->getId()};";
		$result = $this->sqlQueryRow($sql);
		return $this->_set($result);
	}

	// *******************************************************************************
	// CRUD methods
	// *******************************************************************************

	/**
	 * @param int    $userId
	 * @param string $page
	 */
	public function record($userId, $page = "")
	{
		$this->sysLog->debug("userId=" . $userId);
		date_default_timezone_set("America/New_York");

		$requestUri = array_key_exists("SCRIPT_NAME", $_SERVER) ? $_SERVER["SCRIPT_NAME"] : "CLI";
		if ($page != "") {
			$requestUri = $page;
		}

		$now = date("Y-m-d H:i:s", time());

		$pv = new PageView();
		$pv->setUserId($userId);
		$pv->setAccessTime($now);
		$pv->setPage($requestUri);
		$this->create($pv);
	}

    /**
     * @param PageView $o
     * @param string $sql
     * @return mixed
     */
    public function create($o, $sql="")
	{
		$this->sysLog->debug();
		return parent::create($o);
	}

    /**
     * @param PageView $o
     * @param string $idColumn
     * @param string $sql
     * @return mixed
     */
    public function update($o, $idColumn = "id", $sql = "")
	{
		$this->sysLog->debug();
		return parent::update($o);
	}

    /**
     * @param PageView $o
     * @param string $idColumn
     * @param string $sql
     * @return mixed
     */
    public function delete($o, $idColumn = "id", $sql = "")
	{
		$this->sysLog->debug();
		return parent::delete($o);
	}

	// *******************************************************************************
	// Getters and Setters
	// *******************************************************************************

	/**
	 * @param $logLevel
	 */
	public function setLogLevel($logLevel)
	{
		$this->logLevel = $logLevel;
	}

	/**
	 * @return mixed
	 */
	public function getLogLevel()
	{
		return $this->logLevel;
	}

	/**
	 * @param $columnNames
	 */
	public static function setColumnNames($columnNames)
	{
		self::$columnNames = $columnNames;
	}

	/**
	 * @return array
	 */
	public static function getColumnNames()
	{
		return self::$columnNames;
	}

	/**
	 * @param null $dbRowObj
	 * @return PageView
	 */
	private function _set($dbRowObj = null)
	{
		$this->sysLog->debug();

		$o = new PageView();
		if ($dbRowObj) {
			foreach (self::$columnNames as $prop) {
				$o->set($prop, $dbRowObj->$prop);
			}
		}
		else {
			foreach (self::$columnNames as $prop) {
				$o->set($prop, null);
			}
		}
		return $o;
	}
}
