<?php
/*******************************************************************************
 *
 * $Id: HPSIMMgmtProcessorExceptionTable.php 81815 2013-12-09 20:00:43Z rcallaha $
 * $Date: 2013-12-09 15:00:43 -0500 (Mon, 09 Dec 2013) $
 * $Author: rcallaha $
 * $Revision: 81815 $
 * $HeadURL: https://svn.ultradns.net/svn/sts_tools/sts-lib/trunk/STS/HPSIM/HPSIMMgmtProcessorExceptionTable.php $
 *
 *******************************************************************************
 */

namespace STS\HPSIM;

use STS\DB\DBTable;


class HPSIMMgmtProcessorExceptionTable extends DBTable
{
    protected $dbIndex = 'hpsim';
   	protected $tableName = 'mgmt_processor_exception';
    protected $idAutoIncremented = true;

	protected static $columnNames = array("id", "mgmtProcessorId", "exceptionTypeId", "dateUpdated", "userUpdated");

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
		$this->sysLog->debug();

		$this->exceptionTypeTable = HPSIMExceptionTypeTable::singleton();
		$this->exceptionNumbers   = $this->exceptionTypeTable->getExceptionNumbersByObject("mgmtproc");
		$this->exceptionTypeIds   = $this->exceptionTypeTable->getExceptionIdsByObject("mgmtproc");
	}

	/**
	 * @param $id
	 * @return HPSIMMgmtProcessorException
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
	 * @param $mpId
	 * @return HPSIMMgmtProcessorException
	 */
	public function getByMgmtProcessorId($mpId)
	{
		$this->sysLog->debug("mpId=" . $mpId);
		$sql                = "select {$this->getQueryColumnsStr()}
                from   {$this->tableName}
		        where  mgmtProcessorId = {$mpId};";
		$result             = $this->sqlQueryRow($sql);
		$mgmtProcessorExcep = $this->_set($result);
		if ($mgmtProcessorExcep->getExceptionTypeId() != "") {
			$mgmtProcessorExcep->setExceptionTypeDescr($this->exceptionTypeIds[$mgmtProcessorExcep->getExceptionTypeId()]->getExceptionDescr());
		}
		return $mgmtProcessorExcep;
	}

	/**
	 * @param string $orderBy
	 * @param string $dir
	 * @return HPSIMMgmtProcessorException[]
	 */
	public function getAll($orderBy = "id", $dir = "asc")
	{
		$this->sysLog->debug();
		$sql    = "select {$this->getQueryColumnsStr()}
                from   {$this->tableName}
		        order by {$orderBy} {$dir};";
		$result = $this->sqlQuery($sql);
		$array  = array();
		for ($i = 0; $i < count($result); $i++) {
			$array[] = $this->_set($result[$i]);
		}
		return $array;
	}


	// *******************************************************************************
	// CRUD methods
	// *******************************************************************************

    /**
     * @param HPSIMMgmtProcessorException $e
     * @param string $exceptionNumber
     * @return HPSIMMgmtProcessorException
     */
    public function updateCreate(HPSIMMgmtProcessorException $e, $exceptionNumber)
	{
		$this->sysLog->debug("exceptionNumber=" . $exceptionNumber);
		$e->setExceptionTypeId($this->exceptionNumbers[$exceptionNumber]->getId());
		$e->setUserUpdated("stsuser");
		$e->setdateUpdated(date("Y-m-d H:i:s"));

		$sql = "select {$this->getQueryColumnsStr()}
                from   {$this->tableName}
		        where  mgmtProcessorId = {$e->getMgmtProcessorId()}
		          and  exceptionTypeId = {$e->getExceptionTypeId()};";

		$existing = $this->sqlQueryRow($sql);
		if ($existing && $existing->id != "") {
			$e->setId($existing->id);
			$this->update($e);
			return $e;
		}
		else {
			$newId = parent::create($e);
			return $this->getById($newId);
		}
	}

    /**
     * @param HPSIMMgmtProcessorException $o
     * @param string $sql
     * @return HPSIMMgmtProcessorException
     */
    public function create($o, $sql="")
	{
		$this->sysLog->debug();
		$newId = parent::create($o);
		return $this->getById($newId);
	}

    /**
     * @param HPSIMMgmtProcessorException $o
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
     * @param HPSIMMgmtProcessorException $o
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
	 * @return HPSIMMgmtProcessorException
	 */
	private function _set($dbRowObj = null)
	{
		$this->sysLog->debug();

		$o = new HPSIMMgmtProcessorException();
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
