<?php
/*******************************************************************************
 *
 * $Id: CMDBDAOTest.php 79057 2013-09-19 18:12:14Z rcallaha $
 * $Date: 2013-09-19 14:12:14 -0400 (Thu, 19 Sep 2013) $
 * $Author: rcallaha $
 * $Revision: 79057 $
 * $HeadURL: https://svn.ultradns.net/svn/sts_tools/sts-lib/trunk/test/CMDB/CMDBDAOTest.php $
 *
 *******************************************************************************
 */

/**
 * Class CMDBDAOTest
 * @covers \STS\CMDB\CMDBDAO
 */
class CMDBDAOTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var StubCMDBDAO
     */
    protected $cmdb;

    protected static $dataDir;
    protected static $dataFileName;
    protected $dataFileContents;
    protected $baseURL;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        // read the config file and add to $GLOBALS
        $GLOBALS['config'] = require(CONFIGFILE);

	    $this->cmdb = new StubCMDBDAO();

        self::$dataDir = DATADIR . "/CMDB/cmdb_ci_server/JSON";
        self::$dataFileName = self::$dataDir . "/cmdb_ci_server.json";
        $this->dataFileContents = file_get_contents(self::$dataFileName);

        $snConfig = $GLOBALS['config']->servicenow;
        $snSite = $snConfig->site;
        $siteConfig = $snConfig->$snSite;
        $this->baseURL = "{$siteConfig->protocol}://{$siteConfig->server}/";
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
	    unset($this->cmdb);
    }

    public function testConfiguration()
    {
	    // insure the config file exists
	    $configFile = CONFIGFILE;
        $this->assertTrue(file_exists($configFile), "config file not found");

	    $this->assertArrayHasKey('config', $GLOBALS, "GLOBALS does not contain config key");
	    $this->assertObjectHasAttribute('servicenow', $GLOBALS['config'], "config file does not contain servicenow attribute");

		$snConfig = $GLOBALS['config']->servicenow;
		$this->assertObjectHasAttribute('site', $snConfig, 'servicenow config does not contain site attribute');
	    $this->assertAttributeNotEmpty('site', $snConfig, 'servicenow config contains empty site attribute');

	    $snSite = $snConfig->site;
	    $this->assertObjectHasAttribute($snSite, $snConfig, 'servicenow config does not contain config for site ' . $snSite);

	    $snAuth = $snConfig->$snSite;
	    $this->assertObjectHasAttribute('protocol', $snAuth, 'servicenow site config does not contain protocol attribute');
		$this->assertAttributeNotEmpty('protocol', $snAuth, 'servicenow site config contains empty protocol attribute');
	    $this->assertObjectHasAttribute('server', $snAuth, 'servicenow site config does not contain server attribute');
		$this->assertAttributeNotEmpty('server', $snAuth, 'servicenow site config contains empty server attribute');
	    $this->assertObjectHasAttribute('username', $snAuth, 'servicenow site config does not contain username attribute');
		$this->assertAttributeNotEmpty('username', $snAuth, 'servicenow site config contains empty username attribute');
	    $this->assertObjectHasAttribute('password', $snAuth, 'servicenow site config does not contain password attribute');
		$this->assertAttributeNotEmpty('password', $snAuth, 'servicenow site config contains empty password attribute');
    }

    /**
     * @covers STS\CMDB\CMDBDAO::setSite
     * @covers STS\CMDB\CMDBDAO::getSite
     */
    public function testSetSite()
    {
        $snConfig = $GLOBALS['config']->servicenow;
        $snSite = $snConfig->site;
        $siteConfig = $snConfig->$snSite;

        $this->cmdb->setSite($snSite);
        $site = $this->cmdb->getSite();
        $this->assertEquals($snSite, $site);

        // check username
        $this->assertEquals($siteConfig->username, $this->cmdb->getUsername());

        // check password
        $crypt = new STS\Util\Obfuscation();
        $password = $crypt->decrypt($siteConfig->password);
        $this->assertEquals($password, $this->cmdb->getPassword());
        unset($crypt);

        // check base URL
        $this->assertEquals("{$siteConfig->protocol}://{$siteConfig->server}/", $this->cmdb->getBaseURL());

        // check curl user and pwd
        $curl = $this->cmdb->getCurl();
        $this->assertEquals($siteConfig->username, $curl->getUsername());
        $this->assertEquals($password, $curl->getPassword());

        // tests for use of user credentials
        $this->cmdb->setUseUserCredentials(true);
        $this->assertEquals(true, $this->cmdb->getUseUserCredentials());
    }

    public function testWithUserName()
    {
        // test PHP_AUTH_USER
        $this->cmdb->setUseUserCredentials(true);
        $this->cmdb->setUsername("");
        $_SERVER['PHP_AUTH_USER'] = "user";
        $this->cmdb->setSite("prod");
        $this->assertEquals("user", $this->cmdb->getUsername());
    }

    public function testNoUsername()
    {
        // no username
        $this->cmdb->setUseUserCredentials(true);
        $this->cmdb->setUsername("");
        unset($_SERVER['PHP_AUTH_USER']);
        $this->setExpectedException('ErrorException', "Flag 'useUserCredentials' set to true but username cannot be determined");
        $this->cmdb->setSite("prod");
        $this->assertEquals("prod", $this->cmdb->getSite());
    }

    public function testWithPassword()
    {
        // test PHP_AUTH_PW
        $this->cmdb->setUseUserCredentials(true);
        $this->cmdb->setUsername("");
        $_SERVER['PHP_AUTH_USER'] = "user";
        $this->cmdb->setPassword("");
        $_SERVER['PHP_AUTH_PW'] = "changeme";
        $this->cmdb->setSite("prod");
        $this->assertEquals("changeme", $this->cmdb->getPassword());
    }

    public function testNoPassword()
    {
        // no password
        $this->cmdb->setUseUserCredentials(true);
        $this->cmdb->setUsername("");
        $_SERVER['PHP_AUTH_USER'] = "user";
        $this->cmdb->setPassword("");
        $this->assertEquals("", $this->cmdb->getPassword());
        unset($_SERVER['PHP_AUTH_PW']);
        $this->assertArrayNotHasKey('PHP_AUTH_PW', $_SERVER);
        $this->setExpectedException('ErrorException', "Flag 'useUserCredentials' set to true but password cannot be determined");
        $this->cmdb->setSite("prod");
    }

    /**
     * @covers STS\CMDB\CMDBDAO::singleton
     * @covers STS\CMDB\CMDBDAO::__construct
     */
    public function testSingleton()
    {
        $cmdb = \STS\CMDB\CMDBDAO::singleton($useUserCredentials=false);
        $this->assertInstanceOf('\STS\CMDB\CMDBDAO', $cmdb);
        unset($cmdb);
    }

    /**
     * @covers STS\CMDB\CMDBDAO::getConfigDir
     */
    public function testGetConfigDir()
    {
        unset($GLOBALS['config']);
        $cmdb = new \STS\CMDB\CMDBDAO();
        $configDir = $cmdb->getConfigDir();
        $this->assertEquals(1, preg_match("/STS\/CMDB\/config/", $configDir));
        unset($cmdb);
    }

    /**
     * @covers STS\CMDB\CMDBDAO::getConfigFile
     */
    public function testGetConfigFile()
    {
        unset($GLOBALS['config']);
        $cmdb = new \STS\CMDB\CMDBDAO();
        $configFile = $cmdb->getConfigFile();
        $this->assertEquals(1, preg_match("/config.php/", $configFile));
        unset($cmdb);
    }

    /**
     * @covers STS\CMDB\CMDBDAO::getFullUrl
     */
    public function testGetFullUrl()
    {
        $this->cmdb->curlExec('{"key":"xxx"}');
        $fullUrl = $this->cmdb->getFullUrl();
        $this->assertEquals('{"key":"xxx"}', $fullUrl);
    }

    /**
     * @covers STS\CMDB\CMDBDAO::getQueryHistory
     * @covers STS\CMDB\CMDBDAO::getJsonHistory
     */
    public function testHistory()
    {
        $this->cmdb->curlExec('{"key":"xxx"}', 'some_json_string');

        $fullUrl = $this->cmdb->getFullUrl();
        $json = $this->cmdb->getJson();

        $qHistory = $this->cmdb->getQueryHistory();
        $this->assertEquals($fullUrl, $qHistory);
        $jHistory = $this->cmdb->getJsonHistory();
        $this->assertEquals($json, $jHistory);
    }

    /**
     * @covers STS\CMDB\CMDBDAO::getConfig
     */
    public function testGetConfig()
    {
        $config = $this->cmdb->getConfig();
        $this->assertInstanceOf('stdClass', $config);
        $this->assertObjectHasAttribute('servicenow', $config);
    }

    /**
     * @covers STS\CMDB\CMDBDAO::setLogLevel
     * @covers STS\CMDB\CMDBDAO::getLogLevel
     * @covers STS\CMDB\CMDBDAO::setPrintResult
     * @covers STS\CMDB\CMDBDAO::getPrintResult
     * @covers STS\CMDB\CMDBDAO::setBaseURL
     * @covers STS\CMDB\CMDBDAO::getBaseURL
     * @covers STS\CMDB\CMDBDAO::setCurlVerbose
     * @covers STS\CMDB\CMDBDAO::getCurlVerbose
     * @covers STS\CMDB\CMDBDAO::setJson
     * @covers STS\CMDB\CMDBDAO::getJson
     * @covers STS\CMDB\CMDBDAO::setQuery
     * @covers STS\CMDB\CMDBDAO::getQuery
     * @covers STS\CMDB\CMDBDAO::setEncodedQuery
     * @covers STS\CMDB\CMDBDAO::getEncodedQuery
     * @covers STS\CMDB\CMDBDAO::setUsername
     * @covers STS\CMDB\CMDBDAO::getUsername
     * @covers STS\CMDB\CMDBDAO::setPassword
     * @covers STS\CMDB\CMDBDAO::getPassword
     * @covers STS\CMDB\CMDBDAO::setUseUserCredentials
     * @covers STS\CMDB\CMDBDAO::getUseUserCredentials
     * @covers STS\CMDB\CMDBDAO::setSysLog
     * @covers STS\CMDB\CMDBDAO::getSysLog
     * @covers STS\CMDB\CMDBDAO::getCurl
     */
    public function testGettersAndSetters()
    {
        $this->cmdb->setLogLevel(\STS\Util\SysLog::NOTICE);
        $this->assertEquals(\STS\Util\SysLog::NOTICE, $this->cmdb->getLogLevel());

        $this->cmdb->setPrintResult(true);
        $this->assertEquals(true, $this->cmdb->getPrintResult());

        $sysLog = new \STS\Util\SysLog();
        $this->cmdb->setSysLog($sysLog);
        $this->assertEquals($sysLog, $this->cmdb->getSysLog());
        unset($sysLog);

        $this->cmdb->setBaseURL("xxx");
        $this->assertEquals("xxx", $this->cmdb->getBaseURL());

        $this->cmdb->setCurlVerbose(true);
        $this->assertEquals(true, $this->cmdb->getCurlVerbose());

        $this->cmdb->setJson('{"x":"y"}');
        $this->assertEquals('{"x":"y"}', $this->cmdb->getJson());

        $this->cmdb->setQuery("xxx");
        $this->assertEquals("xxx", $this->cmdb->getQuery());

        $this->cmdb->setEncodedQuery("xxx");
        $this->assertEquals("xxx", $this->cmdb->getEncodedQuery());

        $this->cmdb->setPassword("xxx");
        $this->assertEquals("xxx", $this->cmdb->getPassword());

        $this->cmdb->setUsername("xxx");
        $this->assertEquals("xxx", $this->cmdb->getUsername());

        $this->cmdb->setUseUserCredentials(true);
        $this->assertTrue($this->cmdb->getUseUserCredentials());

        $this->assertInstanceOf('\STS\UTIL\Curl', $this->cmdb->getCurl());
    }

    /**
     * @covers STS\CMDB\CMDBDAO::buildQuery
     */
    public function testBuildQuery()
    {
        $query = "sys_class_name!=^install_statusNOT IN117,1501^name=stopcdvvt1.va.neustar.com";
        $encoded = "sys_class_name%21%3D%5Einstall_statusNOT%20IN117%2C1501%5Ename%3Dstopcdvvt1.va.neustar.com";
        $url = $this->cmdb->buildQuery("cmdb_ci_server", "JSON", $query);
        $this->assertEquals($encoded, $this->cmdb->getEncodedQuery(), "buildQuery failed to encode the query string");

        $expected = "{$this->cmdb->getBaseURL()}cmdb_ci_server.do?JSON&sysparm_query={$encoded}";
        $this->assertEquals($expected, $url, "buildQuery failed to correctly build the query");
    }

    /**
     * @covers STS\CMDB\CMDBDAO::doQuery
     */
    public function testDoQuery()
    {
        $response = $this->cmdb->doQuery(self::$dataFileName);
        $this->assertEquals($this->dataFileContents, $response, "doQuery response did not match expected");
    }

    /**
     * @covers STS\CMDB\CMDBDAO::lastQuery
     * @covers STS\CMDB\CMDBDAO::doLastQuery
     */
    public function testDoLastQuery()
    {
        $this->cmdb->curlExec(self::$dataFileName);
        $lastQuery = $this->cmdb->lastQuery();
        $this->assertEquals(self::$dataFileName, $lastQuery);

        $response = $this->cmdb->doLastQuery();
        $this->assertEquals($this->dataFileContents, $response, "doLastQuery response did not match expected");
    }

    /**
	 * @covers STS\CMDB\CMDBDAO::curlExec
	 */
    public function testCurlExecEmptyReturn()
    {
        // test for empty response
        $this->setExpectedException('ErrorException', "Empty return value from ServiceNow");
        $this->cmdb->curlExec("");
    }

    /**
	 * @covers STS\CMDB\CMDBDAO::curlExec
	 */
    public function testCurlExec()
    {
        // test for standard response for a cmdb_ci_server in json format
        $expectedJson = json_decode($this->dataFileContents);
        $json = $this->cmdb->curlExec(self::$dataFileName);
        $this->assertEquals($expectedJson, $json, "curlExec response did not match expected");
    }

    /**
	 * @covers STS\CMDB\CMDBDAO::curlExec
	 */
    public function testJsonDecode()
    {
        // test for json decode problem
        $this->setExpectedException('ErrorException', "JSON decode failed on the ServiceNow response");
        $this->cmdb->curlExec("{l;sdkf}");
    }

    /**
	 * @covers STS\CMDB\CMDBDAO::curlExec
	 */
    public function testInsufficientRights()
    {
        // test for insufficient rights error
        $this->setExpectedException('ErrorException', "Insufficient rights");
        $this->cmdb->curlExec('{"error":"Insufficient rights"}', $json="json");

    }

    /**
	 * @covers STS\CMDB\CMDBDAO::curlExec
	 */
    public function testServiceNowError()
    {
        // test for other error
        $this->setExpectedException('ErrorException', "Service Now Error");
        $this->cmdb->curlExec('{"error":"Something went wrong"}');
    }

    /**
	 * @covers STS\CMDB\CMDBDAO::getRecord
	 */
	public function testGetRecord()
    {
        // getRecord() is being passed 3 values: table, test name and "UNIT TEST"
        // the 3rd param tells StubCurl it's a test and to use the second param as the test name to lookup the value to be returned

        // test for proper url assembly
        $expected = "https://neustar.service-now.com/cmdb_ci_server.do?JSON&displayvalue=all&sysparm_action=getRecords";
        $json = $this->cmdb->getRecord("cmdb_ci_server", 'urlAssembly', 'UNIT_TEST');
        $this->assertEquals($expected, $json->url);

        // check for unacceptable return (json object does no contact a records property)
        $this->setExpectedException('ErrorException', "Unacceptable return from ServiceNow:");
        $this->cmdb->getRecord("cmdb_ci_server", "missingRecordsInJson", "UNIT_TEST");

		// test exception thrown when more than one record is returned
		$this->setExpectedException('ErrorException', 'More than one record returned');
		$this->cmdb->getRecord('cmdb_ci_server', 'moreThanOneRecord", "UNIT_TEST');

		// get a valid record
        $json = $this->cmdb->getRecord('cmdb_ci_server', 'validServerResponse", "UNIT_TEST');

		// insure the result is an object
		$this->assertTrue(is_object($json), "expected object, found " . gettype($json));

		// insure there is a sys_id attribute that is not empty
		$this->assertObjectHasAttribute('sys_id', $json, "expected attribute, sys_id, not found");
		$this->assertTrue($json->sys_id != "" && $json->sys_id != null, "expected attribute, sys_id, to be non null and not empty");

		// insure there is a name attribute that is not empty
		$this->assertObjectHasAttribute('name', $json, 'expected attribute, name, not found');
		$this->assertTrue($json->name != "" && $json->name != null, "expected attribute, name, to be non null and not empty");
	}

	/**
	 * @covers STS\CMDB\CMDBDAO::getRecords
	 */
	public function testGetRecords() {
		// get a set of records
		$results = $this->cmdb->getRecords('cmdb_ci_server', 'multipleServers', 'UNIT_TEST');

		// insure an array is returned
		$this->assertTrue(is_array($results), "expected array, found " . gettype($results));

		// insure that we have more than 1 record
		$this->assertTrue(count($results) > 1, "expected more than one record to be returned, found " . count($results));

		// loop thru the array, insure each is an object and has a sys_id and name field
		for ($i=0; $i<count($results); $i++) {
			$r = $results[$i];
			$this->assertTrue(is_object($r), "expected object, found " . gettype($r));

			$this->assertObjectHasAttribute('sys_id', $r, "expected attribute, sys_id, not found");
			$this->assertTrue($r->sys_id != "" && $r->sys_id != null, "expected attribute, sys_id, to be non null and not empty");

			$this->assertObjectHasAttribute('name', $r, 'expected attribute, name, not found');
			$this->assertTrue($r->name != "" && $r->name != null, "expected attribute, name, to be non null and not empty");
		}
	}

	/**
	 * @covers STS\CMDB\CMDBDAO::create
	 */
	public function testCreate() {
        $json = '{"name":"ducky"}';
        $this->cmdb->create("cmdb_ci_server", $json);

        $this->assertEquals($this->baseURL . "cmdb_ci_server.do?JSON" . "&sysparm_action=insert", $this->cmdb->getFullUrl());
        $this->assertEquals($json, $this->cmdb->getJson());
	}

	/**
	 * @covers STS\CMDB\CMDBDAO::createMultiple
	 */
	public function testCreateMultiple() {
        $json = '{"name":"ducky"}';
        $this->cmdb->createMultiple("cmdb_ci_server", $json);

        $this->assertEquals($this->baseURL . "cmdb_ci_server.do?JSON" . "&sysparm_action=insertMultiple", $this->cmdb->getFullUrl());
        $this->assertEquals($json, $this->cmdb->getJson());
	}

	/**
	 * @covers STS\CMDB\CMDBDAO::update
	 */
	public function testUpdate() {
        $json = '{"name":"ducky"}';
        $this->cmdb->update("cmdb_ci_server", "xxxx", $json);

        $this->assertEquals($this->baseURL . "cmdb_ci_server.do?JSON" . "&sysparm_action=update&sysparm_query=sys_id=xxxx", $this->cmdb->getFullUrl());
        $this->assertEquals($json, $this->cmdb->getJson());
	}

	/**
	 * @covers STS\CMDB\CMDBDAO::delete
	 */
	public function testDelete() {
        $this->cmdb->delete("cmdb_ci_server", "xxxx");
        $this->assertEquals($this->baseURL . "cmdb_ci_server.do?JSON" . "&sysparm_action=deleteRecord", $this->cmdb->getFullUrl());
        $this->assertEquals('{"sysparm_sys_id":"xxxx"}', $this->cmdb->getJson());
	}

	/**
	 * @covers STS\CMDB\CMDBDAO::deleteMultiple
	 */
	public function testDeleteMultiple() {
        $this->cmdb->deleteMultiple("cmdb_ci_server", "nameLIKEstopcd101");
        $this->assertEquals($this->baseURL . "cmdb_ci_server.do?JSON" . "&sysparm_action=deleteMultiple", $this->cmdb->getFullUrl());
        $this->assertEquals('{"sysparm_query":"nameLIKEstopcd101"}', $this->cmdb->getJson());
	}
}

