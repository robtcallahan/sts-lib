<?php

/*******************************************************************************
 *
 * $Id: CMDBDAO.php 78804 2013-09-11 17:14:40Z rcallaha $
 * $Date: 2013-09-11 13:14:40 -0400 (Wed, 11 Sep 2013) $
 * $Author: rcallaha $
 * $Revision: 78804 $
 * $HeadURL: https://svn.ultradns.net/svn/sts_tools/sts-lib/trunk/STS/CMDB/CMDBDAO.php $
 *
 *******************************************************************************
 */

/**
 * Stub for the Curl class.
 *
 * Allows testing code that uses cURL without actually
 * making any HTTP connections.
 */
class StubCurl extends STS\Util\Curl {

    private static $instance;
    private static $dataDir;
    private static $returnValues = array();

    /**
     * Constructor.
     */
    public function __construct() {
        parent::__construct();

        self::$dataDir = DATADIR . "/CMDB";

        self::$returnValues = array(
            "urlAssembly" => '{"records":[{"url":"https://neustar.service-now.com/cmdb_ci_server.do?JSON&displayvalue=all&sysparm_action=getRecords"}]}',
            "missingRecordsInJson" => '{"name":"value"}',
            "moreThanOneRecord" => '{"records":[{"name":"value1"},{"name":"value2"}]}',
            "validServerResponse" => file_get_contents(self::$dataDir . "/cmdb_ci_server/JSON/cmdb_ci_server.json"),
            "multipleServers" => file_get_contents(self::$dataDir . "/cmdb_ci_server/JSON/cmdb_ci_servers.json"),

            "stsccprdlpmail01.va.neustar.com" => file_get_contents(self::$dataDir . "/cmdb_ci_server/JSON/stsccprdlpmail01.va.neustar.com"),
        );
    }

    public static function singleton()
    {
        if (self::$instance == null) {
            self::$instance = new StubCurl();
        }
        return self::$instance;
    }

    /**
     * 'Send' the cURL request.
     *
     * Obviously doesn't actually send any requests.
     * Inspect $this->url
     *      if file, load file into $this->body
     *      if format is UNIT_TEST, then use sysparm_query as the key of $returnValues array
     *      else, load as string into $this->body
     *
     * @return StubCurl
     */
    public function send() {
        $table = null;
        $format = null;
        $query = null;
        $file = null;

        if (!$this->url) {
            $this->body = "";
            return $this;
        }
        if ('GET' == $this->type && $this->data) {
            $this->url .= '?' . $this->data;
        }

        if ($this->url == "") {
            $this->body = "";
            return $this;
        }

        // parse out the params of the url
        if (preg_match("/\.service-now\.com\/(\w+)\.do\?([A-Z]+).*(&|%26)sysparm_query(=|%3D)([\w\d%=_\.-]+)/", $this->url, $m)) {
            $table = $m[1];
            $format = $m[2];
            $query = $m[5];

            // construct file name from query parts
            $file = self::$dataDir . "/" . $table . "/" . $format . "/" . $query;
        }

        // if we've successfully parsed out the query parts to construct a file name, check if the file exists and load it
        if ($table && $format && $query && $file && file_exists($file)) {
            $this->body = file_get_contents($file);
        }

        // if $this->url is a file, then return it's contents as the body
        else if (file_exists($this->url))
        {
            $this->body = file_get_contents($this->url);
        }

        // if $this->url contains the string UNIT_TEST for the format, then get the value of sysparm_query
        // to use as the test name to lookup the value to be returned in the $returnValues array
        else if (preg_match("/\?UNIT_TEST/", $this->url) && preg_match("/sysparm_query=(.+)/", $this->url, $m)) {
            $testName = $m[1];
            if (array_key_exists($testName, self::$returnValues)) {
                $this->body = self::$returnValues[$testName];
            }
        }

        // else if this is an insert, insertMultiple, update, delete or deleteMultiple, then return the standard server
        else if (preg_match("/&sysparm_action=(insert|insertMultiple|update|delete|deleteMultiple)/", $this->url)) {
            if (preg_match("/\.service-now\.com\/(\w+)\.do\?([A-Z]+)/", $this->url, $m)) {
                $table = $m[1];
                $format = $m[2];
                $file = self::$dataDir . "/" . $table . "/" . $format . "/" . $table . ".json";
                if ($table && $format && file_exists($file)) {
                    $this->body = file_get_contents($file);
                }
                else {
                    $this->body = self::$returnValues["validServerResponse"];
                }
            }
            else {
                $this->body = self::$returnValues["validServerResponse"];
            }
        }

        // else, just return the contents of $this->url
        else {
            $this->body = $this->url;
        }

        // this is no longer a new cookie session
        $this->newCookieSession = false;


        return $this;
    }
}
