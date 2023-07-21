<?php
/*******************************************************************************
 *
 * $Id: HTTPParameters.php 76561 2013-07-04 20:45:18Z rcallaha $
 * $Date: 2013-07-04 16:45:18 -0400 (Thu, 04 Jul 2013) $
 * $Author: rcallaha $
 * $Revision: 76561 $
 * $HeadURL: https://svn.ultradns.net/svn/sts_tools/sts-lib/trunk/STS/Util/HTTPParameters.php $
 *
 *******************************************************************************
 */

namespace STS\Util;

use STS\Util\SysLog;

class HTTPParameters
{
    protected $params;

    protected $sysLog;
   	protected $logLevel;

	public function __construct()
	{
		// Set up SysLog
		$this->sysLog   = SysLog::singleton($GLOBALS['config']->appName);
		$this->logLevel = $GLOBALS['config']->logLevel;
		$this->sysLog->debug();
	}

    public function getParams($expectedParams = array())
    {
        $params = array();
        foreach ($expectedParams as $paramName) {
            if (array_key_exists($paramName, $_POST)) {
                $params[$paramName] = $_POST[$paramName];
            } else if (array_key_exists($paramName, $_GET)) {
                $params[$paramName] = $_GET[$paramName];
            } else {
                $params[$paramName] = "";
            }
        }
        $this->params = $params;
        return $params;
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
}
