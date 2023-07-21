<?php
/*******************************************************************************
 *
 * $Id: CMDBSubsystem.php 76979 2013-07-18 19:27:00Z rcallaha $
 * $Date: 2013-07-18 15:27:00 -0400 (Thu, 18 Jul 2013) $
 * $Author: rcallaha $
 * $Revision: 76979 $
 * $HeadURL: https://svn.ultradns.net/svn/sts_tools/sts-lib/trunk/STS/CMDB/CMDBSubsystem.php $
 *
 *******************************************************************************
 */

class CMDBTaskListTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var STS\CMDB\CMDBTaskList
     */
    protected $object;

    /**
     * @var STS\CMDB\CMDBTaskListTable
     */
    protected $objectTable;

    /**
     * @var string
     */
    protected $propToTest = "number";

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new \STS\CMDB\CMDBTaskList();
        $this->objectTable = new StubCMDBTaskListTable();
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        unset($this->object);
        unset($this->objectTable);
    }

    public function testGettersAndSetters()
    {
        $this->object->set($this->propToTest, 'prop_value');
        $this->assertEquals('prop_value', $this->object->get($this->propToTest));

        foreach ($this->objectTable->getNameMapping() as $prop) {
            $upperProp = preg_replace_callback(
                "/^(\w)/",
                function($matches) {
                    return strtoupper($matches[1]);
                },
                $prop
            );
            $setter = "set" . $upperProp;
            $this->object->$setter("xxx");
            $getter = "get" . $upperProp;
            $this->assertEquals("xxx", $this->object->$getter());
        }
    }

    /**
     * @covers \STS\CMDB\CMDBTaskList::getChanges
     * @covers \STS\CMDB\CMDBTaskList::clearChanges
     */
    public function testChanges()
    {
        $setProp = "set" . ucfirst($this->propToTest);
        $this->object->$setProp("prop_value");
        $changes = $this->object->getChanges();
        $this->assertInternalType("array", $changes);
        $this->assertEquals(1, count($changes));
        $this->assertArrayHasKey($this->propToTest, $changes);
        $this->assertObjectHasAttribute("originalValue", $changes[$this->propToTest]);
        $this->assertObjectHasAttribute("modifiedValue", $changes[$this->propToTest]);
        $this->assertEquals(null, $changes[$this->propToTest]->originalValue);
        $this->assertEquals("prop_value", $changes[$this->propToTest]->modifiedValue);

        $this->object->clearChanges();
        $changes = $this->object->getChanges();
        $this->assertInternalType("array", $changes);
        $this->assertEquals(0, count($changes));

        // test that changes are updated for every property
        $numProps = 0;
        foreach ($this->objectTable->getNameMapping() as $prop) {
            $numProps++;
            $upperProp = preg_replace_callback(
                "/^(\w)/",
                function($matches) {
                    return strtoupper($matches[1]);
                },
                $prop
            );
            $setter = "set" . $upperProp;
            $this->object->$setter("xxx");
            $this->assertEquals($numProps, count($this->object->getChanges()), "Changes not updated for {$prop}");
        }
    }

    /**
     * @covers \STS\CMDB\CMDBTaskList::__toString
     */
    public function test__toString()
    {
        $string = "";
        foreach ($this->objectTable->getNameMapping() as $prop) {
            $string .= sprintf("%-25s => %s\n", $prop, $this->object->get($prop));
        }

        $this->assertEquals($string, $this->object->__toString());
    }

    /**
     * @covers \STS\CMDB\CMDBTaskList::toObject
     */
    public function testToObject()
    {
        $obj = (object) array();
        foreach ($this->objectTable->getNameMapping() as $prop) {
            $obj->$prop = $this->object->get($prop);
        }

        $this->assertEquals($obj, $this->object->toObject());
    }
}

