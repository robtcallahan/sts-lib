<?php
/*******************************************************************************
 *
 * $Id: MySqlDB.php 82444 2014-01-03 14:28:59Z rcallaha $
 * $Date: 2014-01-03 09:28:59 -0500 (Fri, 03 Jan 2014) $
 * $Author: rcallaha $
 * $Revision: 82444 $
 * $HeadURL: https://svn.ultradns.net/svn/sts_tools/sts-lib/trunk/STS/DB/MySqlDB.php $
 *
 *******************************************************************************
 */

namespace STS\DB;

use STS\Util\Obfuscation;
use STS\Util\SysLog;
use Zend\Json\Server\Exception\ErrorException;

class MySqlDB implements DB
{
	private $config;
	private $link;

	protected $sysLog;
	protected $logLevel;

	public function __construct($config)
	{
        #print print_r($config, true); exit;
        $this->config = $config['databases'][$config['dbIndex']];

		// Set up SysLog
		$this->sysLog   = SysLog::singleton($config['appName']);
		$this->logLevel = $config['logLevel'];
		$this->sysLog->debug();
	}

	/**
	 * @return void
	 */
	public function connect()
	{
		$this->sysLog->debug();
		$crypt = new Obfuscation();

		$server = $this->config['server'];
		if (isset($this->config['port'])) {
			$server .= ":{$this->config['port']}";
		}

        $this->link = mysqli_connect(
			$server,
			$this->config['username'],
			$crypt->decrypt($this->config['password']),
			$this->config['database']);

		if (mysqli_connect_errno()) {
			throw new ErrorException("Connect failed: %s\n", mysqli_connect_error());
		}
	}

	/**
	 * @return int
	 */
	public function errno()
	{
		return mysqli_errno($this->link);
	}

	/**
	 * @return string
	 */
	public function error()
	{
		return mysqli_error($this->link);
	}

	/**
	 * @param $string
	 * @return string
	 */
	public function escape_string($string)
	{
		return mysqli_real_escape_string($this->link, $string);
	}

	/**
	 * @param $sql
	 * @return resource
	 * @throws \ErrorException
	 */
	public function query($sql)
	{
		// compact sql string for logging purposes: replace occurances of 4 or more spaces with just one space
		$logSql = preg_replace("/\s\s\s\s+/", " ", $sql);
		$this->sysLog->debug("sql=" . $logSql);

		try {
			$result = mysqli_query($this->link, $sql);
			if (!$result) {
				throw new \ErrorException($this->error(), $this->errno());
			}
			return $result;
		}
		catch (\Exception $e) {
			throw new \ErrorException($e->getMessage() . "\nsql={$sql}", $e->getCode(), 0, $e->getFile(), $e->getLine());
		}
	}

	/**
	 * @param     $result
	 * @param int $array_type
	 * @return array
	 */
	public function fetch_array($result, $array_type = MYSQLI_BOTH)
	{
		return mysqli_fetch_array($result, $array_type);
	}

	/**
	 * @param $result
	 * @return array
	 */
	public function fetch_row($result)
	{
		return mysqli_fetch_row($result);
	}

	/**
	 * @param $result
	 * @return array
	 */
	public function fetch_assoc($result)
	{
		return mysqli_fetch_assoc($result);
	}

	/**
	 * @param $result
	 * @return object|\stdClass
	 */
	public function fetch_object($result)
	{
		return mysqli_fetch_object($result);
	}

	/**
	 * @param $result
	 * @return int
	 */
	public function num_rows($result)
	{
		return mysqli_num_rows($result);
	}

	/**
	 * @return int
	 */
	public function getAffectedRows()
	{
		return mysqli_affected_rows($this->link);
	}

	/**
	 * @return bool
	 */
	public function close()
	{
		return mysqli_close($this->link);
	}

	/**
	 * @return int
	 */
	public function getInsertId()
	{
		return mysqli_insert_id($this->link);
	}

	/**
	 * @param string $sql
	 * @return array|string
	 */
	public function getRow($sql = "")
	{
		if ($sql == "") {
			return "";
		}

		$result = $this->query($sql);
		return $this->fetch_assoc($result);
	}

	/**
	 * @param string $sql
	 * @return object|\stdClass|string
	 */
	public function getObject($sql = "")
	{
		if ($sql == "") {
			return "";
		}

		$result = $this->query($sql);
		return $this->fetch_object($result);
	}

	/**
	 * @param string $sql
	 * @return array
	 */
	public function getAllRows($sql = "")
	{
		$rows = array();
		if ($sql == "") {
			return $rows;
		}

		$result = $this->query($sql);
		while ($hash = $this->fetch_assoc($result)) {
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
		if ($sql == "") {
			return $rows;
		}

		$result = $this->query($sql);
		while ($hash = $this->fetch_object($result)) {
			$rows[] = $hash;
		}
		return ($rows);
	}
}
