<?php
/*******************************************************************************
 *
 * $Id: CMDBDAO.php 82447 2014-01-03 16:00:17Z rcallaha $
 * $Date: 2014-01-03 11:00:17 -0500 (Fri, 03 Jan 2014) $
 * $Author: rcallaha $
 * $Revision: 82447 $
 * $HeadURL: https://svn.ultradns.net/svn/sts_tools/sts-lib/trunk/STS/CMDB/CMDBDAO.php $
 *
 *******************************************************************************
 */

namespace STS\CMDB;

use STS\Util\Curl;
use STS\Util\Obfuscation;
use STS\Util\SysLog;

/**
 * This is the main interface to ServiceNow
 *
 * The class can be used by itself, but it is extended into each of the CMDB*Table classes
 * using the singleton() method so that only one instance is created.
 *
 */
class CMDBDAO
{
    const EMPTY_QUERY = 0;
    const RETURN_EMPTY = 1;
    const JSON_INVALID = 2;
    const RETURN_ERROR = 3;
    const RETURN_UNACCEPTABLE = 4;
    const INSUFFICIENT_RIGHTS = 5;
    const MULTIPLE_ENTRIES = 69;

    /**
     * @var string $site defines the ServiceNow site abbrev. One of "prod", "test", or "dev" as defined in the config file
     */
    protected $site;

    /**
     * @var mixed $config
     */
    protected $config;
    /**
     * @var mixed $snConfig ;
     */
    protected $snConfig;

    /**
     * If true, determine the user credentials from the PHP $_SERVER variables PHP_AUTH_USER and PHP_AUTH_PW
     * @var bool
     */
    protected $useUserCredentials = false;

    /**
     * @var string
     */
    protected $username = "";

    /**
     * @var string
     */
    protected $password = "";

    /**
     * @var string
     */
    protected $baseURL;
    /**
     * @var string
     */
    protected $fullUrl;
    /**
     * @var string
     */
    protected $query;
    /**
     * @var string
     */
    protected $encodedQuery;
    /**
     * @var
     */
    protected $json;

    /** @var $curl Curl */
    protected $curl;
    /**
     * @var bool
     */
    protected $printResult = false;
    /**
     * @var bool
     */
    protected $curlVerbose = false;

    /**
     * @var \STS\Util\SysLog
     */
    protected $sysLog;
    /**
     * @var int
     */
    protected $logLevel = SysLog::NOTICE;

    /**
     * @var null
     */
    private static $instance = null;

    /** @var string $_lastQuery cache the previous query */
    private $_lastQuery;

    /** @var array $queryHistory holds a history of queries. Makes testing create/update/delete possible */
    private $queryHistory = array();

    /** @var array $jsonHistory holds a history of queries. Makes testing create/update/delete possible */
    private $jsonHistory = array();

