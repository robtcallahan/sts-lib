<?php
/*******************************************************************************
 *
 * $Id: SANScreenGenericTable.php 82460 2014-01-04 15:38:17Z rcallaha $
 * $Date: 2014-01-04 10:38:17 -0500 (Sat, 04 Jan 2014) $
 * $Author: rcallaha $
 * $Revision: 82460 $
 * $HeadURL: https://svn.ultradns.net/svn/sts_tools/sts-lib/trunk/STS/SANScreen/SANScreenGenericTable.php $
 *
 *******************************************************************************
 */

namespace STS\SANScreen;

use STS\DB\DBTable;

class SANScreenGenericTable extends DBTable
{
    protected $dbIndex = 'sanscreen';
   	protected $tableName = 'generic';
    protected $idAutoIncremented = false;

	protected static $columnNames = array("id", "dead", "wwn");

    public function __construct($config = null)
    {
        if ($config) {
            // need to add these to the config since won't be in the config file
            $config['tableName']         = $this->tableName;
            $config['dbIndex']           = $this->dbIndex;
            $config['idAutoIncremented'] = $this->idAutoIncremented;
        }
        parent::__construct($config);
        $this->sysLog->debug();
    }

    /**
	 * @param $id
	 * @return SANScreenGeneric
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

	// *******************************************************************************
	// CRUD methods
	// *******************************************************************************

	/**
	 * @param SANScreenGeneric $o
     * @param string $sql
	 * @return SANScreenGeneric
	 */
    public function create($o, $sql = "") {
   		$this->sysLog->debug();
   		$newId = parent::create($o, $sql);
   		return $this->getById($newId);
   	}

	/**
	 * @param SANScreenGeneric $o
     * @param string $idColumn
     * @param string $sql
	 * @return mixed
	 */
    public function update($o, $idColumn = "id", $sql = "")
   	{
   		$this->sysLog->debug();
        return parent::update($o, $idColumn, $sql);
   	}

	/**
	 * @param SANScreenGeneric $o
     * @param string $idColumn
     * @param string $sql
	 * @return mixed
	 */
    public function delete($o, $idColumn = "id", $sql = "") {
   		$this->sysLog->debug();
   		return parent::delete($o, $idColumn, $sql);
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
	 * @return SANScreenGeneric
	 */
	private function _set($dbRowObj = null)
	{
		$this->sysLog->debug();

		$o = new SANScreenGeneric();
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
