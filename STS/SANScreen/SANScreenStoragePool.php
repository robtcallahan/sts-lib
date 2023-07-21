<?php
/*******************************************************************************
 *
 * $Id: SANScreenVolume.php 74756 2013-04-26 17:08:54Z rcallaha $
 * $Date: 2013-04-26 13:08:54 -0400 (Fri, 26 Apr 2013) $
 * $Author: rcallaha $
 * $Revision: 74756 $
 * $HeadURL: https://svn.ultradns.net/svn/sts_tools/sts-lib/trunk/STS/SANScreen/SANScreenVolume.php $
 *
 *******************************************************************************
 */

namespace STS\SANScreen;

class SANScreenStoragePool
{
    protected $id;
	protected $dataAllocatedCapacityMB;
	protected $dataUsedCapacityMB;
	protected $dedupeSavings;
	protected $name;
	protected $otherAllocatedCapacityMB;
	protected $otherUsedCapacityMB;
	protected $physicalDiskCapacityMB;
	protected $rawToUsableRatio;
	protected $redundancy;
	protected $reservedCapacityMB;
	protected $snapshotAllocatedCapacityMB;
	protected $snapshotUsedCapacityMB;
	protected $status;
	protected $storageId;
	protected $totalAllocatedCapacityMB;
	protected $totalUsedCapacityMB;
	protected $type;
	protected $vendorTier;
	protected $autoTiering;
	protected $dedupeEnabled;
	protected $includeInDwhCapacity;
	protected $raidGroup;
	protected $thinProvisioningSupported;
	protected $usesSSDCache;
	protected $virtual;

    /**
     * Keeps track of properties that have their values changed
     *
     * @var array
     */
    protected $changes = array();

    /**
     * @return string
     */
	public function __toString()
	{
		$return = "";
		foreach (SANScreenStoragePoolTable::getColumnNames() as $prop) {
			$return .= sprintf("%-25s => %s\n", $prop, $this->$prop);
		}
		return $return;
	}

    /**
     * @return object
     */
	public function toObject()
	{
		$obj = (object) array();
		foreach (SANScreenStoragePoolTable::getColumnNames() as $prop) {
			$obj->$prop = $this->$prop;
		}
		return $obj;
	}


	// *******************************************************************************
	// Getters and Setters
	// *******************************************************************************

    /**
     * @param $prop
     * @return mixed
     */
    public function get($prop)
    {
        return $this->$prop;
    }

    /**
     * @param $prop
     * @param $value
     * @return mixed
     */
    public function set($prop, $value)
    {
        $this->$prop = $value;
        return $this;
    }

    /**
     * @return array
     */
    public function getChanges()
    {
        return $this->changes;
    }

    /**
     *
     */
    public function clearChanges()
    {
        $this->changes = array();
    }

    /**
     * @param $value
     */
    private function updateChanges($value)
    {
        $trace = debug_backtrace();

        // get the calling method name, eg., setSysId
        $callerMethod = $trace[1]["function"];

        // perform a replace to remove "set" from the method name and change first letter to lowercase
        // so, setSysId becomes sysId. This will be the property name that needs to be added to the changes array
        $prop = preg_replace_callback(
            "/^set(\w)/",
            function ($matches) {
                return strtolower($matches[1]);
            },
            $callerMethod
        );

        // update the changes array to keep track of this properties orig and new values
        if ($this->$prop != $value) {
            if (!array_key_exists($prop, $this->changes)) {
                $this->changes[$prop] = (object)array(
                    'originalValue' => $this->$prop,
                    'modifiedValue' => $value
                );
            } else {
                $this->changes[$prop]->modifiedValue = $value;
            }
        }
    }

