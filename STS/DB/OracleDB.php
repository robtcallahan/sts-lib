<?php
/*******************************************************************************
 *
 * $Id: OracleDB.php 76808 2013-07-15 19:16:25Z rcallaha $
 * $Date: 2013-07-15 15:16:25 -0400 (Mon, 15 Jul 2013) $
 * $Author: rcallaha $
 * $Revision: 76808 $
 * $HeadURL: https://svn.ultradns.net/svn/sts_tools/sts-lib/trunk/STS/DB/OracleDB.php $
 *
 *******************************************************************************
 */

namespace STS\DB;

use STS\Util\Obfuscation;
use STS\Util\SysLog;

class OracleDB implements DB
{
	private $dbName;
	private $link;

	protected $sysLog;
	protected $logLevel;

	public function __construct($dbName)
	{
		// Set up SysLog
		$this->sysLog   = SysLog::singleton($GLOBALS['config']->appName);
		$this->logLevel = $GLOBALS['config']->logLevel;
		$this->sysLog->debug();

		$this->config = $GLOBALS['config']->databases->{$dbName};
	}

	/**
	 * @throws \ErrorException
	 */
	public function connect()
	{
		$this->sysLog->debug();
		$crypt = new Obfuscation();

		$connString = "{$this->config->server}:{$this->config->port}/{$this->config->database}";

		$this->link = oci_connect($this->config->username, $crypt->decrypt($this->config->password), $connString);
		if (!$this->link) {
			throw new \ErrorException("Could not connect to Oracle database {$this->config->database}. " . oci_error());
		}
	}

	/**
	 * @return mixed
	 */
	public function getDbName()
	{
		return $this->dbName;
	}

	/**
	 * @throws \ErrorException
	 */
	public function errno()
	{
		throw new \ErrorException("Function not implemented");
	}

	/**
	 * @return array
	 */
	public function error()
	{
		return oci_error($this->link);
	}

	/**
	 * @param $string
	 * @return mixed
	 */
	public function escape_string($string)
	{
		return $string;
	}

	/**
	 * @param $sql
	 * @return resource|string
	 * @throws \ErrorException
	 */
	public function query($sql)
	{
		if ($sql == "") {
			return "";
		}
		try {
			$stId = oci_parse($this->link, $sql);
			$ok   = oci_execute($stId);

			if (!$ok) {
				throw new \ErrorException("Query failed on Oracle database {$this->dbName}. Query: {$sql}. Oracle Error: " . $this->error());
			}
			return $stId;
		}
		catch (\Exception $e) {
			throw new \ErrorException($e->getMessage() . "\nsql={$sql}", $e->getCode(), 0, $e->getFile(), $e->getLine());
		}
	}

	/**
	 * @param $stId
	 * @return array
	 */
	public function fetch_array($stId)
	{
		return oci_fetch_array($stId, OCI_ASSOC);
	}

	/**
	 * @param $stId
	 * @return array
	 */
	public function fetch_row($stId)
	{
		return oci_fetch_row($stId);
	}

	/**
	 * @param $stId
	 * @return array
	 */
	public function fetch_assoc($stId)
	{
		return oci_fetch_assoc($stId);
	}

	/**
	 * @param $stId
	 * @return object
	 */
	public function fetch_object($stId)
	{
		return oci_fetch_object($stId);
	}

	/**
	 * @param $stId
	 * @return int
	 */
	public function num_rows($stId)
	{
		return oci_num_rows($stId);
	}

	/**
	 * @return bool
	 */
	public function close()
	{
		return oci_close($this->link);
	}

	/**
	 * @throws \ErrorException
	 */
	public function getInsertId()
	{
		//throw new \ErrorException("Function not implemented");
        return null;
	}

	/**
	 * @param string $sql
	 * @return array|string
	 */
	public function getRow($sql = "")
	{
		if ($sql == "") return "";
		$stId = $this->query($sql);
		return $this->fetch_assoc($stId);
	}

	/**
	 * @param string $sql
	 * @return object|string
	 */
	public function getObject($sql = "")
	{
		if ($sql == "") return "";

		$stId = $this->query($sql);
		return $this->fetch_object($stId);
	}

	/**
	 * @param string $sql
	 * @return array
	 */
	public function getAllRows($sql = "")
	{
		$rows = array();
		if ($sql == "") return $rows;

		$stId = $this->query($sql);
		while ($hash = $this->fetch_assoc($stId)) {
			$rows[] = $hash;
		}
		return ($rows);
	}

	/**
	 * @param string $sql
	 * @return array
	 */
	public function getAllObjects($sql = "")
	{
		$rows = array();
		if ($sql == "") return $rows;

		$stId = $this->query($sql);
		while ($hash = $this->fetch_object($stId)) {
			$rows[] = $hash;
		}
		return ($rows);
	}
}   
