<?php
/*******************************************************************************
 *
 * $Id: CMDBServerTableTest.php 79057 2013-09-19 18:12:14Z rcallaha $
 * $Date: 2013-09-19 14:12:14 -0400 (Thu, 19 Sep 2013) $
 * $Author: rcallaha $
 * $Revision: 79057 $
 * $HeadURL: https://svn.ultradns.net/svn/sts_tools/sts-lib/trunk/test/CMDB/CMDBServerTableTest.php $
 *
 *******************************************************************************
 */

class CMDBSysReportTableTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var STS\CMDB\CMDBSysReportTable
     */
    protected $objectTable;

    protected $testSysId = "8ea874648dc14d402bd59db7ec75d305";
    protected $testTitle = "Chassis, Ops";

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->objectTable = new StubCMDBSysReportTable;
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        unset($this->objectTable);
    }

    /**
     * Tests the forward and revers name mapping of ServiceNow to model properties
     *
     * @covers: \STS\CMDB\CMDBSysReportTable::getNameMapping
     * @covers: \STS\CMDB\CMDBSysReportTable::getReverseNameMapping
     */
    public function testNameMap()
    {
        $nameMap = $this->objectTable->getNameMapping();
        $this->assertInternalType('array', $nameMap);

        $revNameMap = $this->objectTable->getReverseNameMapping();
        $this->assertInternalType('array', $revNameMap);

        $this->assertEquals(count($nameMap), count($revNameMap));

        foreach ($nameMap as $prop) {
            $this->assertArrayHasKey($prop, $revNameMap);
        }
        foreach ($revNameMap as $field) {
            $this->assertArrayHasKey($field, $nameMap);
        }
    }

    /**
     * @covers: \STS\CMDB\CMDBSysReportTable::setFormat
     * @covers: \STS\CMDB\CMDBSysReportTable::getFormat
     */
    public function testSetFormat()
    {
        $this->objectTable->setFormat("JSON");
        $format = $this->objectTable->getFormat();
        $this->assertEquals("JSON", $format);
    }

    /**
     * @covers: \STS\CMDB\CMDBSysReportTable::setLogLevel
     * @covers: \STS\CMDB\CMDBSysReportTable::getLogLevel
     */
    public function testLogLevel()
    {
        $this->objectTable->setLogLevel(\STS\UTIL\SysLog::NOTICE);
        $logLevel = $this->objectTable->getLogLevel();
        $this->assertEquals(\STS\UTIL\SysLog::NOTICE, $logLevel);
    }

    /**
     * @covers STS\CMDB\CMDBSysReportTable::setPrintResult
     * @covers STS\CMDB\CMDBSysReportTable::getPrintResult
     */
    public function testSetGetPrintResult()
    {
        $this->objectTable->setPrintResult(true);
        $this->assertEquals(true, $this->objectTable->getPrintResult());
    }

    /**
     * @covers STS\CMDB\CMDBSysReportTable::getById
     */
    public function testGetById()
    {
        $object = $this->objectTable->getById($this->testSysId);
        $this->checkClassProperties($object);

        $object = $this->objectTable->getById($this->testSysId, $raw = true);
        $this->checkObjectProperties($object);
    }

    /**
     * @covers STS\CMDB\CMDBSysReportTable::getBySysId
     */
    public function testGetBySysId()
    {
        $object = $this->objectTable->getBySysId($this->testSysId);
        $this->checkClassProperties($object);

        $object = $this->objectTable->getBySysId($this->testSysId, $raw=true);
        $this->checkObjectProperties($object);
    }

    /**
     * @covers STS\CMDB\CMDBSysReportTable::getByTitle
     */
    public function testGetByTitle()
    {
        $object = $this->objectTable->getByTitle($this->testTitle);
        $this->checkClassProperties($object);
    }

    /**
     * @covers STS\CMDB\CMDBSysReportTable::getByQueryString
     */
    public function testGetByQueryString()
    {
        $objects = $this->objectTable->getByQueryString("title=" . $this->testTitle);
        $this->assertInternalType('array', $objects);
        $this->assertEquals(1, count($objects));
        $o = $objects[0];
        $this->checkClassProperties($o);
    }

    /**
     * @covers STS\CMDB\CMDBSysReportTable::update
     * @covers STS\CMDB\CMDBSysReportTable::updateByJson
     */
    public function testUpdate()
    {
        $object = $this->objectTable->getByTitle($this->testTitle);

        // first no change
        $object = $this->objectTable->update($object);
        $this->assertInstanceOf('\STS\CMDB\CMDBSysReport', $object);
        $this->assertEquals($this->testTitle, $object->getTitle());

        // since there were no changes, the last history entry should be the query url
        $this->assertEquals('https://neustartest.service-now.com/sys_report.do?JSON&displayvalue=all&sysparm_action=getRecords&sysparm_query=title%3DChassis%2C%20Ops',
            $this->objectTable->getQueryHistory());

        // change Title
        $object->setTitle("fred");
        $object = $this->objectTable->update($object);
        $this->assertInstanceOf('\STS\CMDB\CMDBSysReport', $object);
        $this->assertEquals($this->testTitle, $object->getTitle());

        // this time there are changes so update is called. The last query will be the get to query the CI after the update
        // so we need to go back 2 queries to test that the URL was correct. Same for JSON
        $this->assertEquals('https://neustartest.service-now.com/sys_report.do?JSON&sysparm_action=update&sysparm_query=sys_id=8ea874648dc14d402bd59db7ec75d305',
            $this->objectTable->getQueryHistory(2));
        $this->assertEquals('{"title":"fred"}', $this->objectTable->getJsonHistory(2));
    }

    /**
     * @covers STS\CMDB\CMDBSysReportTable::create
     * @covers STS\CMDB\CMDBSysReportTable::createByJson
     */
    public function testCreate()
    {
        $object = $this->objectTable->getByTitle($this->testTitle);

        // first no change
        $object = $this->objectTable->create($object);
        $this->assertInstanceOf('\STS\CMDB\CMDBSysReport', $object);
        $this->assertEquals($this->testTitle, $object->getTitle());

        // since there were no changes, the last history entry should be the query url
        $this->assertEquals('https://neustartest.service-now.com/sys_report.do?JSON&displayvalue=all&sysparm_action=getRecords&sysparm_query=title%3DChassis%2C%20Ops',
            $this->objectTable->getQueryHistory());

        // change Title
        $object->setTitle("fred");
        $this->assertEquals(1, count($object->getChanges()));
        $object = $this->objectTable->create($object);
        $this->assertInstanceOf('\STS\CMDB\CMDBSysReport', $object);

        $this->assertEquals($this->testTitle, $object->getTitle());

        $this->assertEquals('https://neustartest.service-now.com/sys_report.do?JSON&sysparm_action=insert',
            $this->objectTable->getQueryHistory());
        $this->assertEquals('{"title":"fred"}', $this->objectTable->getJsonHistory());
    }

    /**
     * Checks all the CMDBSysReport properties
     *
     * @param \STS\CMDB\CMDBSysReport $o
     */
    public function checkClassProperties(\STS\CMDB\CMDBSysReport $o)
    {
        $this->assertInstanceOf('STS\CMDB\CMDBSysReport', $o);

        $this->assertEquals("8ea874648dc14d402bd59db7ec75d305", $o->getSysId());
        $this->assertEquals("COUNT", $o->getAggregate());
        $this->assertEquals("large", $o->getChartSize());
        $this->assertEquals("", $o->getContent());
        $this->assertEquals("", $o->getColumn());
        $this->assertEquals("false", $o->getDisplayGrid());
        $this->assertEquals("", $o->getField());
        $this->assertEquals("name,u_business_service_s_,u_cmdb_subsystem_list,short_description,u_environment,os,u_host_type,last_discovered,u_last_ddmi_update,manufacturer,install_status,sys_created_on,sys_created_by,location,model_number,serial_number,sys_updated_by,ip_address", $o->getFieldList());
        $this->assertEquals("u_business_service_s_LIKEaf3a11b38d7c49402bd59db7ec75d367^EQ^ORDERBYlast_discovered", $o->getFilter());
        $this->assertEquals("554defc10a0a3cab00716149bea67674", $o->getGroup());
        $this->assertEquals("year", $o->getInterval());
        $this->assertEquals("", $o->getOrderByList());
        $this->assertEquals("-2", $o->getOtherThreshold());
        $this->assertEquals("", $o->getOthers());
        $this->assertEquals("", $o->getRoles());
        $this->assertEquals("", $o->getRow());
        $this->assertEquals("false", $o->getShowEmpty());
        $this->assertEquals("", $o->getSumField());
        $this->assertEquals("0", $o->getSysModCount());
        $this->assertEquals("cmdb_ci_server", $o->getTable());
        $this->assertEquals("Chassis, Ops", $o->getTitle());
        $this->assertEquals("", $o->getTrendField());
        $this->assertEquals("list", $o->getType());
        $this->assertEquals("group", $o->getUser());
        $this->assertEquals("gsalinge", $o->getSysCreatedBy());
        $this->assertEquals("2013-04-26 19:11:55", $o->getSysCreatedOn());
        $this->assertEquals("gsalinge", $o->getSysUpdatedBy());
        $this->assertEquals("2013-04-26 19:11:55", $o->getSysUpdatedOn());
    }

    /**
     * Checks all the stdObject properties
     *
     * @param stdClass $o
     */
    public function checkObjectProperties(stdClass $o)
    {
        $this->assertInstanceOf("stdClass", $o);

        $this->assertEquals("COUNT", $o->aggregate);
        $this->assertEquals("large", $o->chart_size);
        $this->assertEquals("", $o->column);
        $this->assertEquals("", $o->content);
        $this->assertEquals("false", $o->display_grid);
        $this->assertEquals("Count", $o->dv_aggregate);
        $this->assertEquals("Large", $o->dv_chart_size);
        $this->assertEquals("", $o->dv_column);
        $this->assertEquals("", $o->dv_content);
        $this->assertEquals("false", $o->dv_display_grid);
        $this->assertEquals("", $o->dv_field);
        $this->assertEquals("name,u_business_service_s_,u_cmdb_subsystem_list,short_description,u_environment,os,u_host_type,last_discovered,u_last_ddmi_update,manufacturer,install_status,sys_created_on,sys_created_by,location,model_number,serial_number,sys_updated_by,ip_address", $o->dv_field_list);
        $this->assertEquals("u_business_service_s_LIKEaf3a11b38d7c49402bd59db7ec75d367^EQ^ORDERBYlast_discovered", $o->dv_filter);
        $this->assertEquals("Core Hosting", $o->dv_group);
        $this->assertEquals("Year", $o->dv_interval);
        $this->assertEquals("", $o->dv_orderby_list);
        $this->assertEquals("System Default", $o->dv_other_threshold);
        $this->assertEquals("", $o->dv_others);
        $this->assertEquals("", $o->dv_roles);
        $this->assertEquals("", $o->dv_row);
        $this->assertEquals("false", $o->dv_show_empty);
        $this->assertEquals("", $o->dv_sumfield);
        $this->assertEquals("gsalinge", $o->dv_sys_created_by);
        $this->assertEquals("2013-04-26 15:11:55", $o->dv_sys_created_on);
        $this->assertEquals("8ea874648dc14d402bd59db7ec75d305", $o->dv_sys_id);
        $this->assertEquals("0", $o->dv_sys_mod_count);
        $this->assertEquals("gsalinge", $o->dv_sys_updated_by);
        $this->assertEquals("2013-04-26 15:11:55", $o->dv_sys_updated_on);
        $this->assertEquals("cmdb_ci_server", $o->dv_table);
        $this->assertEquals("Chassis, Ops", $o->dv_title);
        $this->assertEquals("", $o->dv_trend_field);
        $this->assertEquals("List", $o->dv_type);
        $this->assertEquals("group", $o->dv_user);
        $this->assertEquals("", $o->field);
        $this->assertEquals("name,u_business_service_s_,u_cmdb_subsystem_list,short_description,u_environment,os,u_host_type,last_discovered,u_last_ddmi_update,manufacturer,install_status,sys_created_on,sys_created_by,location,model_number,serial_number,sys_updated_by,ip_address", $o->field_list);
        $this->assertEquals("u_business_service_s_LIKEaf3a11b38d7c49402bd59db7ec75d367^EQ^ORDERBYlast_discovered", $o->filter);
        $this->assertEquals("554defc10a0a3cab00716149bea67674", $o->group);
        $this->assertEquals("year", $o->interval);
        $this->assertEquals("", $o->orderby_list);
        $this->assertEquals("-2", $o->other_threshold);
        $this->assertEquals("", $o->others);
        $this->assertEquals("", $o->roles);
        $this->assertEquals("", $o->row);
        $this->assertEquals("false", $o->show_empty);
        $this->assertEquals("", $o->sumfield);
        $this->assertEquals("gsalinge", $o->sys_created_by);
        $this->assertEquals("2013-04-26 19:11:55", $o->sys_created_on);
        $this->assertEquals("8ea874648dc14d402bd59db7ec75d305", $o->sys_id);
        $this->assertEquals("0", $o->sys_mod_count);
        $this->assertEquals("gsalinge", $o->sys_updated_by);
        $this->assertEquals("2013-04-26 19:11:55", $o->sys_updated_on);
        $this->assertEquals("cmdb_ci_server", $o->table);
        $this->assertEquals("Chassis, Ops", $o->title);
        $this->assertEquals("", $o->trend_field);
        $this->assertEquals("list", $o->type);
        $this->assertEquals("group", $o->user);
    }
}