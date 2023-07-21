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
class SysLogTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \STS\Util\SysLog
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new StubSysLog();
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        unset($this->object);
    }

    /**
     * @covers STS\Util\SysLog::__construct
     */
    public function test__construct()
    {
        $sysLog = new \STS\Util\SysLog();
        $this->assertEquals(STS\Util\SysLog::NOTICE, $sysLog->getLogLevel());
        $this->assertEquals("Util\SysLog", $sysLog->getProcessName());
        $this->assertEquals("stsuser", $sysLog->getUser());
    }

    /**
     * @covers STS\Util\SysLog::singleton
     */
    public function testSingleton()
    {
        $sysLog = \STS\Util\SysLog::singleton("unit_test");
        $this->assertInstanceOf("\STS\Util\SysLog", $sysLog);

    }

    /**
     * @covers STS\Util\SysLog::setLogLevel
     * @covers STS\Util\SysLog::getLogLevel
     */
    public function testSetGetLogLevel()
    {
        $this->object->setLogLevel(STS\Util\SysLog::NOTICE);
        $this->assertEquals(STS\Util\SysLog::NOTICE, $this->object->getLogLevel());
    }

    /**
     * @covers STS\Util\SysLog::setProcessName
     * @covers STS\Util\SysLog::getProcessName
     */
    public function testSetGetProcessName()
    {
        $this->object->setProcessName("test");
        $this->assertEquals("test", $this->object->getProcessName());
    }

    /**
     * @covers STS\Util\SysLog::setUser
     * @covers STS\Util\SysLog::getUser
     */
    public function testSetGetUser()
    {
        $this->object->setUser("joeuser");
        $this->assertEquals("joeuser", $this->object->getUser());
    }

    /**
     * @covers STS\Util\SysLog::log
     * @covers STS\Util\SysLog::getCaller
     */
    public function testLog()
    {
        $this->object->setLogLevel(\STS\Util\SysLog::NOTICE);
        $out = $this->object->log(\STS\Util\SysLog::NOTICE, "test message");
        $test = preg_match("/5: Severity=NOTICE; User=stsuser; Function=SysLogTest::testLog; Caller=Util\/SysLogTest.php:\d+; test message/", $out);
        $this->assertTrue($test == 1);
    }

    /**
     * @covers STS\Util\SysLog::debug
     */
    public function testDebug()
    {
        $this->object->setLogLevel(\STS\Util\SysLog::DEBUG);
        $out = $this->object->debug("test message");
        $test = preg_match("/7: Severity=DEBUG; User=stsuser; Function=SysLogTest::testDebug; Caller=Util\/SysLogTest.php:\d+; test message/", $out);
        $this->assertTrue($test == 1);
    }

    /**
     * @covers STS\Util\SysLog::info
     */
    public function testInfo()
    {
        $this->object->setLogLevel(\STS\Util\SysLog::INFO);
        $out = $this->object->info("test message");
        $test = preg_match("/6: Severity=INFO; User=stsuser; Function=SysLogTest::testInfo; Caller=Util\/SysLogTest.php:\d+; test message/", $out);
        $this->assertTrue($test == 1);
    }

    /**
     * @covers STS\Util\SysLog::notice
     */
    public function testNotice()
    {
        $this->object->setLogLevel(\STS\Util\SysLog::NOTICE);
        $out = $this->object->notice("test message");
        $test = preg_match("/5: Severity=NOTICE; User=stsuser; Function=SysLogTest::testNotice; Caller=Util\/SysLogTest.php:\d+; test message/", $out);
        $this->assertTrue($test == 1);
    }

    /**
     * @covers STS\Util\SysLog::warning
     */
    public function testWarning()
    {
        $this->object->setLogLevel(\STS\Util\SysLog::WARNING);
        $out = $this->object->warning("test message");
        $test = preg_match("/4: Severity=WARNING; User=stsuser; Function=SysLogTest::testWarning; Caller=Util\/SysLogTest.php:\d+; test message/", $out);
        $this->assertTrue($test == 1);
    }

    /**
     * @covers STS\Util\SysLog::error
     */
    public function testError()
    {
        $this->object->setLogLevel(\STS\Util\SysLog::ERR);
        $out = $this->object->error("test message");
        $test = preg_match("/3: Severity=ERR; User=stsuser; Function=SysLogTest::testError; Caller=Util\/SysLogTest.php:\d+; test message/", $out);
        $this->assertTrue($test == 1);
    }

    /**
     * @covers STS\Util\SysLog::errorWithException
     */
    public function testErrorWithException()
    {
        $this->object->setLogLevel(\STS\Util\SysLog::ERR);
        $e = new ErrorException("Test Exception");
        $out = $this->object->errorWithException($e, "test message");
        $test = preg_match("/3: Severity=ERR; User=stsuser; File=SysLogTest.php; Line=\d+; Error=Test Exception; test message/", $out);
        $this->assertTrue($test == 1);
    }

    /**
     * @covers STS\Util\SysLog::crit
     */
    public function testCrit()
    {
        $this->object->setLogLevel(\STS\Util\SysLog::CRIT);
        $out = $this->object->crit("test message");
        $test = preg_match("/2: Severity=CRIT; User=stsuser; Function=SysLogTest::testCrit; Caller=Util\/SysLogTest.php:\d+; test message/", $out);
        $this->assertTrue($test == 1);
    }

    /**
     * @covers STS\Util\SysLog::alert
     */
    public function testAlert()
    {
        $this->object->setLogLevel(\STS\Util\SysLog::ALERT);
        $out = $this->object->alert("test message");
        $test = preg_match("/1: Severity=ALERT; User=stsuser; Function=SysLogTest::testAlert; Caller=Util\/SysLogTest.php:\d+; test message/", $out);
        $this->assertTrue($test == 1);
    }

    /**
     * @covers STS\Util\SysLog::emerg
     */
    public function testEmerg()
    {
        $this->object->setLogLevel(\STS\Util\SysLog::EMERG);
        $out = $this->object->emerg("test message");
        $test = preg_match("/0: Severity=EMERG; User=stsuser; Function=SysLogTest::testEmerg; Caller=Util\/SysLogTest.php:\d+; test message/", $out);
        $this->assertTrue($test == 1);
    }

    /**
     * @covers STS\Util\SysLog::logMemUsage
     */
    public function testLogMemUsage()
    {
        $this->object->setLogLevel(\STS\Util\SysLog::INFO);
        $out = $this->object->logMemUsage("scriptName");
        $test = preg_match("/6: Severity=INFO; Type=MemUsage; User=stsuser; Script=scriptName; MemUsage=[\d\.]+ (bytes|KB|MB);/", $out);
        $this->assertTrue($test == 1);
    }
}