    /**
     * @param $config
     * @throws \ErrorException Could not find config file
     */
    public function __construct($config = null) {
        if ($config && is_array($config)) {
            // new config method: passing the config into the constructor
            // check for all needed config params

            // appName, logLevel & useUserCredentials. If missing, assign defaults
            $config['appName']            = array_key_exists('appName', $config) ? $config['appName'] : 'hpsim';
            $config['logLevel']           = array_key_exists('logLevel', $config) ? $config['logLevel'] : SysLog::NOTICE;
            $config['useUserCredentials'] = array_key_exists('useUserCredentials', $config) ? $config['useUserCredentials'] : false;

            // check for service now details
            if (!array_key_exists('servicenow', $config) || !$config['servicenow']) throw new \ErrorException("servicenow not defined in config");
            if (!array_key_exists('site', $config['servicenow']) || !$config['servicenow']['site']) throw new \ErrorException("servicenow site not defined in config['servicenow']");
            $site = $config['servicenow']['site'];

            // check for service now connection credentials
            if (!array_key_exists($site, $config['servicenow'])) throw new \ErrorException("site config not defined in config['servicenow']");
            if (!array_key_exists('server', $config['servicenow'][$site])) throw new \ErrorException("server not defined in config['servicenow'][{$site}]");
            if (!array_key_exists('protocol', $config['servicenow'][$site])) throw new \ErrorException("protocol not defined in config['servicenow'][{$site}]");
            if (!array_key_exists('username', $config['servicenow'][$site])) throw new \ErrorException("username not defined in config['servicenow'][{$site}]");
            if (!array_key_exists('password', $config['servicenow'][$site])) throw new \ErrorException("password not defined in config['servicenow'][{$site}]");

            $this->config   = $config;
            $this->snConfig = $config['servicenow'][$site];
        } else {
            // check useUserCredentials
            $useUserCredentials = $config && is_bool($config) ? $config : false;
            $config             = array();

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
                $config['appName']            = property_exists($configOld, 'appName') ? $configOld->appName : 'CMDBDAO';
                $config['logLevel']           = property_exists($configOld, 'logLevel') ? $configOld->logLevel : SysLog::NOTICE;
                $config['useUserCredentials'] = property_exists($configOld, 'useUserCredentials') ? $configOld->useUserCredentials : $useUserCredentials;

                if (!property_exists($configOld, 'servicenow')) throw new \ErrorException("servicenow not defined in config");
                if (!property_exists($configOld->servicenow, 'site')) throw new \ErrorException("servicenow site not defined in config->servicenow");
                $site = $configOld->servicenow->site;

                if (!property_exists($configOld->servicenow->$site, 'server')) throw new \ErrorException("server not defined in config->servicenow->{$site}");
                if (!property_exists($configOld->servicenow->$site, 'protocol')) throw new \ErrorException("protocol not defined in config->servicenow->{$site}");
                if (!property_exists($configOld->servicenow->$site, 'username')) throw new \ErrorException("username not defined in config->servicenow->{$site}");
                if (!property_exists($configOld->servicenow->$site, 'password')) throw new \ErrorException("password not defined in config->servicenow->{$site}");

                $config['servicenow'] = array(
                    $site => array(
                        'server'   => $configOld->servicenow->$site->server,
                        'protocol' => $configOld->servicenow->$site->protocol,
                        'username' => $configOld->servicenow->$site->username,
                        'password' => $configOld->servicenow->$site->password
                    )
                );
                $this->config         = $config;
                $this->snConfig       = $config['servicenow'][$site];
            } else {
                // config is an array
                $config['appName']            = array_key_exists('appName', $configOld) ? $configOld['appName'] : 'CMDBDAO';
                $config['logLevel']           = array_key_exists('logLevel', $configOld) ? $configOld['logLevel'] : SysLog::NOTICE;
                $config['useUserCredentials'] = array_key_exists('useUserCredentials', $configOld) ? $configOld['useUserCredentials'] : $useUserCredentials;

                if (!array_key_exists('servicenow', $configOld)) throw new \ErrorException("servicenow not defined in config");
                if (!array_key_exists('site', $configOld['servicenow'])) throw new \ErrorException("servicenow site not defined in config['servicenow");
                $site = $configOld['servicenow']['site'];

                if (!array_key_exists('server', $configOld['servicenow'][$site])) throw new \ErrorException("server not defined in config['servicenow'][{$site}]");
                if (!array_key_exists('protocol', $configOld['servicenow'][$site])) throw new \ErrorException("protocol not defined in config['servicenow'][{$site}]");
                if (!array_key_exists('username', $configOld['servicenow'][$site])) throw new \ErrorException("username not defined in config['servicenow'][{$site}]");
                if (!array_key_exists('password', $configOld['servicenow'][$site])) throw new \ErrorException("password not defined in config['servicenow'][{$site}]");

                $config['servicenow'] = array(
                    $site => array(
                        'server'   => $configOld['servicenow'][$site]['server'],
                        'protocol' => $configOld['servicenow'][$site]['protocol'],
                        'username' => $configOld['servicenow'][$site]['username'],
                        'password' => $configOld['servicenow'][$site]['password']
                    )
                );
                $this->config         = $config;
                $this->snConfig       = $config['servicenow'][$site];
            }
        }
        $this->site = $site;

        // Set up SysLog
        $this->sysLog   = SysLog::singleton($config['appName']);
        $this->logLevel = $config['logLevel'];
        $this->sysLog->debug();

