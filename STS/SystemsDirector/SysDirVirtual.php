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


class SysDirVirtual extends SysDirServer
{
	protected $serverOid;
	protected $memTotal;

	protected $cpuManufacturer;
	protected $cpuMaxClockSpeed;
	protected $cpuFamily;
	protected $cpuCount;
	protected $cpuEnabledCores;

	protected $operatingSystem;
	protected $osVersion;
	protected $osBuildNumber;

	protected $powerPathVersion;


	public static function cast(SysDirVirtual $object) {
		return $object;
	}

	public function __toString() {
		$return = "";
		foreach (SysDirVirtualTable::getColumnNames() as $prop) {
			$return .= sprintf("%-25s => %s\n", $prop, $this->$prop);
		}
		return $return;
	}


	// *******************************************************************************
	// * Getters and Setters
	// *****************************************************************************

	public function setCpuCount($cpuCount) {
		$this->cpuCount = $cpuCount;
	}

	public function getCpuCount() {
		return $this->cpuCount;
	}

	public function setCpuEnabledCores($cpuEnabledCores) {
		$this->cpuEnabledCores = $cpuEnabledCores;
	}

	public function getCpuEnabledCores() {
		return $this->cpuEnabledCores;
	}

	public function setCpuMaxClockSpeed($cpuMaxClockSpeed) {
		$this->cpuMaxClockSpeed = $cpuMaxClockSpeed;
	}

	public function getCpuMaxClockSpeed() {
		return $this->cpuMaxClockSpeed;
	}

	public function setMemTotal($memTotal) {
		$this->memTotal = $memTotal;
	}

	public function getMemTotal() {
		return $this->memTotal;
	}

	public function setPowerPathVersion($powerPathVersion) {
		$this->powerPathVersion = $powerPathVersion;
	}

	public function getPowerPathVersion() {
		return $this->powerPathVersion;
	}

	public function setServerOid($serverOid)
	{
		$this->serverOid = $serverOid;
	}

	public function getServerOid()
	{
		return $this->serverOid;
	}

	public function setVio($vio)
	{
		$this->vio = $vio;
	}

	public function getVio()
	{
		return $this->vio;
	}

	public function setCpuFamily($cpuFamily)
	{
		$this->cpuFamily = $cpuFamily;
	}

	public function getCpuFamily()
	{
		return $this->cpuFamily;
	}

	public function setCpuManufacturer($cpuManufacturer)
	{
		$this->cpuManufacturer = $cpuManufacturer;
	}

	public function getCpuManufacturer()
	{
		return $this->cpuManufacturer;
	}

	public function setOperatingSystem($operatingSystem)
	{
		$this->operatingSystem = $operatingSystem;
	}

	public function getOperatingSystem()
	{
		return $this->operatingSystem;
	}

	public function setOsVersion($osVersion)
	{
		$this->osVersion = $osVersion;
	}

	public function getOsVersion()
	{
		return $this->osVersion;
	}

	public function setOsBuildNumber($osBuildNumber)
	{
		$this->osBuildNumber = $osBuildNumber;
	}

	public function getOsBuildNumber()
	{
		return $this->osBuildNumber;
	}

}