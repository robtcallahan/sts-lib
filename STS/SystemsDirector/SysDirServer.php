<?php
/*******************************************************************************
 *
 * $Id$
 * $Date$
 * $Author$
 * $Revision$
 * $HeadURL$
 *
 *******************************************************************************
*/

namespace STS\SystemsDirector;


class SysDirServer {
	protected $oid;
	protected $name;
	protected $machineType;
	protected $model;
	protected $manufacturer;
	protected $architecture;
	protected $serialNumber;

	protected $sysId;
	protected $cmdbName;
	protected $environment;
	protected $cmInstallStatus;
	protected $businessService;
	protected $subsystem;
	protected $opsSuppMgr;
	protected $opsSuppGrp;
	protected $comments;
	protected $shortDescr;

	// the following properties are not part of the db table
	protected $virtual;
	protected $vio;
	protected $relatedSystemOid;
	protected $relatedSystemName;
	protected $relatedSystems;



	// *******************************************************************************
	// * Getters and Setters
	// *****************************************************************************

	public function get($prop) {
		return $this->$prop;
	}

	public function set($prop, $value) {
		return $this->$prop = $value;
	}

	public function setArchitecture($architecture) {
		$this->architecture = $architecture;
	}

	public function getArchitecture() {
		return $this->architecture;
	}

	public function setMachineType($machineType) {
		$this->machineType = $machineType;
	}

	public function getMachineType() {
		return $this->machineType;
	}

	public function setManufacturer($manufacturer) {
		$this->manufacturer = $manufacturer;
	}

	public function getManufacturer() {
		return $this->manufacturer;
	}

	public function setModel($model) {
		$this->model = $model;
	}

	public function getModel() {
		return $this->model;
	}

	public function setName($name) {
		$this->name = $name;
	}

	public function getName() {
		return $this->name;
	}

	public function setOid($oid) {
		$this->oid = $oid;
	}

	public function getOid() {
		return $this->oid;
	}

	public function setSerialNumber($serialNumber) {
		$this->serialNumber = $serialNumber;
	}

	public function getSerialNumber() {
		return $this->serialNumber;
	}

	public function setRelatedSystemName($relatedSystemName) {
		$this->relatedSystemName = $relatedSystemName;
	}

	public function getRelatedSystemName() {
		return $this->relatedSystemName;
	}

	public function setRelatedSystemOid($relatedSystemOid) {
		$this->relatedSystemOid = $relatedSystemOid;
	}

	public function getRelatedSystemOid() {
		return $this->relatedSystemOid;
	}

	public function setVirtual($virtual) {
		$this->virtual = $virtual;
	}

	public function getVirtual() {
		return $this->virtual;
	}

	public function setBusinessService($businessService) {
		$this->businessService = $businessService;
	}

	public function getBusinessService() {
		return $this->businessService;
	}

	public function setCmInstallStatus($cmInstallStatus) {
		$this->cmInstallStatus = $cmInstallStatus;
	}

	public function getCmInstallStatus() {
		return $this->cmInstallStatus;
	}

	public function setComments($comments) {
		$this->comments = $comments;
	}

	public function getComments() {
		return $this->comments;
	}

	public function setEnvironment($environment) {
		$this->environment = $environment;
	}

	public function getEnvironment() {
		return $this->environment;
	}

	public function setOpsSuppGrp($opsSuppGrp) {
		$this->opsSuppGrp = $opsSuppGrp;
	}

	public function getOpsSuppGrp() {
		return $this->opsSuppGrp;
	}

	public function setOpsSuppMgr($opsSuppMgr) {
		$this->opsSuppMgr = $opsSuppMgr;
	}

	public function getOpsSuppMgr() {
		return $this->opsSuppMgr;
	}

	public function setShortDescr($shortDescr) {
		$this->shortDescr = $shortDescr;
	}

	public function getShortDescr() {
		return $this->shortDescr;
	}

	public function setSubsystem($subsystem) {
		$this->subsystem = $subsystem;
	}

	public function getSubsystem() {
		return $this->subsystem;
	}

	public function setSysId($sysId) {
		$this->sysId = $sysId;
	}

	public function getSysId() {
		return $this->sysId;
	}

	public function setRelatedSystems($relatedSystems) {
		$this->relatedSystems = $relatedSystems;
	}

	public function getRelatedSystems() {
		return $this->relatedSystems;
	}

	public function setVio($vio)
	{
		$this->vio = $vio;
	}

	public function getVio()
	{
		return $this->vio;
	}

	public function setCmdbName($cmdbName)
	{
		$this->cmdbName = $cmdbName;
	}

	public function getCmdbName()
	{
		return $this->cmdbName;
	}
}