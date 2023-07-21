<?php
/*******************************************************************************
 *
 * $Id: HPSIMBladeExceptionTable.php 82519 2014-01-07 20:06:01Z rcallaha $
 * $Date: 2014-01-07 15:06:01 -0500 (Tue, 07 Jan 2014) $
 * $Author: rcallaha $
 * $Revision: 82519 $
 * $HeadURL: https://svn.ultradns.net/svn/sts_tools/sts-lib/trunk/STS/HPSIM/HPSIMBladeExceptionTable.php $
 *
 *******************************************************************************
 */

namespace STS\HPSIM;

use STS\DB\DBTable;


class HPSIMBladeExceptionTable extends DBTable
{
	protected static $columnNames = array("id", "bladeId", "exceptionTypeId", "errorText", "dateUpdated", "userUpdated");

    protected $dbIndex = 'hpsim';
   	protected $tableName = 'blade_exception';
    protected $idAutoIncremented = true;

	private $exceptionTypeTable;
	private $exceptionNumbers;
	private $exceptionTypeIds;

    /**
     * @param null $config
     */
    public function __construct($config = null)
	{
        if ($config) {
            // need to add these to the config since won't be in the config file
            $config['tableName'] = $this->tableName;
            $config['dbIndex'] = $this->dbIndex;
            $config['idAutoIncremented'] = $this->idAutoIncremented;
        }
        parent::__construct($config);

		$this->exceptionTypeTable = HPSIMExceptionTypeTable::singleton();
		$this->exceptionNumbers   = $this->exceptionTypeTable->getExceptionNumbersByObject("blade");
		$this->exceptionTypeIds   = $this->exceptionTypeTable->getExceptionIdsByObject("blade");

		$this->sysLog->debug();
	}

	/**
	 * @param $id
	 * @return HPSIMBladeException
	 */
	public function getById($id)
	{
		$this->sysLog->debug("id=" . $id);
		$sql = "select {$this->getQueryColumnsStr()}
                from   {$this->tableName}
		        where  id = " . $id . ";";
		$row = $this->sqlQueryRow($sql);
		return $this->_set($row);
	}

	/**
	 * @param $bladeId
	 * @return HPSIMBladeException
	 */
	public function getByBladeId($bladeId)
	{
		$this->sysLog->debug("bladeId=" . $bladeId);
		$sql        = "select {$this->getQueryColumnsStr()}
                from   {$this->tableName}
		        where  exceptionTypeId != 12
		          and  bladeId = " . $bladeId . ";";
		$result     = $this->sqlQueryRow($sql);
		$bladeExcep = $this->_set($result);
		if ($bladeExcep->getExceptionTypeId() != "") {
			$bladeExcep->setExceptionTypeDescr($this->exceptionTypeIds[$bladeExcep->getExceptionTypeId()]->getExceptionDescr());
		}
		return $bladeExcep;
	}

    public function getQueryErrorByBladeId($bladeId)
    {
        $this->sysLog->debug("bladeId=" . $bladeId);
        $sql        = "select {$this->getQueryColumnsStr()}
                      from   {$this->tableName}
      		        where  exceptionTypeId = 12
      		          and  bladeId = " . $bladeId . ";";
        $result     = $this->sqlQueryRow($sql);
        $bladeExcep = $this->_set($result);
        if ($bladeExcep->getExceptionTypeId() != "") {
            $bladeExcep->setExceptionTypeDescr($this->exceptionTypeIds[$bladeExcep->getExceptionTypeId()]->getExceptionDescr());
        }
        return $bladeExcep;
    }

	/**
	 * @param string $orderBy
	 * @param string $dir
	 * @return array
	 */
	public function getAllCoreHosting($orderBy = "id", $dir = "asc")
	{
		$this->sysLog->debug();
		$sql    = "select be.id, be.bladeId, be.exceptionTypeId, be.dateUpdated, be.userUpdated,
		                  b.deviceName as bladeName, c.deviceName as chassisName
                   from   {$this->tableName} be,
                          blade b,
                          chassis c
                   where  b.id = be.bladeId
                     and  c.id = b.chassisId
		           order by " . $orderBy . $dir . ";";
		$results = $this->sqlQuery($sql);
		$objects  = array();
		for ($i = 0; $i < count($results); $i++) {
			if (preg_match("/^(st|ch)(de|mc|nt|ul)/", $results[$i]->chassisName)) continue;
			$objects[] = $this->_set($results[$i]);
		}
		return $objects;
	}

	/**
	 * @param string $orderBy
	 * @param string $dir
	 * @return HPSIMBladeException[]
	 */
	public function getAll($orderBy = "id", $dir = "asc")
	{
		$this->sysLog->debug();
		$sql    = "select {$this->getQueryColumnsStr()}
                   from   {$this->tableName}
		           order by " . $orderBy . $dir . ";";
		$result = $this->sqlQuery($sql);
		$objects  = array();
		for ($i = 0; $i < count($result); $i++) {
			$objects[] = $this->_set($result[$i]);
		}
		return $objects;
	}


	// *******************************************************************************
	// CRUD methods
	// *******************************************************************************

    /**
     * @param HPSIMBladeException $e
     * @param $exceptionNumber
     * @return HPSIMBladeException
     */
    public function updateCreate(HPSIMBladeException $e, $exceptionNumber)
	{
		$this->sysLog->debug("exceptionNumber=" . $exceptionNumber);
		$e->setExceptionTypeId($this->exceptionNumbers[$exceptionNumber]->getId());
		$e->setUserUpdated("stsuser");
		$e->setDateUpdated(date("Y-m-d H:i:s"));

		$sql = "select {$this->getQueryColumnsStr()}
                from   {$this->tableName}
		        where  bladeId = {$e->getBladeId()}
		          and  exceptionTypeId = " . $e->getExceptionTypeId() . ";";

		$existing = $this->sqlQueryRow($sql);
		if ($existing && $existing->id != "") {
			$e->setId($existing->id);
			$this->update($e);
			return $e;
		}
		else {
			// return $this->create($e);
			$newId = parent::create($e);
			return $this->getById($newId);
		}
	}

    /**
     * @param HPSIMBladeException $o
     * @param string $sql
     * @return HPSIMBladeException
     */
    public function create($o, $sql="")
	{
		$this->sysLog->debug();
		$newId = parent::create($o);
		return $this->getById($newId);
	}

    /**
     * @param HPSIMBladeException $o
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
     * @param $bladeId
     * @param $exceptionNumber
     */
    public function deleteByBladeIdAndNumber($bladeId, $exceptionNumber)
    {
        $exceptionTypeId = $this->exceptionNumbers[$exceptionNumber]->getId();

        $sql = "select {$this->getQueryColumnsStr()}
                from   {$this->tableName}
      		    where  bladeId = {$bladeId}
      		      and  exceptionTypeId = " . $exceptionTypeId . ";";
        $existing = $this->_set($this->sqlQueryRow($sql));
        if ($existing && $existing->getId()) {
            $this->delete($existing);
        }
    }

    /**
     * @param HPSIMBladeException $o
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
	// * Getters and Setters
	// *****************************************************************************

	/**
	 * @return HPSIMExceptionType[]
	 */
	public function getExceptionTypeIds()
	{
		return $this->exceptionTypeIds;
	}

	/**
	 * @return HPSIMExceptionType[]
	 */
	public function getExceptionNumbers()
	{
		return $this->exceptionNumbers;
	}

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
	 * @return HPSIMBladeException
	 */
	private function _set($dbRowObj = null)
	{
		$this->sysLog->debug();

		$o = new HPSIMBladeException();
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
