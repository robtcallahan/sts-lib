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

class ObfuscationTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \STS\Util\Obfuscation
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new \STS\Util\Obfuscation;
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    /**
     * @covers STS\Util\Obfuscation::encrypt
     */
    public function testEncrypt()
    {
        $this->assertEquals('U$u', $this->object->encrypt("xxx"));
    }

    /**
     * @covers STS\Util\Obfuscation::decrypt
     */
    public function testDecrypt()
    {
        $this->assertEquals('xxx', $this->object->decrypt('U$u'));
    }

    /**
     * @covers STS\Util\Obfuscation::setAdjustment
     * @covers STS\Util\Obfuscation::getAdjustment
     */
    public function testSetGetAdjustment()
    {
        $this->object->setAdjustment(25.3);
        $this->assertEquals(25.3, $this->object->getAdjustment());
    }

    /**
     * @covers STS\Util\Obfuscation::setModulus
     * @covers STS\Util\Obfuscation::getModulus
     */
    public function testSetGetModulus()
    {
        $this->object->setModulus(3);
        $this->assertEquals(3, $this->object->getModulus());
    }
}
