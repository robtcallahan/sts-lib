<?php
/**
 * Generated by PHPUnit_SkeletonGenerator on 2012-07-13 at 14:16:18.
 */

require_once(__DIR__ . "/../php/global.php");

class SwatLogTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var SwatLog
     */
    protected $object;
	/**
	 * @var MySqlDB $swatDB
	 */
	protected $swatDB;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new SwatLog;
	    $this->swatDB = new MySqlDB('swat');

	    $this->assertTrue(!is_null($this->object));
	    $this->assertTrue(!is_null($this->swatDB));
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     * @var MySqlDB $swatDB
     */
    protected function tearDown()
    {

	    $sql = "DELETE FROM swat WHERE process like '%phpunit%' ";
	    $result = null;
	    $this->swatDB->connect();
	    $result = $this->swatDB->query($sql);
	    $rows = $this->swatDB->getAffectedRows();
	    $this->assertEquals("1", $rows, "Deleted successfully from swat db");
	    $this->swatDB->close();

    }

    /**
     * @covers SwatLog::createEntry
     */
    public function testCreateEntry()
    {
		$this->assertTrue($this->object->createEntry('start'));
    }

}
