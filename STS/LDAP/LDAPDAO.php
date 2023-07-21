<?php
/*******************************************************************************
 *
 * $Id: LDAPDAO.php 76358 2013-06-27 02:13:37Z rcallaha $
 * $Date: 2013-06-26 22:13:37 -0400 (Wed, 26 Jun 2013) $
 * $Author: rcallaha $
 * $Revision: 76358 $
 * $HeadURL: https://svn.ultradns.net/svn/sts_tools/sts-lib/trunk/STS/LDAP/LDAPDAO.php $
 *
 *******************************************************************************
 */

namespace STS\LDAP;

use STS\Util\SysLog;
use STS\Util\Obfuscation;

class LDAPDAO
{
    protected $config;
    protected $ldapConfig;

    protected $ldapConn;

    protected $sysLog;
    protected $logLevel = SysLog::NOTICE;

    public function __construct($config = null)
    {
        if ($config && is_array($config)) {
            // new config method: passing the config into the constructor
            // check for all needed config params
            // appName and logLevel. If missing, assign defaults
            $config['appName']  = array_key_exists('appName', $config) ? $config['appName'] : 'hpsim';
            $config['logLevel'] = array_key_exists('logLevel', $config) ? $config['logLevel'] : SysLog::NOTICE;

            // check for ldap details
            if (!array_key_exists('ldap', $config) || !$config['ldap']) throw new \ErrorException("ldap not defined in config");
            if (!array_key_exists('site', $config['ldap']) || !$config['ldap']['site']) throw new \ErrorException("ldap site not defined in config['ldap']");
            $site = $config['ldap']['site'];

            // check for ldap connection credentials
            if (!array_key_exists($site, $config['ldap'])) throw new \ErrorException("site config not defined in config['ldap']");
            if (!array_key_exists('host', $config['ldap'][$site])) throw new \ErrorException("host not defined in config['ldap'][{$site}]");
            if (!array_key_exists('port', $config['ldap'][$site])) throw new \ErrorException("port not defined in config['ldap'][{$site}]");
            if (!array_key_exists('binduser', $config['ldap'][$site])) throw new \ErrorException("binduser not defined in config['ldap'][{$site}]");
            if (!array_key_exists('password', $config['ldap'][$site])) throw new \ErrorException("password not defined in config['ldap'][{$site}]");
            if (!array_key_exists('accountDomainName', $config['ldap'][$site])) throw new \ErrorException("accountDomainName not defined in config['ldap'][{$site}]");

            $this->config     = $config;
            $this->ldapConfig = $config['ldap'][$site];
        } else {
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
                // appName, logLevel & useUserCredentials. If missing, assign defaults
                $config['appName']  = property_exists($configOld, 'appName') ? $configOld->appName : 'LDAPDAO';
                $config['logLevel'] = property_exists($configOld, 'logLevel') ? $configOld->logLevel : SysLog::NOTICE;

                if (!property_exists($configOld, 'ldap')) throw new \ErrorException("ldap not defined in config");
                if (!property_exists($configOld->ldap, 'site')) throw new \ErrorException("ldap site not defined in config->ldap");
                $site = $configOld->ldap->site;

                if (!property_exists($configOld->ldap->$site, 'host')) throw new \ErrorException("host not defined in config->ldap->{$site}");
                if (!property_exists($configOld->ldap->$site, 'port')) throw new \ErrorException("port not defined in config->ldap->{$site}");
                if (!property_exists($configOld->ldap->$site, 'binduser')) throw new \ErrorException("binduser not defined in config->ldap->{$site}");
                if (!property_exists($configOld->ldap->$site, 'password')) throw new \ErrorException("password not defined in config->ldap->{$site}");
                if (!property_exists($configOld->ldap->$site, 'accountDomainName')) throw new \ErrorException("accountDomainName not defined in config->ldap->{$site}");

                $config['ldap'] = array(
                    $site => array(
                        'host'              => $configOld->ldap->$site->host,
                        'port'              => $configOld->ldap->$site->port,
                        'binduser'          => $configOld->ldap->$site->binduser,
                        'password'          => $configOld->ldap->$site->password,
                        'accountDomainName' => $configOld->ldap->$site->accountDomainName,
                    )
                );
                $this->config   = $config;
                $this->ldapConfig = $config['ldap'][$site];
            } else {
                // config is an array
                $config['appName']  = array_key_exists('appName', $configOld) ? $configOld['appName'] : 'CMDBDAO';
                $config['logLevel'] = array_key_exists('logLevel', $configOld) ? $configOld['logLevel'] : SysLog::NOTICE;

                if (!array_key_exists('ldap', $configOld)) throw new \ErrorException("ldap not defined in config");
                if (!array_key_exists('site', $configOld['ldap'])) throw new \ErrorException("ldap site not defined in config['ldap");
                $site = $configOld['ldap']['site'];

                if (!array_key_exists('host', $configOld['ldap'][$site])) throw new \ErrorException("host not defined in config['ldap'][{$site}]");
                if (!array_key_exists('port', $configOld['ldap'][$site])) throw new \ErrorException("port not defined in config['ldap'][{$site}]");
                if (!array_key_exists('binduser', $configOld['ldap'][$site])) throw new \ErrorException("binduser not defined in config['ldap'][{$site}]");
                if (!array_key_exists('password', $configOld['ldap'][$site])) throw new \ErrorException("password not defined in config['ldap'][{$site}]");
                if (!array_key_exists('accountDomainName', $configOld['ldap'][$site])) throw new \ErrorException("accountDomainName not defined in config['ldap'][{$site}]");

                $config['ldap'] = array(
                    $site => array(
                        'host'              => $configOld['ldap'][$site]['host'],
                        'protocol'          => $configOld['ldap'][$site]['protocol'],
                        'binduser'          => $configOld['ldap'][$site]['binduser'],
                        'password'          => $configOld['ldap'][$site]['password'],
                        'accountDomainName' => $configOld['ldap'][$site]['accountDomainName'],
                    )
                );
                $this->config   = $config;
                $this->ldapConfig = $config['ldap'][$site];
            }
        }

