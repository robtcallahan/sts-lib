<?php
/*******************************************************************************
 *
 * $Id: LoginTable.php 82444 2014-01-03 14:28:59Z rcallaha $
 * $Date: 2014-01-03 09:28:59 -0500 (Fri, 03 Jan 2014) $
 * $Author: rcallaha $
 * $Revision: 82444 $
 * $HeadURL: https://svn.ultradns.net/svn/sts_tools/sts-lib/trunk/STS/Login/LoginTable.php $
 *
 *******************************************************************************
 */

namespace STS\Login;

use STS\DB\DBTable;


class LoginTable extends DBTable
{
	const NOCONFIG = 100;

	protected $loginTable = "login";
	protected $userTable = "user";

	private static $columnNames = array(
		"userId", "numLogins", "lastLogin", "ipAddr", "userAgent",
		"id", "firstName", "lastName", "nickName", "userName", "empId", "title", "dept", "office", "email",
		"officePhone", "mobilePhone", "accessCode"
	);

	public function __construct($idAutoIncremented = true, $appName = "LoginTable", $appDB = "login")
	{

		$this->dbIndex   = $GLOBALS['config']->appDB;
		$this->tableName = "login";
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
    			where  userId = " . $user->getId() . ";";
		$result = $this->sqlQueryRow($sql);
		return $this->_set($result);
	}

	/**
	 * @return Login[]
	 */
	public function getAll()
	{
		$this->sysLog->debug();
		$sql   = "select " . $this->loginTable . ".*, " . $this->userTable . ".*
		          from   " . $this->loginTable . ", " . $this->userTable . "
		          where  " . $this->userTable . ".id = " . $this->loginTable . ".userId
		          order by " . $this->userTable . ".lastName;";
		$rows  = $this->sqlQuery($sql);
		$array = array();
		for ($i = 0; $i < count($rows); $i++) {
			$array[] = $this->_set($rows[$i]);
		}
		return $array;
	}

	// *******************************************************************************
	// CRUD methods
	// *******************************************************************************

	/**
	 * @param int $userId
	 */
	public function record($userId)
	{
		$this->sysLog->debug("userId=" . $userId);
		date_default_timezone_set("America/New_York");

		$userAgent = array_key_exists("HTTP_USER_AGENT", $_SERVER) ? $_SERVER["HTTP_USER_AGENT"] : "CLI";
		$ipAddr    = array_key_exists("REMOTE_ADDR", $_SERVER) ? $_SERVER["REMOTE_ADDR"] : "localhost";

		$sql    = "select userId, numLogins
		        from   login
		        where  userId = " . $userId . ";";
		$result = $this->sqlQueryRow($sql);
		$now    = date("Y-m-d H:i:s", time());

		if ($result && $result->userId != "") {
			$sql = "update login
			        set
			               lastLogin = '{$now}',
			               numLogins = " . ($result->numLogins + 1) . ",
			               ipAddr    = '{$ipAddr}',
			               userAgent = '" . $this->mysqlEscapeString($userAgent) . "'
			        where
			               userId = {$userId}";
		}
		else {
			$sql = "insert into login (userId, lastLogin, numLogins, ipAddr, userAgent)
			        values ({$userId}, '{$now}', 1, '{$ipAddr}', '" . $this->mysqlEscapeString($userAgent) . "')";
		}
		$this->sql($sql);
	}

    /**
     * @param User $o
     * @param string $sql
     * @return mixed
     */
    public function create($o, $sql="")
	{
		$this->sysLog->debug();
		return parent::create($o);
	}

    /**
     * @param User $o
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
     * @param User $o
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
	 * @return void
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
	 * @return void
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
	 * @return Login
	 */
	private function _set($dbRowObj = null)
	{
		$this->sysLog->debug();

		$o = new Login();
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
