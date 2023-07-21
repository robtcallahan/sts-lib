<?php
/*******************************************************************************
 *
 * $Id: UserTable.php 74823 2013-04-30 17:55:03Z rcallaha $
 * $Date: 2013-04-30 13:55:03 -0400 (Tue, 30 Apr 2013) $
 * $Author: rcallaha $
 * $Revision: 74823 $
 * $HeadURL: https://svn.ultradns.net/svn/sts_tools/sts-lib/trunk/STS/Login/UserTable.php $
 *
 *******************************************************************************
 */

namespace STS\Database;

use STS\Util\SysLog;

class UserTable extends DBTable
{


    public function __construct()
    {
        $this->sysLogName = $GLOBALS['config']->appName;
        $this->logLevel   = $GLOBALS['config']->logLevel;

        parent::__construct($GLOBALS['config']->databases->dbtest, "user");

        $this->sysLog->debug();
    }

    /**
     * @param $id
     * @return User
     */
    public function getById($id)
    {
        $this->sysLog->debug("id=" . $id);
        $sql = "SELECT {$this->getQueryColumnsStr()}
                FROM   {$this->tableName}
		        WHERE  id = {$id};";
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
        $sql    = "SELECT {$this->getQueryColumnsStr()}
                FROM   {$this->tableName}
                WHERE  userName = '{$userName}'";
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

        $sql    = "SELECT {$this->getQueryColumnsStr()}
                FROM   {$this->tableName}
                WHERE  lastName = '{$lastName}'";
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

        $sql = "SELECT {$this->getQueryColumnsStr()}
                FROM   {$this->tableName}\n";

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

        $sql  = "SELECT {$this->getQueryColumnsStr()}
                FROM   {$this->tableName};";
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

        $sql  = "SELECT {$this->getQueryColumnsStr()}
                FROM   {$this->tableName};";
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
     * @return User
     */
    public function create(User $o)
    {
        $this->sysLog->debug();
        $newId = parent::create($o);
        return $this->getById($newId);
    }

    /**
     * @param User $o
     * @return User
     */
    public function update(User $o)
    {
        $this->sysLog->debug();
        parent::update($o);
        return $this->getById($o->getId());
    }

    /**
     * @param User $o
     * @return User
     */
    public function delete(User $o)
    {
        $this->sysLog->debug();
        return parent::delete($o);
    }

    // *******************************************************************************
    // Getters and Setters
    // *******************************************************************************

    /**
     * @param null $dbRowObj
     * @return User
     */
    private function _set($dbRowObj = null)
    {
        $o = new User();
        if ($dbRowObj) {
            foreach ($this->getColumnNames() as $prop) {
                $o->set($prop, $dbRowObj[$prop]);
            }
        } else {
            foreach ($this->getColumnNames() as $prop) {
                $o->set($prop, null);
            }
        }
        return $o;
    }
}