        $this->site = $site;

        // Set up SysLog
        $this->sysLog   = SysLog::singleton($config['appName']);
        $this->logLevel = $config['logLevel'];
        $this->sysLog->debug();

        $this->ldapConn = null;
        $this->connect();

        // set referrals to true for writes and deletes
        // not necessary if we are pointing to the master
        // and will probably not work if we are pointing to a replicate since it requires
        // additional perms, but we'll add it anyway because it's instructive :-)
        if (!ldap_set_option($this->ldapConn, LDAP_OPT_REFERRALS, true)) {
            throw new \ErrorException("Could not set LDAP Referrals option to true");
        }
    }

    /**
     * @param       $baseDn
     * @param       $filter
     * @param array $attrs
     * @return array
     * @throws \ErrorException
     */
    public function search($baseDn, $filter, array $attrs = array())
    {
        if ($this->logLevel >= SysLog::INFO) $this->sysLog->info("baseDN={$baseDn}, filter={$filter}, attrs = " . implode(",", $attrs));

        $result = ldap_search($this->ldapConn, $baseDn, $filter, $attrs);
        if ($result === false) {
            throw new \ErrorException("ldap_search failed: " . ldap_error($this->ldapConn));
        }
        $count = ldap_count_entries($this->ldapConn, $result);
        $results = $this->readResults($result, $count, $attrs);
        return $results;
    }

    /**
     * @param       $baseDn
     * @param       $filter
     * @param array $attrs
     * @return array|null
     * @throws \ErrorException
     */
    public function get($baseDn, $filter, array $attrs)
    {
        if ($this->logLevel >= SysLog::INFO) $this->sysLog->info("baseDN={$baseDn}, filter={$filter}, attrs = " . implode(",", $attrs));

        print "\n[idm-lite] LDAPDAO::get(baseDN={$baseDn}, filter={$filter}, attrs = " . implode(",", $attrs) . "\n";
        $results = ldap_search($this->ldapConn, $baseDn, $filter, $attrs);
        if ($results === false) {
            throw new \ErrorException("ldap_search failed: " . ldap_error($this->ldapConn));
        }
        $count   = ldap_count_entries($this->ldapConn, $results);
        $result = $this->readResults($results, $count, $attrs);
        print "[idm-lite] LDAPDAO::get() returning: result=" . print_r($result, true) . "\n";
        return ($result);
    }

    /**
     * @param $baseDn
     * @param $filter
     * @param array $attrs
     * @return array
     * @throws \ErrorException
     */
    public function getRecords($baseDn, $filter, array $attrs = array())
    {
        if ($this->logLevel >= SysLog::INFO) $this->sysLog->info("baseDN={$baseDn}, filter={$filter}, attrs = " . implode(",", $attrs));

        $result = ldap_search($this->ldapConn, $baseDn, $filter, $attrs);
        if ($result === false) {
            throw new \ErrorException("ldap_search failed: " . ldap_error($this->ldapConn));
        }
        $count = ldap_count_entries($this->ldapConn, $result);
        return $this->readResults($result, $count, $attrs);
    }

    /**
     * @param $dn
     * @param $info
     * @throws \ErrorException
     */
    public function add($dn, $info)
    {
        $this->sysLog->debug();
        $ret = ldap_add($this->ldapConn, $dn, $info);
        if (!$ret) {
            throw new \ErrorException("ldap_add({$dn}) failed: " . ldap_error($this->ldapConn));
        }
    }

    /**
     * @param $dn
     * @throws \ErrorException
     */
    public function delete($dn)
    {
        $this->sysLog->debug();
        $ret = ldap_delete($this->ldapConn, $dn);
        if (!$ret) {
            throw new \ErrorException("ldap_delete({$dn}) failed: " . ldap_error($this->ldapConn));
        }
    }

    /**
     * @param $dn
     * @param $entry
     * @throws \ErrorException
     */
    public function modAdd($dn, $entry)
    {
        $this->sysLog->debug();
        $ret = ldap_mod_add($this->ldapConn, $dn, $entry);
        if (!$ret) {
		$error = ldap_error($this->ldapConn);	
		if($error != "Type or value exists"){
			$entrystring = print_r($entry, true);
	      		throw new \ErrorException("ldap_mod_add({$dn}) failed: entry=$entrystring error=" . $error);
	      		// throw new \ErrorException("ldap_mod_add({$dn}) failed: " . ldap_error($this->ldapConn));
		}
        }
    }

    /**
     * @param $dn
     * @param $entry
     * @throws \ErrorException
     */
    public function modDelete($dn, $entry)
    {
        $this->sysLog->debug();
        $ret = ldap_mod_del($this->ldapConn, $dn, $entry);
        if (!$ret) {
		$error = ldap_error($this->ldapConn);
                if($error != "No such attribute"){
                      $entrystring = print_r($entry, true);
		    
  		      throw new \ErrorException("ldap_mod_del({$dn}) failed: " . $error);
		}
        }
    }

    /**
     * @param $results
     * @param $count
     * @param $requestedAttrs
     * @return array|null
     */
    private function readResults($results, $count, $requestedAttrs)
    {
        $this->sysLog->debug();
        print "[idm-lite] LDAPDAO::readResults()\n";
        $results = $this->readResult($results, $count, $requestedAttrs);
        if (count($results) == 1) {
            return $results[0];
        } elseif (count($results) == 0) {
            return null;
        } else {
            return $results;
        }
    }

    /**
     * @param $result
     * @param $count
     * @return array
     * @throws \ErrorException
     */
    private function readResult($result, $count)
    {
        $this->sysLog->debug();
        print "[idm-lite] LDAPDAO::readResult()\n";
        $results = array();
        if ($count > 0) {
            $entry = ldap_first_entry($this->ldapConn, $result);
            if ($entry === false) {
                throw new \ErrorException("ldap_first_entry failed: " . ldap_error($this->ldapConn));
            }
            while ($entry) {
                $attributes = ldap_get_attributes($this->ldapConn, $entry);
                if ($attributes === false) {
                    throw new \ErrorException("ldap_get_attributes() failed: " . ldap_error($this->ldapConn));
                }
                $obj = (object)array();
                foreach ($attributes as $k => $v) {
                    // skip integer keys and 'count' key
                    if (preg_match("/\d+/", $k) || $k == 'count') continue;
                    if ($attributes[$k]['count'] > 1) {
                        $ar = array();
                        for ($i = 0; $i < $attributes[$k]['count']; $i++) {
                            $ar[$i] = $attributes[$k][$i];
                        }
                        $obj->$k = $ar;
                    } else {
                        $obj->$k = $attributes[$k][0];
                    }
                }
                print "[idm-lite] LDAPDAO::readResults() obj=" . var_dump($obj) . "\n";
                $results[] = $obj;
                $entry     = ldap_next_entry($this->ldapConn, $entry);
            }
        }
        $ret = ldap_free_result($result);
        if (!$ret) {
            throw new \ErrorException("ldap_free_result() failed: " . ldap_error($this->ldapConn));
        }
        return $results;
    }

    /**
     * @throws \ErrorException
     */
    public function connect()
    {
        $this->sysLog->debug("LDAP Server=" . $this->ldapConfig['host']);
        #ldap_set_option(null, LDAP_OPT_DEBUG_LEVEL, 7);

        $this->ldapConn = ldap_connect($this->ldapConfig['host']);
        if ($this->ldapConn === false) {
            throw new \ErrorException("ldap_connect({$this->ldapConfig['host']}) failed");
        }
        ldap_set_option($this->ldapConn, LDAP_OPT_PROTOCOL_VERSION, 3);
        ldap_set_option($this->ldapConn, LDAP_OPT_REFERRALS, 0);
        ldap_set_option($this->ldapConn, LDAP_OPT_SIZELIMIT, 1500);

        $crypt = new Obfuscation();

        $ret = ldap_bind(
            $this->ldapConn,
            $this->ldapConfig['binduser'],
            $crypt->decrypt($this->ldapConfig['password'])
        );
        if (!$ret) {
            throw new \ErrorException("ldap_bind() failed");
        }
    }

    /**
     * @throws \ErrorException
     */
    public function close()
    {
        $this->sysLog->debug();
        $ret = ldap_unbind($this->ldapConn);
        if (!$ret) {
            throw new \ErrorException("ldap_close() failed");
        }
        $this->ldapConn = null;
    }

    /**
     * @return null
     */
    public function getLdapConn() {
        return $this->ldapConn;
    }

}
