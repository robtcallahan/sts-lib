<?php
/*******************************************************************************
 *
 * $Id: ADDAO.php 82972 2014-02-05 16:22:58Z rcallaha $
 * $Date: 2014-02-05 11:22:58 -0500 (Wed, 05 Feb 2014) $
 * $Author: rcallaha $
 * $Revision: 82972 $
 * $HeadURL: https://svn.ultradns.net/svn/sts_tools/sts-lib/trunk/STS/AD/ADDAO.php $
 *
 *******************************************************************************
 */

namespace STS\AD;

use STS\Util\Obfuscation;
use STS\Util\SysLog;

class ADDAO 
{
	protected $sysLog;
	protected $logLevel = SysLog::NOTICE;
	protected $config;
    protected $adConfig;

	private $ldapConn;

    public function __construct($config = null)
    {
        if ($config && is_array($config)) {
            // new config method: passing the config into the constructor
            // check for all needed config params

            // appName, logLevel. If missing, assign defaults
            $config['appName']  = array_key_exists('appName', $config) ? $config['appName'] : 'ADDAO';
            $config['logLevel'] = array_key_exists('logLevel', $config) ? $config['logLevel'] : SysLog::NOTICE;

            // check for active directory details
            if (!array_key_exists('activedir', $config) || !$config['activedir']) throw new \ErrorException("activedir not defined in config");

            // check for activedir connection credentials
            if (!array_key_exists('server', $config['activedir'])) throw new \ErrorException("server not defined in config['activedir']");
            if (!array_key_exists('port', $config['activedir'])) throw new \ErrorException("port not defined in config['activedir']");
            if (!array_key_exists('binduser', $config['activedir'])) throw new \ErrorException("binduser not defined in config['activedir']");
            if (!array_key_exists('password', $config['activedir'])) throw new \ErrorException("password not defined in config['activedir']");

            $this->config = $config;
            $this->adConfig = $config['activedir'];
        } else {
            $config = array();

            // old method of $GLOBALS or a local config file
            if (array_key_exists('config', $GLOBALS)) {
                $configOld = $GLOBALS['config'];
            } else {
                if (is_dir(__DIR__ . "/config") && is_file(__DIR__ . "/config/config.php")) {
                    // local config file
                    $configOld = require(__DIR__ . "/config/config.php");
                } else if (is_dir("config") && is_file("config/config.php")) {
                    // config file in STS\CMDB\config directory
                    $configOld = require("config/config.php");
                } else {
                    throw new \ErrorException("Could not find config file");
                }
            }

            // check for all needed config params
            if (is_object($configOld)) {
                // appName & useUserCredentials. If missing, assign defaults
                $config['appName']  = property_exists($configOld, 'appName') ? $configOld->appName : 'ADDAO';
                $config['logLevel'] = property_exists($configOld, 'logLevel') ? $configOld->logLevel : SysLog::NOTICE;

                if (!property_exists($configOld, 'activedir')) throw new \ErrorException("activedir not defined in config");
                if (!property_exists($configOld->activedir, 'server')) throw new \ErrorException("server not defined in config->activedir");
                if (!property_exists($configOld->activedir, 'port')) throw new \ErrorException("port not defined in config->activedir");
                if (!property_exists($configOld->activedir, 'binduser')) throw new \ErrorException("binduser not defined in config->activedir");
                if (!property_exists($configOld->activedir, 'password')) throw new \ErrorException("password not defined in config->activedir");

                $config['activedir'] = array(
                    'server'   => $configOld->activedir->server,
                    'port' => $configOld->activedir->port,
                    'binduser' => $configOld->activedir->binduser,
                    'password' => $configOld->activedir->password
                );
                $this->config = $config;
                $this->adConfig = $config['activedir'];
            } else {
                // config is an array
                $config['appName']  = array_key_exists('appName', $configOld) ? $configOld['appName'] : 'ADDAO';
                $config['logLevel'] = array_key_exists('logLevel', $configOld) ? $configOld['logLevel'] : SysLog::NOTICE;

                if (!array_key_exists('activedir', $configOld)) throw new \ErrorException("activedir not defined in config");
                if (!array_key_exists('server', $configOld['activedir'])) throw new \ErrorException("server not defined in config['activedir']");
                if (!array_key_exists('port', $configOld['activedir'])) throw new \ErrorException("port not defined in config['activedir']");
                if (!array_key_exists('binduser', $configOld['activedir'])) throw new \ErrorException("binduser not defined in config['activedir']");
                if (!array_key_exists('password', $configOld['activedir'])) throw new \ErrorException("password not defined in config['activedir']");

                $config['activedir'] = array(
                    'server'   => $configOld['activedir']['server'],
                    'port' => $configOld['activedir']['port'],
                    'binduser' => $configOld['activedir']['binduser'],
                    'password' => $configOld['activedir']['password']
                );
                $this->config = $config;
                $this->adConfig = $config['activedir'];
            }
        }

	    // Set up SysLog
	    $this->sysLog   = SysLog::singleton($config['appName']);
	    $this->logLevel = $config['logLevel'];
	    $this->sysLog->debug();

        $this->ldapConn = null;
    }
    
