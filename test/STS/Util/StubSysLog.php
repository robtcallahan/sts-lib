<?php
/*******************************************************************************
 *
 * $Id: SysLog.php 74823 2013-04-30 17:55:03Z rcallaha $
 * $Date: 2013-04-30 13:55:03 -0400 (Tue, 30 Apr 2013) $
 * $Author: rcallaha $
 * $Revision: 74823 $
 * $HeadURL: https://svn.ultradns.net/svn/sts_tools/sts-lib/trunk/STS/Util/SysLog.php $
 *
 *******************************************************************************
 */
 
class StubSysLog extends \STS\Util\SysLog
{
	public function __construct($processName = null)
	{
        parent::__construct();
        parent::close();
	}

	public function syslog($sev, $msg)
	{
        return $sev . ": " . $msg;
    }
}