    /**
     * @param mixed $autoTiering
     * @return $this
     */
    public function setAutoTiering($autoTiering) {
        $this->updateChanges(func_get_arg(0));
        $this->autoTiering = $autoTiering;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAutoTiering() {
        return $this->autoTiering;
    }

    /**
     * @param mixed $dataAllocatedCapacityMB
     * @return $this
     */
    public function setDataAllocatedCapacityMB($dataAllocatedCapacityMB) {
        $this->updateChanges(func_get_arg(0));
        $this->dataAllocatedCapacityMB = $dataAllocatedCapacityMB;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDataAllocatedCapacityMB() {
        return $this->dataAllocatedCapacityMB;
    }

    /**
     * @param mixed $dataUsedCapacityMB
     * @return $this
     */
    public function setDataUsedCapacityMB($dataUsedCapacityMB) {
        $this->updateChanges(func_get_arg(0));
        $this->dataUsedCapacityMB = $dataUsedCapacityMB;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDataUsedCapacityMB() {
        return $this->dataUsedCapacityMB;
    }

    /**
     * @param mixed $dedupeEnabled
     * @return $this
     */
    public function setDedupeEnabled($dedupeEnabled) {
        $this->updateChanges(func_get_arg(0));
        $this->dedupeEnabled = $dedupeEnabled;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDedupeEnabled() {
        return $this->dedupeEnabled;
    }

    /**
     * @param mixed $dedupeSavings
     * @return $this
     */
    public function setDedupeSavings($dedupeSavings) {
        $this->updateChanges(func_get_arg(0));
        $this->dedupeSavings = $dedupeSavings;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDedupeSavings() {
        return $this->dedupeSavings;
    }

    /**
     * @param mixed $id
     * @return $this
     */
    public function setId($id) {
        $this->updateChanges(func_get_arg(0));
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @param mixed $includeInDwhCapacity
     * @return $this
     */
    public function setIncludeInDwhCapacity($includeInDwhCapacity) {
        $this->updateChanges(func_get_arg(0));
        $this->includeInDwhCapacity = $includeInDwhCapacity;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIncludeInDwhCapacity() {
        return $this->includeInDwhCapacity;
    }

    /**
     * @param mixed $name
     * @return $this
     */
    public function setName($name) {
        $this->updateChanges(func_get_arg(0));
        $this->name = $name;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @param mixed $otherAllocatedCapacityMB
     * @return $this
     */
    public function setOtherAllocatedCapacityMB($otherAllocatedCapacityMB) {
        $this->updateChanges(func_get_arg(0));
        $this->otherAllocatedCapacityMB = $otherAllocatedCapacityMB;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getOtherAllocatedCapacityMB() {
        return $this->otherAllocatedCapacityMB;
    }

    /**
     * @param mixed $otherUsedCapacityMB
     * @return $this
     */
    public function setOtherUsedCapacityMB($otherUsedCapacityMB) {
        $this->updateChanges(func_get_arg(0));
        $this->otherUsedCapacityMB = $otherUsedCapacityMB;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getOtherUsedCapacityMB() {
        return $this->otherUsedCapacityMB;
    }

    /**
     * @param mixed $physicalDiskCapacityMB
     * @return $this
     */
    public function setPhysicalDiskCapacityMB($physicalDiskCapacityMB) {
        $this->updateChanges(func_get_arg(0));
        $this->physicalDiskCapacityMB = $physicalDiskCapacityMB;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPhysicalDiskCapacityMB() {
        return $this->physicalDiskCapacityMB;
    }

    /**
     * @param mixed $raidGroup
     * @return $this
     */
    public function setRaidGroup($raidGroup) {
        $this->updateChanges(func_get_arg(0));
        $this->raidGroup = $raidGroup;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRaidGroup() {
        return $this->raidGroup;
    }

    /**
     * @param mixed $rawToUsableRatio
     * @return $this
     */
    public function setRawToUsableRatio($rawToUsableRatio) {
        $this->updateChanges(func_get_arg(0));
        $this->rawToUsableRatio = $rawToUsableRatio;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRawToUsableRatio() {
        return $this->rawToUsableRatio;
    }

    /**
     * @param mixed $redundancy
     * @return $this
     */
    public function setRedundancy($redundancy) {
        $this->updateChanges(func_get_arg(0));
        $this->redundancy = $redundancy;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRedundancy() {
        return $this->redundancy;
    }

    /**
     * @param mixed $reservedCapacityMB
     * @return $this
     */
    public function setReservedCapacityMB($reservedCapacityMB) {
        $this->updateChanges(func_get_arg(0));
        $this->reservedCapacityMB = $reservedCapacityMB;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getReservedCapacityMB() {
        return $this->reservedCapacityMB;
    }

    /**
     * @param mixed $snapshotAllocatedCapacityMB
     * @return $this
     */
    public function setSnapshotAllocatedCapacityMB($snapshotAllocatedCapacityMB) {
        $this->updateChanges(func_get_arg(0));
        $this->snapshotAllocatedCapacityMB = $snapshotAllocatedCapacityMB;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSnapshotAllocatedCapacityMB() {
        return $this->snapshotAllocatedCapacityMB;
    }

    /**
     * @param mixed $snapshotUsedCapacityMB
     * @return $this
     */
    public function setSnapshotUsedCapacityMB($snapshotUsedCapacityMB) {
        $this->updateChanges(func_get_arg(0));
        $this->snapshotUsedCapacityMB = $snapshotUsedCapacityMB;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSnapshotUsedCapacityMB() {
        return $this->snapshotUsedCapacityMB;
    }

    /**
     * @param mixed $status
     * @return $this
     */
    public function setStatus($status) {
        $this->updateChanges(func_get_arg(0));
        $this->status = $status;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getStatus() {
        return $this->status;
    }

    /**
     * @param mixed $storageId
     * @return $this
     */
    public function setStorageId($storageId) {
        $this->updateChanges(func_get_arg(0));
        $this->storageId = $storageId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getStorageId() {
        return $this->storageId;
    }

    /**
     * @param mixed $thinProvisioningSupported
     * @return $this
     */
    public function setThinProvisioningSupported($thinProvisioningSupported) {
        $this->updateChanges(func_get_arg(0));
        $this->thinProvisioningSupported = $thinProvisioningSupported;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getThinProvisioningSupported() {
        return $this->thinProvisioningSupported;
    }

    /**
     * @param mixed $totalAllocatedCapacityMB
     * @return $this
     */
    public function setTotalAllocatedCapacityMB($totalAllocatedCapacityMB) {
        $this->updateChanges(func_get_arg(0));
        $this->totalAllocatedCapacityMB = $totalAllocatedCapacityMB;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTotalAllocatedCapacityMB() {
        return $this->totalAllocatedCapacityMB;
    }

    /**
     * @param mixed $totalUsedCapacityMB
     * @return $this
     */
    public function setTotalUsedCapacityMB($totalUsedCapacityMB) {
        $this->updateChanges(func_get_arg(0));
        $this->totalUsedCapacityMB = $totalUsedCapacityMB;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTotalUsedCapacityMB() {
        return $this->totalUsedCapacityMB;
    }

    /**
     * @param mixed $type
     * @return $this
     */
    public function setType($type) {
        $this->updateChanges(func_get_arg(0));
        $this->type = $type;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getType() {
        return $this->type;
    }

    /**
     * @param mixed $usesSSDCache
     * @return $this
     */
    public function setUsesSSDCache($usesSSDCache) {
        $this->updateChanges(func_get_arg(0));
        $this->usesSSDCache = $usesSSDCache;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUsesSSDCache() {
        return $this->usesSSDCache;
    }

    /**
     * @param mixed $vendorTier
     * @return $this
     */
    public function setVendorTier($vendorTier) {
        $this->updateChanges(func_get_arg(0));
        $this->vendorTier = $vendorTier;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getVendorTier() {
        return $this->vendorTier;
    }

    /**
     * @param mixed $virtual
     * @return $this
     */
    public function setVirtual($virtual) {
        $this->updateChanges(func_get_arg(0));
        $this->virtual = $virtual;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getVirtual() {
        return $this->virtual;
    }

}
