<?php
/*******************************************************************************
 *
 * $Id: DBTable.php 74931 2013-05-03 19:21:04Z rcallaha $
 * $Date: 2013-05-03 15:21:04 -0400 (Fri, 03 May 2013) $
 * $Author: rcallaha $
 * $Revision: 74931 $
 * $HeadURL: https://svn.ultradns.net/svn/sts_tools/sts-lib/trunk/STS/DB/DBTable.php $
 *
 *******************************************************************************
 */

use \Zend\Db\Adapter\Adapter;

class DBTableTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \STS\Database\DBTable
     */
    private static $dbTable;
    private static $adapter;

    private static $dbConfig;
    private static $password;

    private static $dbInitFile;
    private static $testTable;

    private $user;

    public static function setUpBeforeClass()
    {
        self::$dbConfig = array(
            "username"    => "unittest",
            "password"    => 'testunit',
            "database"    => "dbtest"
            );

        self::$password = self::$dbConfig['password'];

        self::$dbInitFile = DATADIR . "/Database/dbtest.sql";

        $command = MYSQLBIN . "/mysql --user=" . self::$dbConfig['username'] . " --password=" . self::$password . " <" . self::$dbInitFile;
        system($command);


        self::$testTable = "data_types";
        self::$dbTable = new \STS\Database\DBTable(self::$dbConfig, self::$testTable);

        self::$adapter = new Adapter(array(
            'driver' => 'Mysqli',
            'database' => self::$dbConfig['database'],
            'username' => self::$dbConfig['username'],
            'password' => self::$dbConfig['password']
         ));

    }

    public static function tearDownAfterClass()
    {
        self::$dbTable = null;
    }

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $command = MYSQLBIN . "/mysql --user=" . self::$dbConfig['username'] . " --password=" . self::$password . " <" . self::$dbInitFile;
        system($command);
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    /**
     * @covers STS\Database\DBTable::update
     */
    public function testUpdate()
    {
        /** @var \Zend\Db\Adapter\Adapter $adapter */
        $adapter = self::$adapter;

        $row = self::$dbTable->sqlQueryRow("select * from data_types where colVarcharNotNull = 'var_char_not_null';");
        $dataType = new DataTypes();
        $dataType
            ->setId($row['id'])
            ->setColVarcharNotNull("varchar")
            ->setColVarcharNull("varChar")
            ->setColCharNotNull("Y")
            ->setColCharNull("X")
            ->setColFloatNotNull(2.0)
            ->setColFloatNull(10.4)
            ->setColDoubleNotNull(25.2)
            ->setColDoubleNull(25.6)
            ->setColDecimalNotNull(8.2)
            ->setColDecimalNull(5.5)
            ->setColDateNotNull('2010-10-10')
            ->setColDateNull('2012-10-10')
            ->setColDatetimeNotNull('2010-10-10 22:22:22')
            ->setColDatetimeNull('2012-10-10 11:11:11')
            ->setColTimeNotNull('23:00:00')
            ->setColTimeNull('12:00:00')
            ->setColTimestampNotNull('now()')
            ->setColTimestampNull('2010-10-10 22:22:22')
            ->setColEnumNotNull('Three')
            ->setColEnumNull('Two')
            ->setColBlobNotNull('adlkfjalskdfjlaskfjlaksdfj')
            ->setColBlobNull('lsdjflskjflskdfsldkflskdf');
        self::$dbTable->update($dataType);

        $results = $adapter->query("select * from data_types where id = " . $row['id'] . ";", Adapter::QUERY_MODE_EXECUTE);
        $testRow = $results->current();
        print_r($testRow);
        $this->assertEquals("", $testRow);
    }

    /**
     * @covers STS\Database\DBTable::getColumns
     */
    public function testGetColumns()
    {
        $columnInfo = self::$dbTable->getColumns();
        $this->assertTrue(is_array($columnInfo));
        $expected = file_get_contents(DATADIR . "/Database/data_types_table_columns.ser");
        $this->assertSame($expected, serialize($columnInfo));
    }

    /**
     * @covers STS\Database\DBTable::getColumnNames
     */
    public function testGetColumnNames()
    {
        /** @var \Zend\Db\Adapter\Adapter $adapter */
        $adapter = self::$adapter;

        $sql = "SHOW COLUMNS FROM " . self::$testTable . ";";
        $results = $adapter->query($sql, Adapter::QUERY_MODE_EXECUTE);
        $resultsArray = $results->toArray();
        $colsArray = array();
        foreach ($resultsArray as $hash) {
            $colsArray[] = $hash['Field'];
        }

        $columnNames = self::$dbTable->getColumnNames();
        $this->assertSame($colsArray, $columnNames);
    }

    /**
     * @covers STS\Database\DBTable::getQueryColumnsStr
     */
    public function testGetQueryColumnsStr()
    {
        /** @var \Zend\Db\Adapter\Adapter $adapter */
        $adapter = self::$adapter;

        $sql = "SHOW COLUMNS FROM " . self::$testTable . ";";
        $results = $adapter->query($sql, Adapter::QUERY_MODE_EXECUTE);
        $resultsArray = $results->toArray();
        $colsArray = array();
        foreach ($resultsArray as $hash) {
            $colsArray[] = $hash['Field'];
        }
        $expected = implode(",", $colsArray);

        $columnNamesStr = self::$dbTable->getQueryColumnsStr();
        $this->assertSame($expected, $columnNamesStr);
    }

    /**
     * @covers STS\Database\DBTable::getLogLevel
     */
    public function testGetLogLevel()
    {
        $this->assertEquals(\STS\Util\SysLog::NOTICE, self::$dbTable->getLogLevel());
    }

    /**
     * @covers STS\Database\DBTable::setLogLevel
     */
    public function testSetLogLevel()
    {
        self::$dbTable->setLogLevel(\STS\Util\SysLog::INFO);
        $this->assertEquals(\STS\Util\SysLog::INFO, self::$dbTable->getLogLevel());
    }

    /**
     * @covers STS\Database\DBTable::getNumberTypes
     */
    public function testGetNumberTypes()
    {
        $this->assertEquals("/decimal|double|float|int|numeric/", self::$dbTable->getNumberTypes());
    }

    /**
     * @covers STS\Database\DBTable::getInsertColumns
     */
    public function testGetInsertColumns()
    {
        $columns = unserialize(file_get_contents(DATADIR . "/Database/data_types_table_columns.ser"));

        $insertColumns = array();
        foreach ($columns as $hash) {
            if (!array_key_exists('primaryKey', $hash) || !$hash['primaryKey']) {
                $insertColumns[$hash['name']] = $hash;
            }
        }
        $this->assertSame($insertColumns, self::$dbTable->getInsertColumns());
    }

    /**
     * @covers STS\Database\DBTable::getSysLog
     */
    public function testGetSysLog()
    {
        $this->assertInstanceOf('\STS\Util\SysLog', self::$dbTable->getSysLog());
    }

    /**
     * @covers STS\Database\DBTable::getAutoIncrement
     */
    public function testGetAutoIncrement()
    {
        $this->assertTrue(self::$dbTable->getAutoIncrement());
    }

    /**
     * @covers STS\Database\DBTable::getPrimaryKey
     */
    public function testGetPrimaryKey()
    {
        $this->assertEquals("id", self::$dbTable->getPrimaryKey());
    }

    /**
     * @covers STS\Database\DBTable::getSysLogName
     */
    public function testGetSysLogName()
    {
        $this->assertEquals("DBTable", self::$dbTable->getSysLogName());
    }

    /**
     * @covers STS\Database\DBTable::setTableName
     */
    public function testSetTableName()
    {
        self::$dbTable->setSysLogName("UnitTest");
        $this->assertEquals("UnitTest", self::$dbTable->getSysLogName());
    }

    /**
     * @covers STS\Database\DBTable::getAdapter
     */
    public function testGetAdapter()
    {
        $this->assertInstanceOf('\Zend\Db\Adapter\Adapter', self::$dbTable->getAdapter());
    }

    /**
     * @covers STS\Database\DBTable::sqlQueryRow
     */
    public function testSqlQueryRow()
    {
        /** @var \Zend\Db\Adapter\Adapter $adapter */
        $adapter = self::$adapter;

        $sql = "select lastName from user;";
        $results = $adapter->query($sql, Adapter::QUERY_MODE_EXECUTE);
        $expected = $results->current();

        $this->setExpectedException('ErrorException', "No SQL statement passed as argument");
        self::$dbTable->sqlQueryRow("");

        $row = self::$dbTable->sqlQueryRow("select lastName from user;");

        $this->assertEquals($expected, $row);
    }

    /**
     * @covers STS\Database\DBTable::sqlQuery
     */
    public function testSqlQuery()
    {
        /** @var \Zend\Db\Adapter\Adapter $adapter */
        $adapter = self::$adapter;

        $sql = "select * from user order by id;";
        $results = $adapter->query($sql, Adapter::QUERY_MODE_EXECUTE);
        $expected = $results->current();

        $this->setExpectedException('ErrorException', "No SQL statement passed as argument");
        self::$dbTable->sqlQueryRow("");

        $row = self::$dbTable->sqlQueryRow("select * from user order by id;");

        $this->assertEquals($expected, $row);
    }

    /**
     * @covers STS\Database\DBTable::sql
     */
    public function testSql()
    {
        /** @var \Zend\Db\Adapter\Adapter $adapter */
        $adapter = self::$adapter;

        $adapter->query("insert into user (firstName, lastName, userName) values('Joe', 'User', 'juser');", Adapter::QUERY_MODE_EXECUTE);
        $results = $adapter->query("select * from user where userName = 'juser';", Adapter::QUERY_MODE_EXECUTE);
        $expected = $results->current();

        $command = MYSQLBIN . "/mysql --user=" . self::$dbConfig['username'] . " --password=" . self::$password . " <" . self::$dbInitFile;
        system($command);

        $this->setExpectedException('ErrorException', "No SQL statement passed as argument");
        self::$dbTable->sqlQueryRow("");

        self::$dbTable->sql("insert into user (firstName, lastName, userName) values('Joe', 'User', 'juser');");
        $results = $adapter->query("select * from user where userName = 'juser';", Adapter::QUERY_MODE_EXECUTE);
        $actual = $results->current();

        $this->assertEquals($expected, $actual);
    }

    /**
     * @covers STS\Database\DBTable::create
     */
    public function testCreate()
    {
        /** @var \Zend\Db\Adapter\Adapter $adapter */
        $adapter = self::$adapter;

        $dataType = new DataTypes();
        $dataType
            ->setColVarcharNotNull("")
            ->setColVarcharNull("varChar")
            ->setColCharNull("X")
            ->setColDecimalNotNull(8.2)
            ->setColFloatNull(10.4)
            ->setColDoubleNotNull(25.2)
            ->setColDateNotNull('2010-10-10')
            ->setColDateNull('2010-10-10')
            ->setColTimeNull('12:00:00')
            ->setColTimestampNotNull('now()')
            ->setColEnumNull('Two');
        $newId = self::$dbTable->create($dataType);
        $dataTypeRow = self::$dbTable->sqlQueryRow("select * from data_types where col_varchar_null = 'varChar';");

        $results = $adapter->query("select * from data_types where col_varchar_null = 'varChar';", Adapter::QUERY_MODE_EXECUTE);
        $expected = $results->current();

        $this->assertEquals($expected['id'], $newId);
        $this->assertEquals($expected, $dataTypeRow);
    }

    /**
     * @covers STS\Database\DBTable::delete
     * @todo   Implement testDelete().
     */
    public function testDelete()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

}
