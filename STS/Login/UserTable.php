<?php
/*******************************************************************************
 *
 * $Id: UserTable.php 82444 2014-01-03 14:28:59Z rcallaha $
 * $Date: 2014-01-03 09:28:59 -0500 (Fri, 03 Jan 2014) $
 * $Author: rcallaha $
 * $Revision: 82444 $
 * $HeadURL: https://svn.ultradns.net/svn/sts_tools/sts-lib/trunk/STS/Login/UserTable.php $
 *
 *******************************************************************************
 */

namespace STS\Login;


use STS\DB\DBTable;


class UserTable extends DBTable
{
	protected static $columnNames = array(
		"id", "firstName", "lastName", "nickName", "userName",
		"empId", "title", "dept", "office", "email", "officePhone", "mobilePhone", "accessCode"
	);

	public function __construct($idAutoIncremented = true, $appName = "UserTable", $appDB = "user")
	{
		$this->dbIndex   = $GLOBALS['config']->appDB;
		$this->tableName = "user";
        $this->idAutoIncremented = $idAutoIncremented;
		parent::__construct();
		$this->sysLog->debug();
	}

	/**
	 * @param $id
	 * @return User
	 */
	public function getById($id)
	{
		$this->sysLog->debug("id=" . $id);
		$sql = "select {$this->getQueryColumnsStr()}
                from   {$this->tableName}
		        where  id = {$id};";
		$row = $this->sqlQueryRow($sql);
		return $this->_set($row);
	}

	/**
	 * @param string $userName
	 * @return User
	 */
	public function getByUserName($userName)
	{
		$this->sysLog->debug("userName=" . $userName);
		$sql    = "select {$this->getQueryColumnsStr()}
                from   {$this->tableName}
                where  userName = '{$userName}'";
		$result = $this->sqlQueryRow($sql);
		return $this->_set($result);
	}

	/**
	 * @param string $lastName
	 * @return User
	 */
	public function getByLastName($lastName)
	{
		$this->sysLog->debug("lastName=" . $lastName);

		$sql    = "select {$this->getQueryColumnsStr()}
                from   {$this->tableName}
                where  lastName = '{$lastName}'";
		$result = $this->sqlQueryRow($sql);
		return $this->_set($result);
	}

	/**
	 * @param string $query
	 * @return User[]
	 */
	public function getByNameLike($query = "")
	{
		$this->sysLog->debug();

		$sql = "select {$this->getQueryColumnsStr()}
                from   {$this->tableName}\n";

		if ($query !== "") {
			$sql .= "WHERE lastName like '%{$query}%' OR firstName like '%{$query}%' OR userName like '%{$query}%'\n";
		}
		$sql .= "ORDER  BY lastName;";
		$rows = $this->sqlQuery($sql);

		$users = array();
		for ($i = 0; $i < count($rows); $i++) {
			$users[] = $this->_set($rows[$i]);
		}
		return $users;
	}

	/**
	 * @return User[]
	 */
	public function getIdHash()
	{
		$this->sysLog->debug();

		$sql  = "select {$this->getQueryColumnsStr()}
                from   {$this->tableName};";
		$rows = $this->sqlQuery($sql);

		$userHash = array();
		for ($i = 0; $i < count($rows); $i++) {
			$u                     = $this->_set($rows[$i]);
			$userHash[$u->getId()] = $u;
		}
		return $userHash;
	}

	/**
	 * @return User[]
	 */
	public function getUserNameHash()
	{
		$this->sysLog->debug();

		$sql  = "select {$this->getQueryColumnsStr()}
                from   {$this->tableName};";
		$rows = $this->sqlQuery($sql);

		$userHash = array();
		for ($i = 0; $i < count($rows); $i++) {
			$u                           = $this->_set($rows[$i]);
			$userHash[$u->getUserName()] = $rows[$i];
		}
		return $userHash;
	}


	// *******************************************************************************
	// CRUD methods
	// *******************************************************************************

    /**
     * @param User $o
     * @param string $sql
     * @return User
     */
	public function create($o, $sql="")
	{
		$this->sysLog->debug();
		$newId = parent::create($o);
		return $this->getById($newId);
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

		$o = new User();
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
