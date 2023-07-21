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

class CMDBCITableTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var STS\CMDB\CMDBCITable
     */
    protected $objectTable;

    protected $testSysId = "1ae74a40116efc407fa2908b78101b4f";
    protected $testName = "stsccprdlpmail01.va.neustar.com";
    protected $testSerialNumber = "USE10770E6";
    protected $testAssetId = "000000010340";

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->objectTable = new StubCMDBCITable;
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
     * @covers: \STS\CMDB\CMDBCITable::getNameMapping
     * @covers: \STS\CMDB\CMDBCITable::getReverseNameMapping
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
     * @covers: \STS\CMDB\CMDBCITable::setFormat
     * @covers: \STS\CMDB\CMDBCITable::getFormat
     */
    public function testSetFormat()
    {
        $this->objectTable->setFormat("JSON");
        $format = $this->objectTable->getFormat();
        $this->assertEquals("JSON", $format);
    }

    /**
     * @covers: \STS\CMDB\CMDBCITable::setLogLevel
     * @covers: \STS\CMDB\CMDBCITable::getLogLevel
     */
    public function testLogLevel()
    {
        $this->objectTable->setLogLevel(\STS\UTIL\SysLog::NOTICE);
        $logLevel = $this->objectTable->getLogLevel();
        $this->assertEquals(\STS\UTIL\SysLog::NOTICE, $logLevel);
    }

    /**
     * @covers STS\CMDB\CMDBCITable::getById
     */
    public function testGetById()
    {
        $object = $this->objectTable->getById($this->testSysId);
        $this->checkClassProperties($object);

        $object = $this->objectTable->getById($this->testSysId, $raw=true);
        $this->checkObjectProperties($object);
    }

    /**
     * @covers STS\CMDB\CMDBCITable::getBySysId
     */
    public function testGetBySysId()
    {
        $object = $this->objectTable->getBySysId($this->testSysId);
        $this->checkClassProperties($object);

        $object = $this->objectTable->getBySysId($this->testSysId, $raw=true);
        $this->checkObjectProperties($object);
    }

    /**
     * @covers STS\CMDB\CMDBCITable::getByName
     */
    public function testGetByName()
    {
        $object = $this->objectTable->getByName($this->testName);
        $this->checkClassProperties($object);

        $object = $this->objectTable->getByName($this->testName, $raw=true);
        $this->checkObjectProperties($object);
    }

    /**
     * @covers STS\CMDB\CMDBCITable::getByNameLike
     */
    public function testGetByNameLike()
    {
        $object = $this->objectTable->getByNameLike($this->testName);
        $this->checkClassProperties($object);

        $object = $this->objectTable->getByNameLike($this->testName, $raw=true);
        $this->checkObjectProperties($object);
    }

    /**
     * @covers STS\CMDB\CMDBCITable::getByNameStartsWith
     */
    public function testGetByNameStartsWith()
    {
        $object = $this->objectTable->getByNameStartsWith($this->testName);
        $this->checkClassProperties($object);

        $object = $this->objectTable->getByNameStartsWith($this->testName, $raw=true);
        $this->checkObjectProperties($object);
    }

    /**
     * @covers STS\CMDB\CMDBCITable::getAllByNameLike
     */
    public function testGetAllByNameLike()
    {
        $objects = $this->objectTable->getAllByNameLike($this->testName);
        $this->assertInternalType('array', $objects);
        $this->assertEquals(1, count($objects));
        $o = $objects[0];
        $this->checkClassProperties($o);

        $objects = $this->objectTable->getAllByNameLike($this->testName, $anyStatus=true);
        $this->assertInternalType('array', $objects);
        $this->assertEquals(1, count($objects));
        $o = $objects[0];
        $this->checkClassProperties($o);
    }

    /**
     * @covers STS\CMDB\CMDBCITable::getBySerialNumber
     */
    public function testGetBySerialNumber()
    {
        $object = $this->objectTable->getBySerialNumber($this->testSerialNumber);
        $this->checkClassProperties($object);

        $object = $this->objectTable->getBySerialNumber($this->testSerialNumber, $raw=true);
        $this->checkObjectProperties($object);
    }

    /**
     * @covers STS\CMDB\CMDBCITable::getByAssetId
     */
    public function testGetByAssetId()
    {
        $object = $this->objectTable->getByAssetId($this->testAssetId);
        $this->checkClassProperties($object);

        $object = $this->objectTable->getByAssetId($this->testAssetId, $raw=true);
        $this->checkObjectProperties($object);
    }

    /**
     * @covers STS\CMDB\CMDBCITable::getByQueryString
     */
    public function testGetByQueryString()
    {
        $objects = $this->objectTable->getByQueryString("install_statusNOT IN117,1501^nameLIKE" . $this->testName);
        $this->assertInternalType('array', $objects);
        $this->assertEquals(1, count($objects));
        $o = $objects[0];
        $this->checkClassProperties($o);
    }

    /**
     * @covers STS\CMDB\CMDBCITable::update
     * @covers STS\CMDB\CMDBCITable::updateByJson
     */
    public function testUpdate()
    {
        $object = $this->objectTable->getByName($this->testName);

        // first no change
        $object = $this->objectTable->update($object);
        $this->assertInstanceOf('\STS\CMDB\CMDBCI', $object);
        $this->assertEquals($this->testName, $object->getName());

        // since there were no changes, the last history entry should be the query url
        $this->assertEquals('https://neustartest.service-now.com/cmdb_ci.do?JSON&displayvalue=all&sysparm_action=getRecords&sysparm_query=sys_class_name%21%3D%5Einstall_statusNOT%20IN117%2C1501%5Ename%3Dstsccprdlpmail01.va.neustar.com',
            $this->objectTable->getQueryHistory());

        // change name
        $object->setName("fred");
        $object = $this->objectTable->update($object);
        $this->assertInstanceOf('\STS\CMDB\CMDBCI', $object);
        $this->assertEquals($this->testName, $object->getName());

        // this time there are changes so update is called. The last query will be the get to query the CI after the update
        // so we need to go back 2 queries to test that the URL was correct. Same for JSON
        $this->assertEquals('https://neustartest.service-now.com/cmdb_ci.do?JSON&sysparm_action=update&sysparm_query=sys_id=1ae74a40116efc407fa2908b78101b4f',
            $this->objectTable->getQueryHistory(2));
        $this->assertEquals('{"name":"fred"}', $this->objectTable->getJsonHistory(2));
    }

    /**
     * Checks all the CMDBCI properties
     *
     * @param \STS\CMDB\CMDBCI $o
     */
    public function checkClassProperties(\STS\CMDB\CMDBCI $o)
    {
        $this->assertInstanceOf('STS\CMDB\CMDBCI', $o);

        $this->assertEquals("1ae74a40116efc407fa2908b78101b4f", $o->getSysId());
        $this->assertEquals("cmdb_ci_server", $o->getSysClassName());
        $this->assertEquals("stsccprdlpmail01.va.neustar.com", $o->getName());
        $this->assertEquals("USE10770E6", $o->getSerialNumber());
        $this->assertEquals("147906", $o->getAssetTag());
        $this->assertEquals("", $o->getLocationType());
        $this->assertEquals("", $o->getLocationTypeId());
        $this->assertEquals("252770b80a0a3cac01a23e2b410dd37d", $o->getLocationId());
        $this->assertEquals("Sterling-VA-NSR-B8", $o->getLocation());
        $this->assertEquals("", $o->getRackId());
        $this->assertEquals("", $o->getRack());
        $this->assertEquals("", $o->getNumberOfRackUnits());
        $this->assertEquals("", $o->getRackPosition());
        $this->assertEquals("Live", $o->getInstallStatus());
        $this->assertEquals("255b48640a0a3cac013f4f5866d7b7b8", $o->getManufacturerId());
        $this->assertEquals("HP", $o->getManufacturer());
        $this->assertEquals("HP ProLiant BL460c", $o->getModelNumber());
        $this->assertEquals("2011-02-23 05:00:00", $o->getDeliveryDate());
        $this->assertEquals("0000006906", $o->getPoNumber());
        $this->assertEquals("000000010340", $o->getAssetId());
        $this->assertEquals("", $o->getAssetReceiptDateTime());
        $this->assertEquals("Sabir, Rizwan", $o->getPoRequestor());
        $this->assertEquals("Sabir, Rizwan", $o->getPoRequestorId());
        $this->assertEquals("zhassan", $o->getSysCreatedBy());
        $this->assertEquals("2012-12-22 05:06:11", $o->getSysCreatedOn());
        $this->assertEquals("steve_stumpf", $o->getSysUpdatedBy());
        $this->assertEquals("2013-07-23 12:04:59", $o->getSysUpdatedOn());
    }

    /**
     * Checks all the stdObject properties
     *
     * @param stdClass $o
     */
    public function checkObjectProperties(stdClass $o)
    {
        $this->assertInstanceOf("stdClass", $o);

        $this->assertEquals("", $o->asset);
        $this->assertEquals("147906", $o->asset_tag);
        $this->assertEquals("", $o->assigned);
        $this->assertEquals("", $o->assigned_to);
        $this->assertEquals("", $o->attributes);
        $this->assertEquals("false", $o->can_print);
        $this->assertEquals("", $o->category);
        $this->assertEquals("", $o->change_control);
        $this->assertEquals("", $o->checked_in);
        $this->assertEquals("", $o->checked_out);
        $this->assertEquals("", $o->comments);
        $this->assertEquals("", $o->company);
        $this->assertEquals("", $o->correlation_id);
        $this->assertEquals("0", $o->cost);
        $this->assertEquals("USD", $o->cost_cc);
        $this->assertEquals("", $o->cost_center);
        $this->assertEquals("2011-02-23 05:00:00", $o->delivery_date);
        $this->assertEquals("", $o->department);
        $this->assertEquals("", $o->discovery_source);
        $this->assertEquals("", $o->dns_domain);
        $this->assertEquals("", $o->due);
        $this->assertEquals("", $o->due_in);
        $this->assertEquals("", $o->dv_asset);
        $this->assertEquals("147906", $o->dv_asset_tag);
        $this->assertEquals("", $o->dv_assigned);
        $this->assertEquals("", $o->dv_assigned_to);
        $this->assertEquals("", $o->dv_attributes);
        $this->assertEquals("false", $o->dv_can_print);
        $this->assertEquals("", $o->dv_category);
        $this->assertEquals("", $o->dv_change_control);
        $this->assertEquals("", $o->dv_checked_in);
        $this->assertEquals("", $o->dv_checked_out);
        $this->assertEquals("", $o->dv_comments);
        $this->assertEquals("", $o->dv_company);
        $this->assertEquals("", $o->dv_correlation_id);
        $this->assertEquals("", $o->dv_cost);
        $this->assertEquals("USD", $o->dv_cost_cc);
        $this->assertEquals("", $o->dv_cost_center);
        $this->assertEquals("2011-02-23 00:00:00", $o->dv_delivery_date);
        $this->assertEquals("", $o->dv_department);
        $this->assertEquals("", $o->dv_discovery_source);
        $this->assertEquals("", $o->dv_dns_domain);
        $this->assertEquals("", $o->dv_due);
        $this->assertEquals("", $o->dv_due_in);
        $this->assertEquals("0", $o->dv_fault_count);
        $this->assertEquals("", $o->dv_first_discovered);
        $this->assertEquals("", $o->dv_gl_account);
        $this->assertEquals("", $o->dv_install_date);
        $this->assertEquals("Live", $o->dv_install_status);
        $this->assertEquals("", $o->dv_invoice_number);
        $this->assertEquals("10.31.44.110", $o->dv_ip_address);
        $this->assertEquals("", $o->dv_justification);
        $this->assertEquals("2013-03-23 13:50:09", $o->dv_last_discovered);
        $this->assertEquals("", $o->dv_lease_id);
        $this->assertEquals("Sterling-VA-NSR-B8", $o->dv_location);
        $this->assertEquals("", $o->dv_mac_address);
        $this->assertEquals("", $o->dv_maintenance_schedule);
        $this->assertEquals("", $o->dv_managed_by);
        $this->assertEquals("HP", $o->dv_manufacturer);
        $this->assertEquals("Unknown", $o->dv_model_id);
        $this->assertEquals("HP ProLiant BL460c", $o->dv_model_number);
        $this->assertEquals("false", $o->dv_monitor);
        $this->assertEquals("stsccprdlpmail01.va.neustar.com", $o->dv_name);
        $this->assertEquals("", $o->dv_operational_status);
        $this->assertEquals("", $o->dv_order_date);
        $this->assertEquals("", $o->dv_owned_by);
        $this->assertEquals("0000006906", $o->dv_po_number);
        $this->assertEquals("", $o->dv_purchase_date);
        $this->assertEquals("", $o->dv_schedule);
        $this->assertEquals("USE10770E6", $o->dv_serial_number);
        $this->assertEquals("1/18 - MC ping replies", $o->dv_short_description);
        $this->assertEquals("false", $o->dv_skip_sync);
        $this->assertEquals("", $o->dv_start_date);
        $this->assertEquals("", $o->dv_subcategory);
        $this->assertEquals("Core Hosting", $o->dv_support_group);
        $this->assertEquals("", $o->dv_supported_by);
        $this->assertEquals("Host", $o->dv_sys_class_name);
        $this->assertEquals("zhassan", $o->dv_sys_created_by);
        $this->assertEquals("2012-12-22 00:06:11", $o->dv_sys_created_on);
        $this->assertEquals("global", $o->dv_sys_domain);
        $this->assertEquals("1ae74a40116efc407fa2908b78101b4f", $o->dv_sys_id);
        $this->assertEquals("118", $o->dv_sys_mod_count);
        $this->assertEquals("steve_stumpf", $o->dv_sys_updated_by);
        $this->assertEquals("2013-07-23 08:04:59", $o->dv_sys_updated_on);
        $this->assertEquals("", $o->dv_u_alias___cnames);
        $this->assertEquals("000000010340", $o->dv_u_asset_id);
        $this->assertEquals("", $o->dv_u_asset_receipt_date_time);
        $this->assertEquals("", $o->dv_u_b_location);
        $this->assertEquals("Systems Infrastructure", $o->dv_u_cmdb_subsystem_list);
        $this->assertEquals("", $o->dv_u_contract_line_item);
        $this->assertEquals("Sterling General Purpose", $o->dv_u_distribution_switch);
        $this->assertEquals("Production", $o->dv_u_environment);
        $this->assertEquals("", $o->dv_u_location_served);
        $this->assertEquals("2013-07-23 08:04:59", $o->dv_u_location_status_last_updated);
        $this->assertEquals("", $o->dv_u_manufacturer_date);
        $this->assertEquals("NEXT_NEU01_0000022090 _3 _1 _1_16", $o->dv_u_p_o__asset_id);
        $this->assertEquals("USE10770E6", $o->dv_u_p_o__asset_serial_number);
        $this->assertEquals("HP BL460c G7 CTO Blade Factory", $o->dv_u_p_o__item_description);
        $this->assertEquals("Sabir, Rizwan", $o->dv_u_p_o__requestor);
        $this->assertEquals("ADVANCED COMPUTER CONCEPTS, INC", $o->dv_u_p_o__vendor);
        $this->assertEquals("true", $o->dv_u_pingable_asset);
        $this->assertEquals("", $o->dv_u_po_line_item_number);
        $this->assertEquals("", $o->dv_u_po_receipt_status);
        $this->assertEquals("", $o->dv_vendor);
        $this->assertEquals("", $o->dv_warranty_expiration);
        $this->assertEquals("0", $o->fault_count);
        $this->assertEquals("", $o->first_discovered);
        $this->assertEquals("", $o->gl_account);
        $this->assertEquals("", $o->install_date);
        $this->assertEquals("110", $o->install_status);
        $this->assertEquals("", $o->invoice_number);
        $this->assertEquals("10.31.44.110", $o->ip_address);
        $this->assertEquals("", $o->justification);
        $this->assertEquals("2013-03-23 17:50:09", $o->last_discovered);
        $this->assertEquals("", $o->lease_id);
        $this->assertEquals("252770b80a0a3cac01a23e2b410dd37d", $o->location);
        $this->assertEquals("", $o->mac_address);
        $this->assertEquals("", $o->maintenance_schedule);
        $this->assertEquals("", $o->managed_by);
        $this->assertEquals("255b48640a0a3cac013f4f5866d7b7b8", $o->manufacturer);
        $this->assertEquals("223216f2e09530002bd5a432afdb809d", $o->model_id);
        $this->assertEquals("HP ProLiant BL460c", $o->model_number);
        $this->assertEquals("false", $o->monitor);
        $this->assertEquals("stsccprdlpmail01.va.neustar.com", $o->name);
        $this->assertEquals("0", $o->operational_status);
        $this->assertEquals("", $o->order_date);
        $this->assertEquals("", $o->owned_by);
        $this->assertEquals("0000006906", $o->po_number);
        $this->assertEquals("", $o->purchase_date);
        $this->assertEquals("", $o->schedule);
        $this->assertEquals("USE10770E6", $o->serial_number);
        $this->assertEquals("1/18 - MC ping replies", $o->short_description);
        $this->assertEquals("false", $o->skip_sync);
        $this->assertEquals("", $o->start_date);
        $this->assertEquals("", $o->subcategory);
        $this->assertEquals("554defc10a0a3cab00716149bea67674", $o->support_group);
        $this->assertEquals("", $o->supported_by);
        $this->assertEquals("cmdb_ci_server", $o->sys_class_name);
        $this->assertEquals("zhassan", $o->sys_created_by);
        $this->assertEquals("2012-12-22 05:06:11", $o->sys_created_on);
        $this->assertEquals("global", $o->sys_domain);
        $this->assertEquals("1ae74a40116efc407fa2908b78101b4f", $o->sys_id);
        $this->assertEquals("118", $o->sys_mod_count);
        $this->assertEquals("steve_stumpf", $o->sys_updated_by);
        $this->assertEquals("2013-07-23 12:04:59", $o->sys_updated_on);
        $this->assertEquals("", $o->u_alias___cnames);
        $this->assertEquals("000000010340", $o->u_asset_id);
        $this->assertEquals("", $o->u_asset_receipt_date_time);
        $this->assertEquals("", $o->u_b_location);
        $this->assertEquals("f432116a99e93000ad50086852dd8021", $o->u_cmdb_subsystem_list);
        $this->assertEquals("", $o->u_contract_line_item);
        $this->assertEquals("Sterling General Purpose", $o->u_distribution_switch);
        $this->assertEquals("5", $o->u_environment);
        $this->assertEquals("", $o->u_location_served);
        $this->assertEquals("2013-07-23 12:04:59", $o->u_location_status_last_updated);
        $this->assertEquals("", $o->u_manufacturer_date);
        $this->assertEquals("NEXT_NEU01_0000022090 _3 _1 _1_16", $o->u_p_o__asset_id);
        $this->assertEquals("USE10770E6", $o->u_p_o__asset_serial_number);
        $this->assertEquals("HP BL460c G7 CTO Blade Factory", $o->u_p_o__item_description);
        $this->assertEquals("Sabir, Rizwan", $o->u_p_o__requestor);
        $this->assertEquals("ADVANCED COMPUTER CONCEPTS, INC", $o->u_p_o__vendor);
        $this->assertEquals("true", $o->u_pingable_asset);
        $this->assertEquals("", $o->u_po_line_item_number);
        $this->assertEquals("", $o->u_po_receipt_status);
        $this->assertEquals("", $o->vendor);
        $this->assertEquals("", $o->warranty_expiration);
    }
}
