<?php
/*******************************************************************************
 *
 * $Id: CMDBSubsystemTableTest.php 79057 2013-09-19 18:12:14Z rcallaha $
 * $Date: 2013-09-19 14:12:14 -0400 (Thu, 19 Sep 2013) $
 * $Author: rcallaha $
 * $Revision: 79057 $
 * $HeadURL: https://svn.ultradns.net/svn/sts_tools/sts-lib/trunk/test/CMDB/CMDBSubsystemTableTest.php $
 *
 *******************************************************************************
 */

class CMDBSubsystemTableTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var STS\CMDB\CMDBSubsystemTable
     */
    protected $objectTable;

    protected $testSysId = "9432dd2a99e93000ad50086852dd809a";
    protected $testName = "WMRS";
    protected $testBusServId = "42677e770a0a3cab011393dc0ee763fb";

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->objectTable = new StubCMDBSubsystemTable();
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        unset($this->objectTable);
    }

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
     * @covers: \STS\CMDB\CMDBSubsystemTable::setFormat
     * @covers: \STS\CMDB\CMDBSubsystemTable::getFormat
     */
    public function testSetFormat()
    {
        $this->objectTable->setFormat("JSON");
        $format = $this->objectTable->getFormat();
        $this->assertEquals("JSON", $format);
    }

    /**
     * @covers: \STS\CMDB\CMDBSubsystemTable::setLogLevel
     * @covers: \STS\CMDB\CMDBSubsystemTable::getLogLevel
     */
    public function testLogLevel()
    {
        $this->objectTable->setLogLevel(\STS\UTIL\SysLog::NOTICE);
        $logLevel = $this->objectTable->getLogLevel();
        $this->assertEquals(\STS\UTIL\SysLog::NOTICE, $logLevel);
    }

    /**
     * @covers STS\CMDB\CMDBSubsystemTable::getById
     * @covers STS\CMDB\CMDBSubsystemTable::getBySysId
     */
    public function testGetById()
    {
        $subsystem = $this->objectTable->getById($this->testSysId);
        $this->checkClassProperties($subsystem);

        $subsystem = $this->objectTable->getById($this->testSysId, $raw=true);
        $this->checkObjectProperties($subsystem);
    }

    /**
     * @covers STS\CMDB\CMDBSubsystemTable::getBySysId
     */
    public function testGetBySysId()
    {
        $subsystem = $this->objectTable->getBySysId($this->testSysId);
        $this->checkClassProperties($subsystem);

        $subsystem = $this->objectTable->getBySysId($this->testSysId, $raw=true);
        $this->checkObjectProperties($subsystem);
    }

    /**
     * @covers \STS\CMDB\CMDBSubsystemTable::getByName
     */
    public function testGetByName()
    {
        $subsystem = $this->objectTable->getByName($this->testName);
        $this->checkClassProperties($subsystem);

        $subsystem = $this->objectTable->getByName($this->testName, $raw=true);
        $this->checkObjectProperties($subsystem);
    }

    /**
     * @covers: \STS\CMDB\CMDBSubsystemTable::getByBusinessServiceId
     */
    public function testGetByBusinessServiceId()
    {
        $subsystems = $this->objectTable->getByBusinessServiceId($this->testBusServId);
        $this->assertInternalType('array', $subsystems);
        $this->assertEquals(5, count($subsystems));
        $s = $subsystems[0];
        $this->checkClassProperties($s);
    }

    /**
     * @covers: \STS\CMDB\CMDBSubsystemTable::update
     * @covers: \STS\CMDB\CMDBSubsystemTable::updateByJson
     */
    public function testUpdate()
    {
        $subsystem = $this->objectTable->getByName($this->testName);

        // first no change
        $subsystem = $this->objectTable->update($subsystem);
        $this->assertInstanceOf('\STS\CMDB\CMDBSubsystem', $subsystem);
        $this->assertEquals($this->testName, $subsystem->getName());

        // since there were no changes, the last history entry should be the query url
        $this->assertEquals('https://neustartest.service-now.com/u_subsystem.do?JSON&displayvalue=all&sysparm_action=getRecords&sysparm_query=name%3DWMRS',
            $this->objectTable->getQueryHistory());

        // change name
        $subsystem->setName("fred");
        $subsystem = $this->objectTable->update($subsystem);
        $this->assertInstanceOf('\STS\CMDB\CMDBSubsystem', $subsystem);
        $this->assertEquals($this->testName, $subsystem->getName());

        // this time there are changes so update is called. The last query will be the get to query the CI after the update
        // so we need to go back 2 queries to test that the URL was correct. Same for JSON
        $this->assertEquals('https://neustartest.service-now.com/u_subsystem.do?JSON&sysparm_action=update&sysparm_query=sys_id=9432dd2a99e93000ad50086852dd809a',
            $this->objectTable->getQueryHistory(2));
        $this->assertEquals('{"name":"fred"}', $this->objectTable->getJsonHistory(2));
    }

    /**
     * Checks all the CMDBSubsystem properties
     *
     * @param \STS\CMDB\CMDBSubsystem $s
     */
    public function checkClassProperties(\STS\CMDB\CMDBSubsystem $s)
    {
        $this->assertInstanceOf('STS\CMDB\CMDBSubsystem', $s);

        $this->assertEquals("9432dd2a99e93000ad50086852dd809a", $s->getSysId());
        $this->assertEquals("WMRS", $s->getName());
        $this->assertEquals("Operating", $s->getOperationalStatus());
        $this->assertEquals("7", $s->getOperationalStatusId());
        $this->assertEquals("WMRS", $s->getBusinessService());
        $this->assertEquals("42677e770a0a3cab011393dc0ee763fb", $s->getBusinessServiceId());
        $this->assertEquals("Rob Coffman", $s->getOwningSupportManager());
        $this->assertEquals("d6117dbc0a0a3cac0096c937f8c4a08d", $s->getOwningSupportManagerId());
        $this->assertEquals("ENUM OPS", $s->getOperationsSupportGroup());
        $this->assertEquals("690347b27bda9080bba99ff39b4d4d18", $s->getOperationsSupportGroupId());
        $this->assertEquals("", $s->getSystemsAdminManager());
        $this->assertEquals("", $s->getSystemsAdminManagerId());
        $this->assertEquals("ENUM OPS", $s->getSystemsAdminGroup());
        $this->assertEquals("690347b27bda9080bba99ff39b4d4d18", $s->getSystemsAdminGroupId());
        $this->assertEquals("Rob Coffman", $s->getCmDirector());
        $this->assertEquals("d6117dbc0a0a3cac0096c937f8c4a08d", $s->getCmDirectorId());
        $this->assertEquals("Production - Consumer Facing", $s->getSubsystemCategory());
        $this->assertEquals("High", $s->getServiceBusinessClass());
        $this->assertEquals("Bronze", $s->getServiceSlaClass());
        $this->assertEquals("emartin", $s->getSysCreatedBy());
        $this->assertEquals("2011-10-19 21:27:28", $s->getSysCreatedOn());
        $this->assertEquals("iculpepp", $s->getSysUpdatedBy());
        $this->assertEquals("2013-08-06 14:14:40", $s->getSysUpdatedOn());
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
        $this->assertEquals("", $o->asset_tag);
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
        $this->assertEquals("", $o->delivery_date);
        $this->assertEquals("", $o->department);
        $this->assertEquals("", $o->discovery_source);
        $this->assertEquals("", $o->dns_domain);
        $this->assertEquals("", $o->due);
        $this->assertEquals("", $o->due_in);
        $this->assertEquals("", $o->dv_asset);
        $this->assertEquals("", $o->dv_asset_tag);
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
        $this->assertEquals("", $o->dv_delivery_date);
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
        $this->assertEquals("", $o->dv_ip_address);
        $this->assertEquals("", $o->dv_justification);
        $this->assertEquals("", $o->dv_last_discovered);
        $this->assertEquals("", $o->dv_lease_id);
        $this->assertEquals("", $o->dv_location);
        $this->assertEquals("", $o->dv_mac_address);
        $this->assertEquals("", $o->dv_maintenance_schedule);
        $this->assertEquals("", $o->dv_managed_by);
        $this->assertEquals("", $o->dv_manufacturer);
        $this->assertEquals("", $o->dv_model_id);
        $this->assertEquals("", $o->dv_model_number);
        $this->assertEquals("false", $o->dv_monitor);
        $this->assertEquals("WMRS", $o->dv_name);
        $this->assertEquals("Operating", $o->dv_operational_status);
        $this->assertEquals("", $o->dv_order_date);
        $this->assertEquals("", $o->dv_owned_by);
        $this->assertEquals("", $o->dv_po_number);
        $this->assertEquals("", $o->dv_purchase_date);
        $this->assertEquals("", $o->dv_schedule);
        $this->assertEquals("", $o->dv_serial_number);
        $this->assertEquals("", $o->dv_short_description);
        $this->assertEquals("false", $o->dv_skip_sync);
        $this->assertEquals("", $o->dv_start_date);
        $this->assertEquals("", $o->dv_subcategory);
        $this->assertEquals("", $o->dv_support_group);
        $this->assertEquals("", $o->dv_supported_by);
        $this->assertEquals("subsystem", $o->dv_sys_class_name);
        $this->assertEquals("emartin", $o->dv_sys_created_by);
        $this->assertEquals("2011-10-19 17:27:28", $o->dv_sys_created_on);
        $this->assertEquals("global", $o->dv_sys_domain);
        $this->assertEquals("9432dd2a99e93000ad50086852dd809a", $o->dv_sys_id);
        $this->assertEquals("8", $o->dv_sys_mod_count);
        $this->assertEquals("iculpepp", $o->dv_sys_updated_by);
        $this->assertEquals("2013-08-06 10:14:40", $o->dv_sys_updated_on);
        $this->assertEquals("", $o->dv_u_alias___cnames);
        $this->assertEquals("", $o->dv_u_approval_group);
        $this->assertEquals("", $o->dv_u_asset_id);
        $this->assertEquals("", $o->dv_u_asset_receipt_date_time);
        $this->assertEquals("", $o->dv_u_b_location);
        $this->assertEquals("WMRS", $o->dv_u_business_service);
        $this->assertEquals("Rob Coffman", $o->dv_u_cm_director);
        $this->assertEquals("", $o->dv_u_cmdb_subsystem_list);
        $this->assertEquals("", $o->dv_u_colo_physical_acl);
        $this->assertEquals("", $o->dv_u_contract_line_item);
        $this->assertEquals("", $o->dv_u_description);
        $this->assertEquals("", $o->dv_u_development_group);
        $this->assertEquals("", $o->dv_u_distribution_switch);
        $this->assertEquals("", $o->dv_u_email);
        $this->assertEquals("", $o->dv_u_environment);
        $this->assertEquals("false", $o->dv_u_executive_aprrover_ecr);
        $this->assertEquals("", $o->dv_u_first_responders_group);
        $this->assertEquals("", $o->dv_u_first_responders_page_addres);
        $this->assertEquals("", $o->dv_u_location_served);
        $this->assertEquals("", $o->dv_u_location_status_last_updated);
        $this->assertEquals("", $o->dv_u_long_name);
        $this->assertEquals("", $o->dv_u_maintenance_window);
        $this->assertEquals("", $o->dv_u_manufacturer_date);
        $this->assertEquals("false", $o->dv_u_off_shore_eligible);
        $this->assertEquals("ENUM OPS", $o->dv_u_oncall_appsupport);
        $this->assertEquals("", $o->dv_u_oncall_dba);
        $this->assertEquals("", $o->dv_u_oncall_network);
        $this->assertEquals("", $o->dv_u_oncall_systems);
        $this->assertEquals("", $o->dv_u_operations_subject_matter_ex);
        $this->assertEquals("ENUM OPS", $o->dv_u_operations_support_group);
        $this->assertEquals("Rob Coffman", $o->dv_u_owning_support_manager);
        $this->assertEquals("", $o->dv_u_p_o__asset_id);
        $this->assertEquals("", $o->dv_u_p_o__asset_serial_number);
        $this->assertEquals("", $o->dv_u_p_o__item_description);
        $this->assertEquals("", $o->dv_u_p_o__requestor);
        $this->assertEquals("", $o->dv_u_p_o__vendor);
        $this->assertEquals("false", $o->dv_u_pingable_asset);
        $this->assertEquals("", $o->dv_u_po_line_item_number);
        $this->assertEquals("", $o->dv_u_po_receipt_status);
        $this->assertEquals("", $o->dv_u_qa_group);
        $this->assertEquals("High", $o->dv_u_service_business_class);
        $this->assertEquals("Bronze", $o->dv_u_service_sla_class);
        $this->assertEquals("Production - Consumer Facing", $o->dv_u_subsystem_category);
        $this->assertEquals("ENUM OPS", $o->dv_u_system_admin_group);
        $this->assertEquals("", $o->dv_u_systems_administration_manag);
        $this->assertEquals("false", $o->dv_u_virtualizaton_eligibilty);
        $this->assertEquals("", $o->dv_vendor);
        $this->assertEquals("", $o->dv_warranty_expiration);
        $this->assertEquals("0", $o->fault_count);
        $this->assertEquals("", $o->first_discovered);
        $this->assertEquals("", $o->gl_account);
        $this->assertEquals("", $o->install_date);
        $this->assertEquals("110", $o->install_status);
        $this->assertEquals("", $o->invoice_number);
        $this->assertEquals("", $o->ip_address);
        $this->assertEquals("", $o->justification);
        $this->assertEquals("", $o->last_discovered);
        $this->assertEquals("", $o->lease_id);
        $this->assertEquals("", $o->location);
        $this->assertEquals("", $o->mac_address);
        $this->assertEquals("", $o->maintenance_schedule);
        $this->assertEquals("", $o->managed_by);
        $this->assertEquals("", $o->manufacturer);
        $this->assertEquals("", $o->model_id);
        $this->assertEquals("", $o->model_number);
        $this->assertEquals("false", $o->monitor);
        $this->assertEquals("WMRS", $o->name);
        $this->assertEquals("7", $o->operational_status);
        $this->assertEquals("", $o->order_date);
        $this->assertEquals("", $o->owned_by);
        $this->assertEquals("", $o->po_number);
        $this->assertEquals("", $o->purchase_date);
        $this->assertEquals("", $o->schedule);
        $this->assertEquals("", $o->serial_number);
        $this->assertEquals("", $o->short_description);
        $this->assertEquals("false", $o->skip_sync);
        $this->assertEquals("", $o->start_date);
        $this->assertEquals("", $o->subcategory);
        $this->assertEquals("", $o->support_group);
        $this->assertEquals("", $o->supported_by);
        $this->assertEquals("u_subsystem", $o->sys_class_name);
        $this->assertEquals("emartin", $o->sys_created_by);
        $this->assertEquals("2011-10-19 21:27:28", $o->sys_created_on);
        $this->assertEquals("global", $o->sys_domain);
        $this->assertEquals("9432dd2a99e93000ad50086852dd809a", $o->sys_id);
        $this->assertEquals("8", $o->sys_mod_count);
        $this->assertEquals("iculpepp", $o->sys_updated_by);
        $this->assertEquals("2013-08-06 14:14:40", $o->sys_updated_on);
        $this->assertEquals("", $o->u_alias___cnames);
        $this->assertEquals("", $o->u_approval_group);
        $this->assertEquals("", $o->u_asset_id);
        $this->assertEquals("", $o->u_asset_receipt_date_time);
        $this->assertEquals("", $o->u_b_location);
        $this->assertEquals("42677e770a0a3cab011393dc0ee763fb", $o->u_business_service);
        $this->assertEquals("d6117dbc0a0a3cac0096c937f8c4a08d", $o->u_cm_director);
        $this->assertEquals("", $o->u_cmdb_subsystem_list);
        $this->assertEquals("", $o->u_colo_physical_acl);
        $this->assertEquals("", $o->u_contract_line_item);
        $this->assertEquals("", $o->u_description);
        $this->assertEquals("", $o->u_development_group);
        $this->assertEquals("", $o->u_distribution_switch);
        $this->assertEquals("", $o->u_email);
        $this->assertEquals("0", $o->u_environment);
        $this->assertEquals("false", $o->u_executive_aprrover_ecr);
        $this->assertEquals("", $o->u_first_responders_group);
        $this->assertEquals("", $o->u_first_responders_page_addres);
        $this->assertEquals("", $o->u_location_served);
        $this->assertEquals("", $o->u_location_status_last_updated);
        $this->assertEquals("", $o->u_long_name);
        $this->assertEquals("", $o->u_maintenance_window);
        $this->assertEquals("", $o->u_manufacturer_date);
        $this->assertEquals("false", $o->u_off_shore_eligible);
        $this->assertEquals("690347b27bda9080bba99ff39b4d4d18", $o->u_oncall_appsupport);
        $this->assertEquals("", $o->u_oncall_dba);
        $this->assertEquals("", $o->u_oncall_network);
        $this->assertEquals("", $o->u_oncall_systems);
        $this->assertEquals("", $o->u_operations_subject_matter_ex);
        $this->assertEquals("690347b27bda9080bba99ff39b4d4d18", $o->u_operations_support_group);
        $this->assertEquals("d6117dbc0a0a3cac0096c937f8c4a08d", $o->u_owning_support_manager);
        $this->assertEquals("", $o->u_p_o__asset_id);
        $this->assertEquals("", $o->u_p_o__asset_serial_number);
        $this->assertEquals("", $o->u_p_o__item_description);
        $this->assertEquals("", $o->u_p_o__requestor);
        $this->assertEquals("", $o->u_p_o__vendor);
        $this->assertEquals("false", $o->u_pingable_asset);
        $this->assertEquals("", $o->u_po_line_item_number);
        $this->assertEquals("", $o->u_po_receipt_status);
        $this->assertEquals("", $o->u_qa_group);
        $this->assertEquals("High", $o->u_service_business_class);
        $this->assertEquals("Bronze", $o->u_service_sla_class);
        $this->assertEquals("Production - Consumer Facing", $o->u_subsystem_category);
        $this->assertEquals("690347b27bda9080bba99ff39b4d4d18", $o->u_system_admin_group);
        $this->assertEquals("", $o->u_systems_administration_manag);
        $this->assertEquals("false", $o->u_virtualizaton_eligibilty);
        $this->assertEquals("", $o->vendor);
        $this->assertEquals("", $o->warranty_expiration);
    }
}