        $this->useUserCredentials = $config['useUserCredentials'];
        $this->setSite($this->snConfig);
    }

    /**
     * Returns a singleton of the CMDBDAO instance.
     *
     * @param $config
     * @return CMDBDAO
     */
    public static function singleton($config) {
        if (self::$instance == null) {
            self::$instance = new CMDBDAO($config);
        }
        return self::$instance;
    }

    /**
     * @return mixed
     */
    public function getSite() {
        return $this->site;
    }

    /**
     * @param mixed $snConfig
     * @throws \ErrorException Flag 'useUserCredentials' set to true but username cannot be determined
     * @throws \ErrorException Flag 'useUserCredentials' set to true but password cannot be determined
     */
    public function setSite($snConfig) {
        $this->sysLog->debug("SN Server=" . $snConfig['server']);

        if ($this->useUserCredentials) {
            if ($this->username == "" && !array_key_exists("PHP_AUTH_USER", $_SERVER)) {
                throw new \ErrorException("Flag 'useUserCredentials' set to true but username cannot be determined");
            } else if ($this->username == "" && array_key_exists("PHP_AUTH_USER", $_SERVER)) {
                $this->username = $_SERVER['PHP_AUTH_USER'];
            }

            if ($this->password == "" && !array_key_exists("PHP_AUTH_PW", $_SERVER)) {
                throw new \ErrorException("Flag 'useUserCredentials' set to true but password cannot be determined");
            } else if ($this->password == "" && array_key_exists("PHP_AUTH_PW", $_SERVER)) {
                $this->password = $_SERVER['PHP_AUTH_PW'];
            }
        } else if ($this->username == "" && $this->password == "") {
            $crypt          = new Obfuscation();
            $this->username = $snConfig['username'];
            $this->password = $crypt->decrypt($snConfig['password']);
            unset($crypt);
        }

        #error_log("CMDBDAO::setSite(): username " . $this->username);
        $this->baseURL = "{$snConfig['protocol']}://{$snConfig['server']}/";

        $this->curlInit();
    }

    /**
     * Instantiate curl using its singleton method
     */
    private function curlInit() {
        #error_log("CMDBDAO::curlInit(): username " . $this->username);
        $this->curl = new Curl();
        $this->curl->setUsernamePassword($this->username, $this->password);
    }

    /**
     * @param        $table
     * @param string $query
     * @param string $format
     * @return mixed
     * @throws \ErrorException
     */
    public function getRecord($table, $query = "", $format = "JSON") {
        $this->sysLog->debug();
        $fullQuery = $this->baseURL . $table . ".do?" . $format . "&displayvalue=all&sysparm_action=getRecords";
        if ($query) {
            $fullQuery = $fullQuery . "&sysparm_query=" . rawurlencode($query);
        }

        $json = $this->curlExec($fullQuery);

        if (!is_object($json) || !property_exists($json, 'records')) {
            throw new \ErrorException("Unacceptable return from ServiceNow: " . print_r($json, true), self::RETURN_UNACCEPTABLE);
        }

        if (count($json->records) > 1) {
            throw new \ErrorException("More than one record returned", self::MULTIPLE_ENTRIES);
        }

        if (array_key_exists(0, $json->records)) {
            return $json->records[0];
        } else {
            return array();
        }
    }

    /**
     * @param        $table
     * @param string $query
     * @param string $format
     * @return mixed
     * @throws \ErrorException
     */
    public function getRecords($table, $query = "", $format = "JSON") {
        $this->sysLog->debug();
        $fullQuery = $this->baseURL . $table . ".do?" . $format . "&displayvalue=all&sysparm_action=getRecords";
        if ($query) {
            $fullQuery = $fullQuery . "&sysparm_query=" . rawurlencode($query);
        }

        $json = $this->curlExec($fullQuery);

        if (!is_object($json) || !property_exists($json, 'records')) {
            throw new \ErrorException("Unacceptable return from ServiceNow: " . print_r($json, true));
        }

        return $json->records;
    }

    /**
     * @param $table
     * @param $json
     * @return mixed|object
     */
    public function createCI($table, $json) {
        $this->sysLog->debug();
        $this->fullUrl = $this->baseURL . $table . ".do?JSON" . "&sysparm_action=insert";
        return $this->curlExec($this->fullUrl, $json);
    }

    /**
     * {"records":[{"short_description":"this was inserted with python using JSON 1", "impact":"1", "caller_id":"Fred Luddy"},
     * {"short_description":"this was inserted with python using JSON 2", "impact":"1", "caller_id":"Fred Luddy"}]}
     **/
    public function createMultipleCIs($table, $json) {
        $this->sysLog->debug();
        $this->fullUrl = $this->baseURL . $table . ".do?JSON" . "&sysparm_action=insertMultiple";
        return $this->curlExec($this->fullUrl, $json);
    }

    /**
     * @param $table
     * @param $sysId
     * @param $json
     * @return mixed|object
     */
    public function updateCI($table, $sysId, $json) {
        $this->sysLog->debug();
        $this->fullUrl = $this->baseURL . $table . ".do?JSON" . "&sysparm_action=update&sysparm_query=sys_id={$sysId}";
        return $this->curlExec($this->fullUrl, $json);
    }

    /**
     * @param $table
     * @param $sysId
     * @return mixed|object
     */
    public function deleteCI($table, $sysId) {
        $this->sysLog->debug();
        $this->fullUrl = $this->baseURL . $table . ".do?JSON" . "&sysparm_action=deleteRecord";
        $json          = '{"sysparm_sys_id":"' . $sysId . '"}';
        return $this->curlExec($this->fullUrl, $json);
    }

    /**
     * @param $table
     * @param $sysparmQuery
     * @return mixed|object
     */
    public function deleteMultipleCIs($table, $sysparmQuery) {
        $this->sysLog->debug();
        $this->fullUrl = $this->baseURL . $table . ".do?JSON" . "&sysparm_action=deleteMultiple";
        $json          = '{"sysparm_query":"' . $sysparmQuery . '"}';
        return $this->curlExec($this->fullUrl, $json);
    }

    /**
     * @param        $query
     * @param string $json
     * @return mixed|object
     * @throws \ErrorException
     */
    public function curlExec($query, $json = "") {
        $this->sysLog->debug("query=" . $query);
        if ($json) $this->sysLog->debug("json=" . $json);

        $this->query = $query;
        $this->json  = $json;

        $this->fullUrl = $query;
        $this->curl->setUrl($query);

        // update the query history
        $this->_lastQuery = $query;
        array_push($this->queryHistory, $query);
        array_push($this->jsonHistory, $json);

        // change to post if $json is defined
        if ($json != "") {
            $this->curl->setType("POST");
            $this->curl->setData($json);
        }


        $this->curl->send();
        $response = $this->curl->getBody();

        if ($this->printResult) print "CMDBDAO::curlExec response=" . print_r($response, true) . "\n";

        if ($response == "") {
            throw new \ErrorException("Empty return value from ServiceNow", self::RETURN_EMPTY);
        }

        try {
            $json = json_decode($response);
        } catch (\Exception $e) {
            throw new \ErrorException("JSON decode failed on the ServiceNow response. Error: {$e->getMessage()} response: " . print_r($response, true), self::JSON_INVALID);
        }

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \ErrorException("JSON decode failed on the ServiceNow response. Response={$response}", self::JSON_INVALID);
        }

        if ($json != "" && is_object($json) && property_exists($json, 'error')) {
            if (preg_match("/Insufficient rights/", $json->error)) {
                throw new \ErrorException($json->error, self::INSUFFICIENT_RIGHTS);
            } else {
                throw new \ErrorException("Service Now Error: {$json->error}", self::RETURN_ERROR);
            }
        }

        return $json;
    }

    /**
     * NOTE: The three following methods have been added to support non-JSON querying of CMDB. Driving use case is
     * LDAP to CMDB sync. Not all fields are needed for this task (name only, really) and returning full JSON records
     * for every host is roughly 1.2GB, and converting to objects increases the data overhead. PHP is currently
     * configured with a script suze limit of 400MB, and while this could be upped, simply using CSV API instead of JSON
     * yields a smaller field set for date return, and it comes back as 989KB in less than 10 seconds.
     */

    /**
     * A utility method to build ServiceNow queries.
     * @param String $table the table against which to query
     * @param String $format format / API to query for results
     * @param String $query the query itself
     * @return string
     */
    public function buildQuery($table, $format, $query) {

        // URI encode our query and build the full request
        $this->encodedQuery = rawurlencode($query);
        $request            = "{$this->baseURL}{$table}.do?{$format}&sysparm_query={$this->encodedQuery}";

        // cache our last query
        return $request;

    } // buildQuery()


    /**
     * A utility method to return the last query executed by CMDBDAO.
     * @return String
     */
    public function lastQuery() {
        return $this->_lastQuery;
    } // doLastQuery()


    /**
     * A utility method to execute queries against ServiceNow's CMDB. See also $this->curlExec()
     * @param string $query
     * @return string
     */
    public function doQuery($query = "") {

        // log our query, cache our query
        $this->sysLog->debug("query=" . $query);
        $this->fullUrl = $query;

        $this->_lastQuery = $query;
        array_push($this->queryHistory, $query);

        $this->curl->setUrl($query);

        $this->curl->send();
        $response = $this->curl->getBody();

        // log our response and return it
        $this->sysLog->debug("response=" . $response);
        return $response;

    } // doQuery()

    /**
     * A convenience method for re-running what is cached in _lastQuery
     * @return mixed
     */
    public function doLastQuery() {
        return $this->doQuery($this->_lastQuery);
    } // doLastQuery()

    /**
     * @param boolean $printResult
     * @return $this
     */
    public function setPrintResult($printResult) {
        $this->printResult = $printResult;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getPrintResult() {
        return $this->printResult;
    }

    /**
     * @param mixed $logLevel
     * @return $this
     */
    public function setLogLevel($logLevel) {
        $this->logLevel = $logLevel;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLogLevel() {
        return $this->logLevel;
    }

    /**
     * @param \STS\Util\SysLog $sysLog
     * @return $this
     */
    public function setSysLog($sysLog) {
        $this->sysLog = $sysLog;
        return $this;
    }

    /**
     * @return \STS\Util\SysLog
     */
    public function getSysLog() {
        return $this->sysLog;
    }

    /**
     * @param string $baseURL
     * @return $this
     */
    public function setBaseURL($baseURL) {
        $this->baseURL = $baseURL;
        return $this;
    }

    /**
     * @return string
     */
    public function getBaseURL() {
        return $this->baseURL;
    }

    /**
     * @param boolean $curlVerbose
     * @return $this
     */
    public function setCurlVerbose($curlVerbose) {
        $this->curlVerbose = $curlVerbose;
        $this->curl->setVerbose(true);
        return $this;
    }

    /**
     * @return boolean
     */
    public function getCurlVerbose() {
        return $this->curlVerbose;
    }

    /**
     * @param mixed $json
     * @return $this
     */
    public function setJson($json) {
        $this->json = $json;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getJson() {
        return $this->json;
    }

    /**
     * @param mixed $query
     * @return $this
     */
    public function setQuery($query) {
        $this->query = $query;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getQuery() {
        return $this->query;
    }

    /**
     * @param $username
     * @param $password
     * @return $this
     */
    public function setUsernamePassword($username, $password) {
        $this->username = $username;
        $this->password = $password;
        $this->curl     = $this->curl->setUsernamePassword($username, $password);
        return $this;
    }

    /**
     * @return string
     */
    public function getPassword() {
        return $this->password;
    }

    /**
     * @return string
     */
    public function getUsername() {
        return $this->username;
    }

    /**
     * @param mixed $encodedQuery
     * @return $this
     */
    public function setEncodedQuery($encodedQuery) {
        $this->encodedQuery = $encodedQuery;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEncodedQuery() {
        return $this->encodedQuery;
    }

    /**
     * @return \STS\Util\Curl
     */
    public function getCurl() {
        return $this->curl;
    }

    /**
     * @return mixed
     */
    public function getFullUrl() {
        return $this->fullUrl;
    }

    /**
     * @param bool $useUserCredentials
     * @return $this
     */
    public function setUseUserCredentials($useUserCredentials) {
        $this->useUserCredentials = $useUserCredentials;
        return $this;
    }

    /**
     * @return bool
     */
    public function getUseUserCredentials() {
        return $this->useUserCredentials;
    }

    /**
     * @param int $numQueriesBack
     * @return string
     */
    public function getJsonHistory($numQueriesBack = 1) {
        $topOfStack = count($this->jsonHistory) - 1;
        return $this->jsonHistory[$topOfStack - ($numQueriesBack - 1)];
    }

    /**
     * @param int $numQueriesBack
     * @return string
     */
    public function getQueryHistory($numQueriesBack = 1) {
        $topOfStack = count($this->jsonHistory) - 1;
        return $this->queryHistory[$topOfStack - ($numQueriesBack - 1)];
    }

} // class CMDBDAO
