<?php
/*******************************************************************************
 *
 * $Id: HPSIMVMExceptionTable.php 81815 2013-12-09 20:00:43Z rcallaha $
 * $Date: 2013-12-09 15:00:43 -0500 (Mon, 09 Dec 2013) $
 * $Author: rcallaha $
 * $Revision: 81815 $
 * $HeadURL: https://svn.ultradns.net/svn/sts_tools/sts-lib/trunk/STS/HPSIM/HPSIMVMExceptionTable.php $
 *
 *******************************************************************************
 */

namespace STS\HPSIM;

use STS\DB\DBTable;


class HPSIMVMExceptionTable extends DBTable
{
    protected $dbIndex = 'hpsim';
   	protected $tableName = 'vm_exception';
    protected $idAutoIncremented = true;

	protected static $columnNames = array("id", "vmId", "exceptionTypeId", "dateUpdated", "userUpdated");

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
		$this->exceptionNumbers   = $this->exceptionTypeTable->getExceptionNumbersByObject("vm");
		$this->exceptionTypeIds   = $this->exceptionTypeTable->getExceptionIdsByObject("vm");
	}

	/**
	 * @param $id
	 * @return HPSIMVMException
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
	 * @param $vmId
	 * @return HPSIMVMException
	 */
	public function getByVmId($vmId)
	{
		$this->sysLog->debug("vmId=" . $vmId);
		$sql     = "select {$this->getQueryColumnsStr()}
                from   {$this->tableName}
		        where  vmId = {$vmId};";
		$result  = $this->sqlQueryRow($sql);
		$vmExcep = $this->_set($result);
		if ($vmExcep->getExceptionTypeId() != "") {
			$vmExcep->setExceptionTypeDescr($this->exceptionTypeIds[$vmExcep->getExceptionTypeId()]->getExceptionDescr());
		}
		return $vmExcep;
	}

	/**
	 * @param string $orderBy
	 * @param string $dir
	 * @return array
	 */
	public function getAllCoreHosting($orderBy = "id", $dir = "asc")
	{
		$this->sysLog->debug();
		$sql    = "select vme.id, vme.vmId, vme.exceptionTypeId, vme.dateUpdated, vme.userUpdated,
		                  b.deviceName as bladeName, c.deviceName as chassisName
                   from   vm_exception vme,
                          vm,
                          blade b,
                          chassis c
                   where  vm.id = vme.vmId
                     and  b.id = vm.bladeId
                     and  c.id = b.chassisId
		           order by {$orderBy} {$dir};";
		$results = $this->sqlQuery($sql);
		$objects  = array();
		for ($i = 0; $i < count($results); $i++) {
			if (preg_match("/^(st|ch)(de|mc|nt|ul)/", $results[$i]->vmName)) continue;
			$objects[] = $this->_set($results[$i]);
		}
		return $objects;
	}

	/**
	 * @param string $orderBy
	 * @param string $dir
	 * @return HPSIMVMException[]
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
     * @param HPSIMVMException $e
     * @param string $exceptionNumber
     * @return HPSIMVMException
     */
    public function updateCreate(HPSIMVMException $e, $exceptionNumber)
	{
		$this->sysLog->debug("exceptionNumber=" . $exceptionNumber);
		$e->setExceptionTypeId($this->exceptionNumbers[$exceptionNumber]->getId());
		$e->setUserUpdated("stsuser");
		$e->setdateUpdated(date("Y-m-d H:i:s"));

		$sql = "select {$this->getQueryColumnsStr()}
                from   {$this->tableName}
		        where  vmId = {$e->getVmId()}
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
     * @param HPSIMVMException $o
     * @param string $sql
     * @return HPSIMVMException
     */
    public function create($o, $sql="")
	{
		$this->sysLog->debug();
		$newId = parent::create($o);
		return $this->getById($newId);
	}

    /**
     * @param HPSIMVMException $o
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
     * @param HPSIMVMException $o
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
	 * @param $exceptionNumbers
	 */
	public function setExceptionNumbers($exceptionNumbers)
	{
		$this->exceptionNumbers = $exceptionNumbers;
	}

	/**
	 * @return HPSIMExceptionType[]
	 */
	public function getExceptionNumbers()
	{
		return $this->exceptionNumbers;
	}

	/**
	 * @param $exceptionTypeIds
	 */
	public function setExceptionTypeIds($exceptionTypeIds)
	{
		$this->exceptionTypeIds = $exceptionTypeIds;
	}

	/**
	 * @return HPSIMExceptionType[]
	 */
	public function getExceptionTypeIds()
	{
		return $this->exceptionTypeIds;
	}

	/**
	 * @param null $dbRowObj
	 * @return HPSIMVMException
	 */
	private function _set($dbRowObj = null)
	{
		$this->sysLog->debug();

		$o = new HPSIMVMException();
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
