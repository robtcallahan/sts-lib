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

class CMDBBusinessServiceTableTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var STS\CMDB\CMDBBusinessServiceTable
     */
    protected $objectTable;

    protected $testSysId = "b99cc4520a0a3cac01230445a15a7d7f";
    protected $testName = "NPAC";

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->objectTable = new StubCMDBBusinessServiceTable;
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
     * @covers STS\CMDB\CMDBBusinessServiceTable::getNameMapping
     * @covers STS\CMDB\CMDBBusinessServiceTable::getReverseNameMapping
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
     * @covers STS\CMDB\CMDBBusinessServiceTable::getById
     */
    public function testGetById()
    {
        $bs = $this->objectTable->getById($this->testSysId);
        $this->checkClassProperties($bs);

        $bs = $this->objectTable->getById($this->testSysId, $raw=true);
        $this->checkObjectProperties($bs);
    }

    /**
     * @covers STS\CMDB\CMDBBusinessServiceTable::getBySysId
     * @covers STS\CMDB\CMDBServerTable::_set
     */
    public function testGetBySysId()
    {
        $bs = $this->objectTable->getBySysId($this->testSysId);
        $this->checkClassProperties($bs);

        $bs = $this->objectTable->getBySysId($this->testSysId, $raw=true);
        $this->checkObjectProperties($bs);
    }

    /**
     * @covers STS\CMDB\CMDBBusinessServiceTable::getByName
     */
    public function testGetByName()
    {
        $bs = $this->objectTable->getByName($this->testName);
        $this->checkClassProperties($bs);

        $bs = $this->objectTable->getByName($this->testName, $raw=true);
        $this->checkObjectProperties($bs);
    }

    /**
     * @covers STS\CMDB\CMDBBusinessServiceTable::getByNameLike
     */
    public function testGetByNameLike()
    {
        $bss = $this->objectTable->getByNameLike($this->testName);
        $this->assertInternalType("array", $bss);
        $this->checkClassProperties($bss[0]);

        $bss = $this->objectTable->getByNameLike($this->testName, $raw=true);
        $this->assertInternalType("array", $bss);
        $this->checkObjectProperties($bss[0]);
    }

    /**
     * @covers STS\CMDB\CMDBBusinessServiceTable::getAll
     */
    public function testGetAll()
    {
        $bss = $this->objectTable->getAll();
        $this->assertEquals(3, count($bss));
    }

    /**
     * @covers STS\CMDB\CMDBBusinessServiceTable::getByQueryString
     */
    public function testGetByQueryString()
    {
        $bss = $this->objectTable->getByQueryString("install_statusNOT IN117,1501^nameLIKE" . $this->testName);
        $this->assertInternalType('array', $bss);
        $this->assertEquals(1, count($bss));
        $bs = $bss[0];
        $this->checkClassProperties($bs);
    }

    /**
     * @covers STS\CMDB\CMDBBusinessServiceTable::update
     * @covers STS\CMDB\CMDBBusinessServiceTable::updateByJson
     */
    public function testUpdate()
    {
        $bs = $this->objectTable->getByName($this->testName);

        // first no change
        $bs = $this->objectTable->update($bs);
        $this->assertInstanceOf('\STS\CMDB\CMDBBusinessService', $bs);
        $this->assertEquals($this->testName, $bs->getName());

        // since there were no changes, the last history entry should be the query url
        $this->assertEquals('https://neustartest.service-now.com/cmdb_ci_service.do?JSON&displayvalue=all&sysparm_action=getRecords&sysparm_query=name%3DNPAC',
            $this->objectTable->getQueryHistory());

        // change name
        $bs->setName("fred");
        $bs = $this->objectTable->update($bs);
        $this->assertInstanceOf('\STS\CMDB\CMDBBusinessService', $bs);
        $this->assertEquals($this->testName, $bs->getName());

        // this time there are changes so update is called. The last query will be the get to query the CI after the update
        // so we need to go back 2 queries to test that the URL was correct. Same for JSON
        $this->assertEquals('https://neustartest.service-now.com/cmdb_ci_service.do?JSON&sysparm_action=update&sysparm_query=sys_id=b99cc4520a0a3cac01230445a15a7d7f',
            $this->objectTable->getQueryHistory(2));
        $this->assertEquals('{"name":"fred"}', $this->objectTable->getJsonHistory(2));
    }

    /**
     * Checks all the CMDBBusinessService properties
     *
     * @param \STS\CMDB\CMDBBusinessService $s
     */
    public function checkClassProperties(\STS\CMDB\CMDBBusinessService $s)
    {
        $this->assertInstanceOf('STS\CMDB\CMDBBusinessService', $s);

        $this->assertEquals("b99cc4520a0a3cac01230445a15a7d7f", $s->getSysId());
        $this->assertEquals("cmdb_ci_service", $s->getSysClassName());
        $this->assertEquals("NPAC", $s->getName());
        $this->assertEquals("Operational", $s->getOperationalStatus());
        $this->assertEquals("1", $s->getOperationalStatusId());
        $this->assertEquals("NPAC", $s->getBusinessServiceGrouping());
        $this->assertEquals("8dbfe083300574c07fa228ae87abc004", $s->getBusinessServiceGroupingId());
        $this->assertEquals("ChangeNotification-NPAC@neustar.biz", $s->getChangeNotification());
        $this->assertEquals("Rob Coffman, Brian Sullivan (SysOps), Donna Guazz", $s->getIncidentOwners());
        $this->assertEquals("d6117dbc0a0a3cac0096c937f8c4a08d,d6118cec0a0a3cac012c520a3f08dbc0,Donna Guazz", $s->getIncidentOwnersId());
        $this->assertEquals("John Denemark, Naumi White", $s->getIncidentExecutives());
        $this->assertEquals("d6117cdc0a0a3cac013d13ee94ef2b27,d6117de40a0a3cac009526e6abe9408b", $s->getIncidentExecutivesId());
        $this->assertEquals("", $s->getIncidentNotification());
        $this->assertEquals("1 – Highest ", $s->getOperationalSensitivity());
        $this->assertEquals("a77e19e5fcc078c8bba91d2a30c0ce34", $s->getOperationalSensitivityId());
        $this->assertEquals("NPAC", $s->getProduct());
        $this->assertEquals("00fbf6300a0a3cab01cc8cdf5f95c7be", $s->getProductId());
        $this->assertEquals("Sowmya Nekkalapudi", $s->getProductLeader());
        $this->assertEquals("d61180db0a0a3cac0027f8d1518a4fdf", $s->getProductLeaderId());
        $this->assertEquals("Geoffrey Salinger", $s->getSystemsAdminLeader());
        $this->assertEquals("d6118ffa0a0a3cac01f45268ab5d7bb3", $s->getSystemsAdminLeaderId());
        $this->assertEquals("Rob Coffman", $s->getOperationsLeader());
        $this->assertEquals("d6117dbc0a0a3cac0096c937f8c4a08d", $s->getOperationsLeaderId());
        $this->assertEquals("rlewis", $s->getSysCreatedBy());
        $this->assertEquals("2011-01-24 20:00:14", $s->getSysCreatedOn());
        $this->assertEquals("iculpepp", $s->getSysUpdatedBy());
        $this->assertEquals("2013-08-06 14:16:06", $s->getSysUpdatedOn());
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
        $this->assertEquals("2 - somewhat critical", $o->busines_criticality);
        $this->assertEquals("false", $o->can_print);
        $this->assertEquals("", $o->category);
        $this->assertEquals("", $o->change_control);
        $this->assertEquals("", $o->checked_in);
        $this->assertEquals("", $o->checked_out);
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
        $this->assertEquals("2 - somewhat critical", $o->dv_busines_criticality);
        $this->assertEquals("false", $o->dv_can_print);
        $this->assertEquals("", $o->dv_category);
        $this->assertEquals("", $o->dv_change_control);
        $this->assertEquals("", $o->dv_checked_in);
        $this->assertEquals("", $o->dv_checked_out);
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
        $this->assertEquals("NPAC", $o->dv_name);
        $this->assertEquals("Operational", $o->dv_operational_status);
        $this->assertEquals("", $o->dv_order_date);
        $this->assertEquals("", $o->dv_owned_by);
        $this->assertEquals("", $o->dv_parent);
        $this->assertEquals("", $o->dv_po_number);
        $this->assertEquals("", $o->dv_purchase_date);
        $this->assertEquals("", $o->dv_schedule);
        $this->assertEquals("", $o->dv_serial_number);
        $this->assertEquals("", $o->dv_short_description);
        $this->assertEquals("false", $o->dv_skip_sync);
        $this->assertEquals("", $o->dv_sla);
        $this->assertEquals("", $o->dv_start_date);
        $this->assertEquals("", $o->dv_subcategory);
        $this->assertEquals("", $o->dv_support_group);
        $this->assertEquals("", $o->dv_supported_by);
        $this->assertEquals("Business Service", $o->dv_sys_class_name);
        $this->assertEquals("rlewis", $o->dv_sys_created_by);
        $this->assertEquals("2011-01-24 15:00:14", $o->dv_sys_created_on);
        $this->assertEquals("global", $o->dv_sys_domain);
        $this->assertEquals("b99cc4520a0a3cac01230445a15a7d7f", $o->dv_sys_id);
        $this->assertEquals("19", $o->dv_sys_mod_count);
        $this->assertEquals("iculpepp", $o->dv_sys_updated_by);
        $this->assertEquals("2013-08-06 10:16:06", $o->dv_sys_updated_on);
        $this->assertEquals("", $o->dv_u_acronym);
        $this->assertEquals("", $o->dv_u_alias___cnames);
        $this->assertEquals("false", $o->dv_u_alternate_access_management_);
        $this->assertEquals("", $o->dv_u_asset_id);
        $this->assertEquals("", $o->dv_u_asset_receipt_date_time);
        $this->assertEquals("", $o->dv_u_b_location);
        $this->assertEquals("NPAC", $o->dv_u_business_service_grouping);
        $this->assertEquals("ChangeNotification-NPAC@neustar.biz", $o->dv_u_change_notification);
        $this->assertEquals("5", $o->dv_u_cm_time_rule);
        $this->assertEquals("", $o->dv_u_cmdb_subsystem_list);
        $this->assertEquals("", $o->dv_u_contract_line_item);
        $this->assertEquals("Naumi White", $o->dv_u_customer_support_leader);
        $this->assertEquals("", $o->dv_u_description);
        $this->assertEquals("Edward Barker", $o->dv_u_development_leader);
        $this->assertEquals("", $o->dv_u_distribution_switch);
        $this->assertEquals("true", $o->dv_u_documentation);
        $this->assertEquals("2013-05-17", $o->dv_u_dr_last_update);
        $this->assertEquals("http://neushare/sites/Audit/QC/ISO/Level%201%20and%202%20Documents/Process%20Profiles/NPAC%20Disaster%20Recovery%20M%20P.docx", $o->dv_u_dr_location_link);
        $this->assertEquals("Yes", $o->dv_u_dr_plan);
        $this->assertEquals("", $o->dv_u_dr_plan_comments);
        $this->assertEquals("2013-05-05", $o->dv_u_dr_plan_last_test);
        $this->assertEquals("As Part of NPAC Releases", $o->dv_u_dr_plan_test);
        $this->assertEquals("", $o->dv_u_environment);
        $this->assertEquals("John Denemark, Naumi White", $o->dv_u_incident_executives);
        $this->assertEquals("IncidentNotification-NPAC@neustar.biz", $o->dv_u_incident_notification);
        $this->assertEquals("", $o->dv_u_incident_notification_group);
        $this->assertEquals("Rob Coffman, Brian Sullivan (SysOps), Donna Guazz", $o->dv_u_incident_owners);
        $this->assertEquals("", $o->dv_u_location_served);
        $this->assertEquals("", $o->dv_u_location_status_last_updated);
        $this->assertEquals("", $o->dv_u_long_name);
        $this->assertEquals("", $o->dv_u_manufacturer_date);
        $this->assertEquals("1 – Highest ", $o->dv_u_operational_sensitivity);
        $this->assertEquals("Rob Coffman", $o->dv_u_operations_leader);
        $this->assertEquals("", $o->dv_u_p_o__asset_id);
        $this->assertEquals("", $o->dv_u_p_o__asset_serial_number);
        $this->assertEquals("", $o->dv_u_p_o__item_description);
        $this->assertEquals("", $o->dv_u_p_o__requestor);
        $this->assertEquals("", $o->dv_u_p_o__vendor);
        $this->assertEquals("false", $o->dv_u_pingable_asset);
        $this->assertEquals("", $o->dv_u_po_line_item_number);
        $this->assertEquals("", $o->dv_u_po_receipt_status);
        $this->assertEquals("NPAC", $o->dv_u_product);
        $this->assertEquals("Sowmya Nekkalapudi", $o->dv_u_product_leader);
        $this->assertEquals("Geoffrey Salinger", $o->dv_u_systems_administration_leade);
        $this->assertEquals("Production", $o->dv_used_for);
        $this->assertEquals("", $o->dv_user_group);
        $this->assertEquals("", $o->dv_vendor);
        $this->assertEquals("", $o->dv_version);
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
        $this->assertEquals("NPAC", $o->name);
        $this->assertEquals("1", $o->operational_status);
        $this->assertEquals("", $o->order_date);
        $this->assertEquals("", $o->owned_by);
        $this->assertEquals("", $o->parent);
        $this->assertEquals("", $o->po_number);
        $this->assertEquals("", $o->purchase_date);
        $this->assertEquals("", $o->schedule);
        $this->assertEquals("", $o->serial_number);
        $this->assertEquals("", $o->short_description);
        $this->assertEquals("false", $o->skip_sync);
        $this->assertEquals("", $o->sla);
        $this->assertEquals("", $o->start_date);
        $this->assertEquals("", $o->subcategory);
        $this->assertEquals("", $o->support_group);
        $this->assertEquals("", $o->supported_by);
        $this->assertEquals("cmdb_ci_service", $o->sys_class_name);
        $this->assertEquals("rlewis", $o->sys_created_by);
        $this->assertEquals("2011-01-24 20:00:14", $o->sys_created_on);
        $this->assertEquals("global", $o->sys_domain);
        $this->assertEquals("b99cc4520a0a3cac01230445a15a7d7f", $o->sys_id);
        $this->assertEquals("19", $o->sys_mod_count);
        $this->assertEquals("iculpepp", $o->sys_updated_by);
        $this->assertEquals("2013-08-06 14:16:06", $o->sys_updated_on);
        $this->assertEquals("", $o->u_acronym);
        $this->assertEquals("", $o->u_alias___cnames);
        $this->assertEquals("false", $o->u_alternate_access_management_);
        $this->assertEquals("", $o->u_asset_id);
        $this->assertEquals("", $o->u_asset_receipt_date_time);
        $this->assertEquals("", $o->u_b_location);
        $this->assertEquals("8dbfe083300574c07fa228ae87abc004", $o->u_business_service_grouping);
        $this->assertEquals("ChangeNotification-NPAC@neustar.biz", $o->u_change_notification);
        $this->assertEquals("5", $o->u_cm_time_rule);
        $this->assertEquals("", $o->u_cmdb_subsystem_list);
        $this->assertEquals("", $o->u_contract_line_item);
        $this->assertEquals("d6117de40a0a3cac009526e6abe9408b", $o->u_customer_support_leader);
        $this->assertEquals("", $o->u_description);
        $this->assertEquals("d6117ff60a0a3cac01d436b342ead634", $o->u_development_leader);
        $this->assertEquals("", $o->u_distribution_switch);
        $this->assertEquals("true", $o->u_documentation);
        $this->assertEquals("2013-05-17", $o->u_dr_last_update);
        $this->assertEquals("http://neushare/sites/Audit/QC/ISO/Level%201%20and%202%20Documents/Process%20Profiles/NPAC%20Disaster%20Recovery%20M%20P.docx", $o->u_dr_location_link);
        $this->assertEquals("Yes", $o->u_dr_plan);
        $this->assertEquals("", $o->u_dr_plan_comments);
        $this->assertEquals("2013-05-05", $o->u_dr_plan_last_test);
        $this->assertEquals("As Part of NPAC Releases", $o->u_dr_plan_test);
        $this->assertEquals("0", $o->u_environment);
        $this->assertEquals("d6117cdc0a0a3cac013d13ee94ef2b27,d6117de40a0a3cac009526e6abe9408b", $o->u_incident_executives);
        $this->assertEquals("IncidentNotification-NPAC@neustar.biz", $o->u_incident_notification);
        $this->assertEquals("", $o->u_incident_notification_group);
        $this->assertEquals("d6117dbc0a0a3cac0096c937f8c4a08d,d6118cec0a0a3cac012c520a3f08dbc0,Donna Guazz", $o->u_incident_owners);
        $this->assertEquals("", $o->u_location_served);
        $this->assertEquals("", $o->u_location_status_last_updated);
        $this->assertEquals("", $o->u_long_name);
        $this->assertEquals("", $o->u_manufacturer_date);
        $this->assertEquals("a77e19e5fcc078c8bba91d2a30c0ce34", $o->u_operational_sensitivity);
        $this->assertEquals("d6117dbc0a0a3cac0096c937f8c4a08d", $o->u_operations_leader);
        $this->assertEquals("", $o->u_p_o__asset_id);
        $this->assertEquals("", $o->u_p_o__asset_serial_number);
        $this->assertEquals("", $o->u_p_o__item_description);
        $this->assertEquals("", $o->u_p_o__requestor);
        $this->assertEquals("", $o->u_p_o__vendor);
        $this->assertEquals("false", $o->u_pingable_asset);
        $this->assertEquals("", $o->u_po_line_item_number);
        $this->assertEquals("", $o->u_po_receipt_status);
        $this->assertEquals("00fbf6300a0a3cab01cc8cdf5f95c7be", $o->u_product);
        $this->assertEquals("d61180db0a0a3cac0027f8d1518a4fdf", $o->u_product_leader);
        $this->assertEquals("d6118ffa0a0a3cac01f45268ab5d7bb3", $o->u_systems_administration_leade);
        $this->assertEquals("Production", $o->used_for);
        $this->assertEquals("", $o->user_group);
        $this->assertEquals("", $o->vendor);
        $this->assertEquals("", $o->version);
        $this->assertEquals("", $o->warranty_expiration);
    }
}
