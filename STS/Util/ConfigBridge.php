<?php
/*******************************************************************************
 *
 * $Id: ConfigBridge.php 74753 2013-04-26 15:07:24Z rcallaha $
 * $Date: 2013-04-26 11:07:24 -0400 (Fri, 26 Apr 2013) $
 * $Author: rcallaha $
 * $Revision: 74753 $
 * $HeadURL: https://svn.ultradns.net/svn/sts_tools/sts-lib/trunk/STS/Util/ConfigBridge.php $
 *
 *******************************************************************************
 */

namespace STS\Util;

class ConfigBridge
{
	public static function parseConfig($config=null)
    {
	    if ($config == null) {
	        throw new \ErrorException("config structure not passed");
        }

	    $myConfig = (object) array();

		if (is_array($config)) {
			$myConfig->appName = array_key_exists('appName', $config) ? $config['appName'] : 'STS';
			$myConfig->appDB = array_key_exists('appDB', $config) ? $config['appDB'] : null;
			$myConfig->logLevel = array_key_exists('logLevel', $config) ? $config['logLevel'] : SysLog::NOTICE;
			$myConfig->adminEmail = array_key_exists('adminEmail', $config) ? $config['adminEmail'] : 'Core Tools Group <coretoolsgroup@neustar.biz>';

			$myConfig->databases = array_key_exists('databases', $config) ? $config['databases'] : null;
			$myConfig->servicenow = array_key_exists('servicenow', $config) ? $config['servicenow'] : null;
			$myConfig->ldap = array_key_exists('ldap', $config) ? $config['ldap'] : null;
			$myConfig->activedir = array_key_exists('activedir', $config) ? $config['activedir'] : null;
		}
		else if (is_object($config)) {
			$myConfig->appName = property_exists($config, 'appName') ? $config->appName : 'STS';
			$myConfig->appDB = property_exists($config, 'appDB') ? $config->appName : null;
			$myConfig->logLevel = property_exists($config, 'logLevel') ? $config->logLevel : SysLog::NOTICE;
			$myConfig->adminEmail = property_exists($config, 'adminEmail') ? $config->adminEmail : 'Core Tools Group <coretoolsgroup@neustar.biz>';

			$myConfig->databases = property_exists($config, 'databases') ? $config->databases : null;
			$myConfig->servicenow = property_exists($config, 'servicenow') ? $config->servicenow : null;
			$myConfig->ldap = property_exists($config, 'ldap') ? $config->ldap : null;
			$myConfig->activedir = property_exists($config, 'activedir') ? $config->activedir : null;
		}
		else {
			throw new \ErrorException("unknown config data type");
		}

	    $GLOBALS['config'] = $myConfig;
	    return $myConfig;
    }
}