    public function connect()
    {
	    $this->sysLog->debug();

        $this->ldapConn = ldap_connect(
        	$this->adConfig['server'],
        	$this->adConfig['port']
        	);

        ldap_set_option($this->ldapConn, LDAP_OPT_PROTOCOL_VERSION, 3);
        ldap_set_option($this->ldapConn, LDAP_OPT_REFERRALS, 0);
        ldap_set_option($this->ldapConn, LDAP_OPT_SIZELIMIT, 1500);
                                                                                                  
        $crypt = new Obfuscation();

        ldap_bind(
        	$this->ldapConn,
	        $this->adConfig['binduser'],
        	$crypt->decrypt($this->adConfig['password']));
    }

	public function getRecord($baseDn, $filter)
	{
		$this->sysLog->debug();
		if (!$this->ldapConn) $this->connect();

		$result = ldap_search(
			$this->ldapConn,
			$baseDn,
			$filter
		);
		$count = ldap_count_entries($this->ldapConn, $result);
		return $this->readResult($result, $count);
	}

	private function readResult($result, $count)
    {
	    $this->sysLog->debug();
        $results = $this->readResults($result, $count);
        if (count($results) == 1)
        {
            return $results[0];
        }
        elseif (count($results) == 0)
        {
            return null;
        }
        else
        {
            return $results;
        }
    }

	private function readResults($result, $count)
    {
	    $this->sysLog->debug();
        $results = array();
        if ($count > 0)
        {
        	$entry = ldap_first_entry($this->ldapConn, $result);
            if ($entry === false)
            {
                throw new \ErrorException("ldap_first_entry failed: " . ldap_error($this->ldapConn));
            }
        	while($entry)
        	{
        		$attributes = ldap_get_attributes($this->ldapConn, $entry);
                if ($attributes === false)
                {
                    throw new \ErrorException("ldap_get_attributes() failed: " . ldap_error($this->ldapConn));
                }
        		$obj = (object) array();
        		foreach($attributes as $k => $v)
        		{
			        if (preg_match("/\d+/", $k)) continue;
                    $obj->$k = $attributes[$k][0];
        		}
                $results[] = $obj;
        		$entry = ldap_next_entry($this->ldapConn, $entry);
        	}
        }
        $ret = ldap_free_result($result);
        if (!$ret)
        {
            throw new \ErrorException("ldap_free_result() failed: " . ldap_error($this->ldapConn));
        }
        return $results;
    }


	public function getRecords($baseDn, $filter)
    {
	    $this->sysLog->debug();
        if (!$this->ldapConn) $this->connect();
        
        $res = ldap_search($this->ldapConn,	$baseDn, $filter);

	    $entries = array();
	    for ($entryId = ldap_first_entry($this->ldapConn, $res); $entryId != false; $entryId = ldap_next_entry($this->ldapConn, $entryId))
	    {
		    $entries[] = $entryId;
	    }
	    return $entries;
    }

	public function getValue($entryId, $attr)
	{
		try
		{
			$values = ldap_get_values($this->ldapConn, $entryId, $attr);
			return $values[0];
		}
		catch (\Exception $e)
		{
			return "";
		}
	}

    public function close()
    {
	    $this->sysLog->debug();
        ldap_unbind($this->ldapConn);
        $this->ldapConn = 0;
    }    
}
