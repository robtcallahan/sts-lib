<?php
/*******************************************************************************
 *
 * $Id: SANScreenSnapshot.php 74756 2013-04-26 17:08:54Z rcallaha $
 * $Date: 2013-04-26 13:08:54 -0400 (Fri, 26 Apr 2013) $
 * $Author: rcallaha $
 * $Revision: 74756 $
 * $HeadURL: https://svn.ultradns.net/svn/sts_tools/sts-lib/trunk/STS/SANScreen/SANScreenSnapshot.php $
 *
 *******************************************************************************
 */

namespace STS\SANScreen;

class SANScreenHostSnapshot
{
	protected $id;
	protected $dateStamp;

	protected $hostName;
	protected $allocatedGb;

	protected $arrayName;
    protected $sanName;
    protected $tier;
	protected $capacityGb;

	protected $businessService;
	protected $subsystem;

	public function __toString()
	{
		$return = "";
		foreach (SANScreenHostSnapshotTable::getColumnNames() as $prop) {
			$return .= sprintf("%-25s => %s\n", $prop, $this->$prop);
		}
		return $return;
	}

	public function toObject()
	{
		$obj = (object) array();
		foreach (SANScreenHostSnapshotTable::getColumnNames() as $prop) {
			$obj->$prop = $this->$prop;
		}
		return $obj;
	}


	// *******************************************************************************
	// Getters and Setters
	// *******************************************************************************

	public function get($prop)
	{
		return $this->$prop;
	}

	public function set($prop, $value)
	{
		return $this->$prop = $value;
	}

	public function setAllocatedGb($allocatedGb)
	{
		$this->allocatedGb = $allocatedGb;
	}

	public function getAllocatedGb()
	{
		return $this->allocatedGb;
	}

	public function setArrayName($arrayName)
	{
		$this->arrayName = $arrayName;
	}

	public function getArrayName()
	{
		return $this->arrayName;
	}

	public function setBusinessService($businessService)
	{
		$this->businessService = $businessService;
	}

	public function getBusinessService()
	{
		return $this->businessService;
	}

	public function setCapacityGb($capacityGb)
	{
		$this->capacityGb = $capacityGb;
	}

	public function getCapacityGb()
	{
		return $this->capacityGb;
	}

	public function setDateStamp($dateStamp)
	{
		$this->dateStamp = $dateStamp;
	}

	public function getDateStamp()
	{
		return $this->dateStamp;
	}

	public function setHostName($hostName)
	{
		$this->hostName = $hostName;
	}

	public function getHostName()
	{
		return $this->hostName;
	}

	public function setId($id)
	{
		$this->id = $id;
	}

	public function getId()
	{
		return $this->id;
	}

	public function setSubsystem($subsystem)
	{
		$this->subsystem = $subsystem;
	}

	public function getSubsystem()
	{
		return $this->subsystem;
	}

    /**
     * @param mixed $sanName
     * @return $this
     */
    public function setSanName($sanName)
    {
        $this->sanName = $sanName;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSanName()
    {
        return $this->sanName;
    }

    /**
     * @param mixed $tier
     * @return $this
     */
    public function setTier($tier)
    {
        $this->tier = $tier;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTier()
    {
        return $this->tier;
    }

}

