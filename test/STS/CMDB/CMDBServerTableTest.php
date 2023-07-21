<?php
/*******************************************************************************
 *
 * $Id: CMDBServerTableTest.php 79070 2013-09-19 21:23:03Z rcallaha $
 * $Date: 2013-09-19 17:23:03 -0400 (Thu, 19 Sep 2013) $
 * $Author: rcallaha $
 * $Revision: 79070 $
 * $HeadURL: https://svn.ultradns.net/svn/sts_tools/sts-lib/trunk/test/CMDB/CMDBServerTableTest.php $
 *
 *******************************************************************************
 */

class CMDBServerTableTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var STS\CMDB\CMDBServerTable
     */
    protected $objectTable;

    protected $testSysId = "1ae74a40116efc407fa2908b78101b4f";
    protected $testName = "stsccprdlpmail01.va.neustar.com";
    protected $testSN = "USE10770E6";
    protected $testSubSysId = "f432116a99e93000ad50086852dd8021";

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->objectTable = new StubCMDBServerTable();
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
     * @covers: \STS\CMDB\CMDBServerTable::getNameMapping
     * @covers: \STS\CMDB\CMDBServerTable::getReverseNameMapping
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
     * @covers: \STS\CMDB\CMDBServerTable::setFormat
     * @covers: \STS\CMDB\CMDBServerTable::getFormat
     */
    public function testSetFormat()
    {
        $this->objectTable->setFormat("JSON");
        $format = $this->objectTable->getFormat();
        $this->assertEquals("JSON", $format);
    }

    /**
     * @covers: \STS\CMDB\CMDBServerTable::setLogLevel
     * @covers: \STS\CMDB\CMDBServerTable::getLogLevel
     */
    public function testLogLevel()
    {
        $this->objectTable->setLogLevel(\STS\UTIL\SysLog::NOTICE);
        $logLevel = $this->objectTable->getLogLevel();
        $this->assertEquals(\STS\UTIL\SysLog::NOTICE, $logLevel);
    }

    /**
     * @covers STS\CMDB\CMDBServerTable::getById
     */
    public function testGetById()
    {
        $object = $this->objectTable->getById($this->testSysId);
        $this->checkClassProperties($object);

        $object = $this->objectTable->getById($this->testSysId, $raw=true);
        $this->checkObjectProperties($object);
    }

    /**
     * @covers STS\CMDB\CMDBServerTable::getBySysId
     * @covers STS\CMDB\CMDBServerTable::_set
     */
    public function testGetBySysId()
    {
        $object = $this->objectTable->getBySysId($this->testSysId);
        $this->checkClassProperties($object);

        $object = $this->objectTable->getBySysId($this->testSysId, $raw=true);
        $this->checkObjectProperties($object);
    }

    /**
     * @covers \STS\CMDB\CMDBServerTable::getByName
     */
    public function testGetByName()
    {
        $object = $this->objectTable->getByName($this->testName);
        $this->checkClassProperties($object);

        $object = $this->objectTable->getByName($this->testName, $anyStatus=true);
        $this->checkClassProperties($object);

        $object = $this->objectTable->getByName($this->testName, $anyStatus=false, $raw=true);
        $this->checkObjectProperties($object);
    }

    /**
     * @covers \STS\CMDB\CMDBServerTable::getByNameLike
     */
    public function testGetByNameLike()
    {
        $object = $this->objectTable->getByNameLike($this->testName);
        $this->checkClassProperties($object);

        $object = $this->objectTable->getByNameLike($this->testName, $anyStatus=true);
        $this->checkClassProperties($object);
    }

    /**
     * @covers \STS\CMDB\CMDBServerTable::getByNameStartsWith
     */
    public function testGetByNameStartsWith()
    {
        $object = $this->objectTable->getByNameStartsWith($this->testName);
        $this->checkClassProperties($object);

        $object = $this->objectTable->getByNameStartsWith($this->testName, $anyStatus=true);
        $this->checkClassProperties($object);

        $object = $this->objectTable->getByNameStartsWith($this->testName, $anyStatus=false, $raw=true);
        $this->checkObjectProperties($object);
    }

    /**
     * @covers \STS\CMDB\CMDBServerTable::getAllByNameLike
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
     * @covers \STS\CMDB\CMDBServerTable::getBySerialNumber
     */
    public function testGetBySerialNumber()
    {
        $object = $this->objectTable->getBySerialNumber($this->testSN);
        $this->checkClassProperties($object);

        $object = $this->objectTable->getBySerialNumber($this->testSN, $anyStatus=true);
        $this->checkClassProperties($object);
    }

    /**
     * @covers \STS\CMDB\CMDBServerTable::getAllBySerialNumber
     */
    public function testGetAllBySerialNumber()
    {
        $objects = $this->objectTable->getAllBySerialNumber($this->testSN);
        $this->assertInternalType('array', $objects);
        $this->assertEquals(1, count($objects));
        $o = $objects[0];
        $this->checkClassProperties($o);
    }

    /**
     * @covers: \STS\CMDB\CMDBServerTable::getBySubsystemId
     */
    public function testGetBySubsystemId()
    {
        $objects = $this->objectTable->getBySubsystemId($this->testSubSysId);
        $this->assertInternalType('array', $objects);
        $this->assertEquals(1, count($objects));
        $o = $objects[0];
        $this->checkClassProperties($o);
    }

    /**
     * @covers: \STS\CMDB\CMDBServerTable::getByBusinessServicesArray
     */
    public function testGetByBusinessServicesArray()
    {
        $objects = $this->objectTable->getByBusinessServicesArray(array("CARE", "WMRS"));
        $this->assertInternalType('array', $objects);
        $this->assertEquals(29, count($objects));
        foreach ($objects as $o) {
            $this->assertInstanceOf('STS\CMDB\CMDBServer', $o);
            $this->assertTrue($o->getName() != "" && $o->getSysId() != "" && strlen($o->getSysId()) == 32);
        }
    }

    /**
     * @covers: \STS\CMDB\CMDBServerTable::getByQueryString
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
     * @covers: \STS\CMDB\CMDBServerTable::update
     * @covers: \STS\CMDB\CMDBServerTable::updateByJson
     */
    public function testUpdate()
    {
        $object = $this->objectTable->getByName($this->testName);

        // first no change
        $object = $this->objectTable->update($object);
        $this->assertInstanceOf('\STS\CMDB\CMDBServer', $object);
        $this->assertEquals($this->testName, $object->getName());

        // since there were no changes, the last history entry should be the query url
        $this->assertEquals('https://neustartest.service-now.com/cmdb_ci_server.do?JSON&displayvalue=all&sysparm_action=getRecords&sysparm_query=sys_class_name%21%3D%5Einstall_statusNOT%20IN117%2C1501%5Ename%3Dstsccprdlpmail01.va.neustar.com',
            $this->objectTable->getQueryHistory());

        // change name
        $object->setName("fred");
        $object = $this->objectTable->update($object);
        $this->assertInstanceOf('\STS\CMDB\CMDBServer', $object);
        $this->assertEquals($this->testName, $object->getName());

        // this time there are changes so update is called. The last query will be the get to query the CI after the update
        // so we need to go back 2 queries to test that the URL was correct. Same for JSON
        $this->assertEquals('https://neustartest.service-now.com/cmdb_ci_server.do?JSON&sysparm_action=update&sysparm_query=sys_id=1ae74a40116efc407fa2908b78101b4f',
            $this->objectTable->getQueryHistory(2));
        $this->assertEquals('{"name":"fred"}', $this->objectTable->getJsonHistory(2));
    }

    /**
     * @covers: \STS\CMDB\CMDBServerTable::create
     * @covers: \STS\CMDB\CMDBServerTable::createByJson
     */
    public function testCreate()
    {
        $object = $this->objectTable->getByName($this->testName);

        // first no change
        $object = $this->objectTable->create($object);
        $this->assertInstanceOf('\STS\CMDB\CMDBServer', $object);
        $this->assertEquals($this->testName, $object->getName());

        // since there were no changes, the last history entry should be the query url
        $this->assertEquals('https://neustartest.service-now.com/cmdb_ci_server.do?JSON&displayvalue=all&sysparm_action=getRecords&sysparm_query=sys_class_name%21%3D%5Einstall_statusNOT%20IN117%2C1501%5Ename%3Dstsccprdlpmail01.va.neustar.com',
            $this->objectTable->getQueryHistory());

        // change name
        $object->setName("fred");
        $object = $this->objectTable->create($object);
        $this->assertInstanceOf('\STS\CMDB\CMDBServer', $object);
        $this->assertEquals($this->testName, $object->getName());

        $this->assertEquals('https://neustartest.service-now.com/cmdb_ci_server.do?JSON&sysparm_action=insert',
            $this->objectTable->getQueryHistory());
        $this->assertEquals('{"name":"fred"}', $this->objectTable->getJsonHistory());
    }

    /**
     * @covers: \STS\CMDB\CMDBServerTable::hostLookup
     */
    public function testHostLookup()
    {
        // test fqdn
        $object = $this->objectTable->hostLookup($this->testName);
        $this->checkClassProperties($object);

        // test hostname with "." added
        $object = $this->objectTable->hostLookup("stsccprdlpmail01");
        $this->checkClassProperties($object);

        // test host not found
        $object = $this->objectTable->hostLookup("fred.ops.neustar.com");
        $this->assertEquals(null, $object->getSysId());
    }

    /**
     * Checks all the CMDBServer properties
     *
     * @param \STS\CMDB\CMDBServer $o
     */
    public function checkClassProperties(\STS\CMDB\CMDBServer $o)
    {
        $this->assertInstanceOf('STS\CMDB\CMDBServer', $o);

        $this->assertEquals("1ae74a40116efc407fa2908b78101b4f", $o->getSysId());
        $this->assertEquals("cmdb_ci_server", $o->getSysClassName());
        $this->assertEquals("stsccprdlpmail01.va.neustar.com", $o->getName());
        $this->assertEquals("", $o->getClassification());
        $this->assertEquals("USE10770E6", $o->getSerialNumber());
        $this->assertEquals("147906", $o->getAssetTag());
        $this->assertEquals("", $o->getBusinessService());
        $this->assertEquals("b99cc4c50a0a3cac012e864fe7e57491", $o->getBusinessServiceId());
        $this->assertEquals("", $o->getBusinessServices());
        $this->assertEquals("b99cc4c50a0a3cac012e864fe7e57491", $o->getBusinessServicesIds());
        $this->assertEquals("", $o->getSubsystemList());
        $this->assertEquals("f432116a99e93000ad50086852dd8021", $o->getSubsystemListId());
        $this->assertEquals("", $o->getInstallStatus());
        $this->assertEquals("110", $o->getInstallStatusId());
        $this->assertEquals("", $o->getFirewallStatus());
        $this->assertEquals("", $o->getHardwareStatus());
        $this->assertEquals("", $o->getOperationalStatus());
        $this->assertEquals("", $o->getEnvironment());
        $this->assertEquals("5", $o->getEnvironmentId());
        $this->assertEquals("290128a90a0a3cac00b2fb9125f68df9", $o->getHostedOnId());
        $this->assertEquals("", $o->getHostedOn());
        $this->assertEquals("Sterling General Purpose", $o->getDistributionSwitch());
        $this->assertEquals("", $o->getHostType());
        $this->assertEquals("3", $o->getHostTypeId());
        $this->assertEquals("", $o->getLocationType());
        $this->assertEquals("Neustar Data Center", $o->getLocationTypeId());
        $this->assertEquals("252770b80a0a3cac01a23e2b410dd37d", $o->getLocationId());
        $this->assertEquals("", $o->getLocation());
        $this->assertEquals("054214ca1081ac04bba92981f5cb8967", $o->getRackId());
        $this->assertEquals("", $o->getRack());
        $this->assertEquals("0", $o->getNumberOfRackUnits());
        $this->assertEquals("14", $o->getRackPosition());
        $this->assertEquals("10.31.44.110", $o->getIpAddress());
        $this->assertEquals("b5c176e57b925c40bba99ff39b4d4db1", $o->getCpuManufacturerId());
        $this->assertEquals("", $o->getCpuManufacturer());
        $this->assertEquals("2", $o->getCpuCount());
        $this->assertEquals("8", $o->getCpuCoreCount());
        $this->assertEquals("", $o->getCpuName());
        $this->assertEquals("2.47", $o->getCpuSpeed());
        $this->assertEquals("Xeon E5630", $o->getCpuType());
        $this->assertEquals("255b48640a0a3cac013f4f5866d7b7b8", $o->getManufacturerId());
        $this->assertEquals("", $o->getManufacturer());
        $this->assertEquals("HP ProLiant BL460c", $o->getModelNumber());
        $this->assertEquals("94", $o->getRam());
        $this->assertEquals("Linux (Version Unrecognized)", $o->getOs());
        $this->assertEquals("5.8", $o->getOsVersion());
        $this->assertEquals("2.6.18-308.el5", $o->getOsServicePack());
        $this->assertEquals("0", $o->getWatts());
        $this->assertEquals("", $o->getComments());
        $this->assertEquals("1/18 - MC ping replies", $o->getShortDescription());
        $this->assertEquals("", $o->getMaintContractEndDate());
        $this->assertEquals("", $o->getMaintContractStartDate());
        $this->assertEquals("", $o->getLastBackupDate());
        $this->assertEquals("", $o->getBackupDirectories());
        $this->assertEquals("false", $o->getInEHealth());
        $this->assertEquals("2011-05-05 04:00:00", $o->getBiosDate());
        $this->assertEquals("", $o->getLastDdmiUpdate());
        $this->assertEquals("2013-03-23 17:50:09", $o->getLastDiscovered());
        $this->assertEquals("", $o->getPowerpathVersion());
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
        $this->assertEquals("false", $o->cd_rom);
        $this->assertEquals("0", $o->cd_speed);
        $this->assertEquals("", $o->change_control);
        $this->assertEquals("", $o->chassis_type);
        $this->assertEquals("", $o->checked_in);
        $this->assertEquals("", $o->checked_out);
        $this->assertEquals("Production", $o->classification);
        $this->assertEquals("", $o->comments);
        $this->assertEquals("", $o->company);
        $this->assertEquals("", $o->correlation_id);
        $this->assertEquals("0", $o->cost);
        $this->assertEquals("USD", $o->cost_cc);
        $this->assertEquals("", $o->cost_center);
        $this->assertEquals("8", $o->cpu_core_count);
        $this->assertEquals("2", $o->cpu_count);
        $this->assertEquals("b5c176e57b925c40bba99ff39b4d4db1", $o->cpu_manufacturer);
        $this->assertEquals("", $o->cpu_name);
        $this->assertEquals("2.47", $o->cpu_speed);
        $this->assertEquals("Xeon E5630", $o->cpu_type);
        $this->assertEquals("2011-02-23 05:00:00", $o->delivery_date);
        $this->assertEquals("", $o->department);
        $this->assertEquals("", $o->discovery_source);
        $this->assertEquals("0", $o->disk_space);
        $this->assertEquals("", $o->dns_domain);
        $this->assertEquals("", $o->dr_backup);
        $this->assertEquals("", $o->due);
        $this->assertEquals("", $o->due_in);
        $this->assertEquals("0", $o->fault_count);
        $this->assertEquals("Intranet", $o->firewall_status);
        $this->assertEquals("", $o->first_discovered);
        $this->assertEquals("", $o->floppy);
        $this->assertEquals("", $o->form_factor);
        $this->assertEquals("", $o->gl_account);
        $this->assertEquals("installed", $o->hardware_status);
        $this->assertEquals("", $o->hardware_substatus);
        $this->assertEquals("", $o->host_name);
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
        $this->assertEquals("Linux (Version Unrecognized)", $o->os);
        $this->assertEquals("0", $o->os_address_width);
        $this->assertEquals("", $o->os_domain);
        $this->assertEquals("2.6.18-308.el5", $o->os_service_pack);
        $this->assertEquals("5.8", $o->os_version);
        $this->assertEquals("", $o->owned_by);
        $this->assertEquals("0000006906", $o->po_number);
        $this->assertEquals("90ff783629fb78c0a21130d9dae3a715", $o->processor);
        $this->assertEquals("", $o->purchase_date);
        $this->assertEquals("94", $o->ram);
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
        $this->assertEquals("", $o->u_appliance_ip_address);
        $this->assertEquals("", $o->u_architecture);
        $this->assertEquals("000000010340", $o->u_asset_id);
        $this->assertEquals("", $o->u_asset_receipt_date_time);
        $this->assertEquals("", $o->u_asset_tag);
        $this->assertEquals("", $o->u_asset_type);
        $this->assertEquals("", $o->u_audit_type);
        $this->assertEquals("", $o->u_authoritative_fqdn);
        $this->assertEquals("", $o->u_authoritative_vip_address);
        $this->assertEquals("", $o->u_b_location);
        $this->assertEquals("", $o->u_backup_directories);
        $this->assertEquals("false", $o->u_backup_required);
        $this->assertEquals("", $o->u_backup_required_last_updated);
        $this->assertEquals("", $o->u_bgp_address);
        $this->assertEquals("2011-05-05 04:00:00", $o->u_bios_date);
        $this->assertEquals("", $o->u_bonded);
        $this->assertEquals("", $o->u_business_customer_name);
        $this->assertEquals("b99cc4c50a0a3cac012e864fe7e57491", $o->u_business_service);
        $this->assertEquals("b99cc4c50a0a3cac012e864fe7e57491", $o->u_business_service_s_);
        $this->assertEquals("", $o->u_cage);
        $this->assertEquals("false", $o->u_client_ldap_active);
        $this->assertEquals("f432116a99e93000ad50086852dd8021", $o->u_cmdb_subsystem_list);
        $this->assertEquals("", $o->u_colocation_provider);
        $this->assertEquals("", $o->u_contract_line_item);
        $this->assertEquals("0", $o->u_core);
        $this->assertEquals("false", $o->u_corporate_backup);
        $this->assertEquals("", $o->u_coverage_hours);
        $this->assertEquals("", $o->u_customer_public_ip_address);
        $this->assertEquals("false", $o->u_ddmi_excluded);
        $this->assertEquals("", $o->u_device_id);
        $this->assertEquals("", $o->u_dhcp_failover_set_name);
        $this->assertEquals("Sterling General Purpose", $o->u_distribution_switch);
        $this->assertEquals("5", $o->u_environment);
        $this->assertEquals("0", $o->u_free_ram);
        $this->assertEquals("", $o->u_functionality);
        $this->assertEquals("", $o->u_host_benchmark);
        $this->assertEquals("3", $o->u_host_type);
        $this->assertEquals("290128a90a0a3cac00b2fb9125f68df9", $o->u_hosted_on);
        $this->assertEquals("", $o->u_hosting_provider);
        $this->assertEquals("false", $o->u_in_ehealth);
        $this->assertEquals("false", $o->u_in_ldap);
        $this->assertEquals("", $o->u_in_scope_for_audit);
        $this->assertEquals("false", $o->u_is_contractor);
        $this->assertEquals("false", $o->u_is_san_attached_to_host);
        $this->assertEquals("", $o->u_item_type);
        $this->assertEquals("", $o->u_kernel);
        $this->assertEquals("", $o->u_last_backup_date);
        $this->assertEquals("2013-03-25 00:30:00", $o->u_last_ddmi_update);
        $this->assertEquals("", $o->u_ldap_host_group);
        $this->assertEquals("0", $o->u_ldap_individual_count);
        $this->assertEquals("", $o->u_ldap_user_group_list);
        $this->assertEquals("", $o->u_location_served);
        $this->assertEquals("2013-07-23 12:04:59", $o->u_location_status_last_updated);
        $this->assertEquals("Neustar Data Center", $o->u_location_type);
        $this->assertEquals("0", $o->u_lun_size);
        $this->assertEquals("", $o->u_maintenance_contract_end_dat);
        $this->assertEquals("", $o->u_maintenance_contract_start_d);
        $this->assertEquals("", $o->u_manufacturer_date);
        $this->assertEquals("", $o->u_model_type);
        $this->assertEquals("", $o->u_notes);
        $this->assertEquals("0", $o->u_number_of_available_hba_slot);
        $this->assertEquals("0", $o->u_number_of_hba_cards);
        $this->assertEquals("0", $o->u_number_of_luns);
        $this->assertEquals("0", $o->u_number_of_power_supplies);
        $this->assertEquals("0", $o->u_number_rack_units);
        $this->assertEquals("", $o->u_operational_support_group_li);
        $this->assertEquals("", $o->u_operational_support_groups);
        $this->assertEquals("", $o->u_original_service);
        $this->assertEquals("NEXT_NEU01_0000022090 _3 _1 _1_16", $o->u_p_o__asset_id);
        $this->assertEquals("USE10770E6", $o->u_p_o__asset_serial_number);
        $this->assertEquals("HP BL460c G7 CTO Blade Factory", $o->u_p_o__item_description);
        $this->assertEquals("Sabir, Rizwan", $o->u_p_o__requestor);
        $this->assertEquals("ADVANCED COMPUTER CONCEPTS, INC", $o->u_p_o__vendor);
        $this->assertEquals("", $o->u_patch_status);
        $this->assertEquals("true", $o->u_pingable_asset);
        $this->assertEquals("", $o->u_po_line_item_number);
        $this->assertEquals("", $o->u_po_receipt_status);
        $this->assertEquals("", $o->u_powerpath_version);
        $this->assertEquals("", $o->u_product_number);
        $this->assertEquals("", $o->u_pwwns__host_hbas_);
        $this->assertEquals("054214ca1081ac04bba92981f5cb8967", $o->u_rack);
        $this->assertEquals("14", $o->u_rack_position);
        $this->assertEquals("", $o->u_recursive_vip_address);
        $this->assertEquals("", $o->u_refresh_date);
        $this->assertEquals("", $o->u_request_item);
        $this->assertEquals("", $o->u_resolution_sla);
        $this->assertEquals("", $o->u_response);
        $this->assertEquals("", $o->u_san_details);
        $this->assertEquals("", $o->u_server_role);
        $this->assertEquals("", $o->u_server_status);
        $this->assertEquals("", $o->u_service_list);
        $this->assertEquals("", $o->u_service_s_);
        $this->assertEquals("", $o->u_shipped_date);
        $this->assertEquals("", $o->u_shipper);
        $this->assertEquals("", $o->u_shipping_contact_email);
        $this->assertEquals("", $o->u_shipping_contact_name);
        $this->assertEquals("", $o->u_shipping_contact_phone_numbe);
        $this->assertEquals("", $o->u_ssh_nat_ip);
        $this->assertEquals("", $o->u_ssh_tunnel_port);
        $this->assertEquals("", $o->u_ssh_web_tunnel_port);
        $this->assertEquals("", $o->u_sudo_role_list);
        $this->assertEquals("", $o->u_support_contract_number);
        $this->assertEquals("", $o->u_support_contract_vendor);
        $this->assertEquals("09d5c74f29dc8144a21130d9dae3a758", $o->u_sync_update);
        $this->assertEquals("", $o->u_tracking_number);
        $this->assertEquals("false", $o->u_wait_for_return);
        $this->assertEquals("", $o->u_warranty_end_date);
        $this->assertEquals("0", $o->u_watts);
        $this->assertEquals("Production", $o->used_for);
        $this->assertEquals("", $o->vendor);
        $this->assertEquals("false", $o->virtual);
        $this->assertEquals("", $o->warranty_expiration);
    }
}
