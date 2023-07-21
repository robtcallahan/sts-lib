<?php
/*******************************************************************************
 *
 * $Id: SwatLog.php 74756 2013-04-26 17:08:54Z rcallaha $
 * $Date: 2013-04-26 13:08:54 -0400 (Fri, 26 Apr 2013) $
 * $Author: rcallaha $
 * $Revision: 74756 $
 * $HeadURL: https://svn.ultradns.net/svn/sts_tools/sts-lib/trunk/STS/Util/SwatLog.php $
 *
 *******************************************************************************
 */

namespace STS\Util;

use STS\DB\MySqlDB;

class SwatLog
{
	private $config;
	private $appID;
	private $user;
	private $swatDB;
	private $processName;

	protected $sysLog;
	protected $logLevel;

	// A private constructor; prevents direct creation of object
	public function __construct()
	{
		// Set up SysLog
		$this->sysLog   = SysLog::singleton($GLOBALS['config']->appName);
		$this->logLevel = $GLOBALS['config']->logLevel;
		$this->sysLog->debug();

		$this->pid = getmypid();
		$this->appID = $GLOBALS['config']->appID;
		$this->user = $_SERVER['LOGNAME'];
		$this->processName = $this->_getProcessName();

		$this->swatDB = new MySqlDB('swat');
	}

	/**
	 * @param $action
	 * @return bool
	 */
	public function createEntry($action)
	{
		$hostname = exec("hostname");
		$sql = "INSERT INTO swat VALUES (NULL, '{$hostname}', '{$this->appID}', '{$this->processName}', $this->pid, '{$this->user}', '{$action}', NOW())";
		$this->swatDB->connect();
		$this->swatDB->query($sql);
		$this->swatDB->close();
		return true;
	}

	/**
	 * @return mixed
	 */
	private function _getProcessName(){
		// pull full file path
		$break = Explode('/', $_SERVER['SCRIPT_FILENAME']);
		// return only the file name
		return $break[count($break) - 1];

	}
}
