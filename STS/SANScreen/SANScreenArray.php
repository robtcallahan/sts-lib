<?php
/*******************************************************************************
 *
 * $Id: SANScreenArray.php 74756 2013-04-26 17:08:54Z rcallaha $
 * $Date: 2013-04-26 13:08:54 -0400 (Fri, 26 Apr 2013) $
 * $Author: rcallaha $
 * $Revision: 74756 $
 * $HeadURL: https://svn.ultradns.net/svn/sts_tools/sts-lib/trunk/STS/SANScreen/SANScreenArray.php $
 *
 *******************************************************************************
 */

namespace STS\SANScreen;

class SANScreenArray
{
	protected $id;
	protected $name;
    protected $sanName;
    protected $tier;
    protected $status;
	protected $objectType;
	protected $vendor;
	protected $model;
	protected $serialNumber;
	protected $capacityGB;
	protected $rawCapacityGB;
	protected $microcodeVersion;
	protected $ip;
	protected $dead;
	protected $startTime;
	protected $endTime;

    /**
     * @return string
     */
    public function __toString()
	{
		$return = "";
		foreach (SANScreenArrayTable::getColumnNames() as $prop) {
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
		foreach (SANScreenArrayTable::getColumnNames() as $prop) {
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
		return $this->$prop = $value;
	}

    /**
     * @param $capacityGB
     */
    public function setCapacityGB($capacityGB)
	{
		$this->capacityGB = $capacityGB;
	}

    /**
     * @return mixed
     */
    public function getCapacityGB()
	{
		return $this->capacityGB;
	}

    /**
     * @param $dead
     */
    public function setDead($dead)
	{
		$this->dead = $dead;
	}

    /**
     * @return mixed
     */
    public function getDead()
	{
		return $this->dead;
	}

    /**
     * @param $endTime
     */
    public function setEndTime($endTime)
	{
		$this->endTime = $endTime;
	}

    /**
     * @return mixed
     */
    public function getEndTime()
	{
		return $this->endTime;
	}

    /**
     * @param $id
     */
    public function setId($id)
	{
		$this->id = $id;
	}

    /**
     * @return mixed
     */
    public function getId()
	{
		return $this->id;
	}

    /**
     * @param $ip
     */
    public function setIp($ip)
	{
		$this->ip = $ip;
	}

    /**
     * @return mixed
     */
    public function getIp()
	{
		return $this->ip;
	}

    /**
     * @param $microcodeVersion
     */
    public function setMicrocodeVersion($microcodeVersion)
	{
		$this->microcodeVersion = $microcodeVersion;
	}

    /**
     * @return mixed
     */
    public function getMicrocodeVersion()
	{
		return $this->microcodeVersion;
	}

    /**
     * @param $model
     */
    public function setModel($model)
	{
		$this->model = $model;
	}

    /**
     * @return mixed
     */
    public function getModel()
	{
		return $this->model;
	}

    /**
     * @param $name
     */
    public function setName($name)
	{
		$this->name = $name;
	}

    /**
     * @return mixed
     */
    public function getName()
	{
		return $this->name;
	}

    /**
     * @param $rawCapacityGB
     */
    public function setRawCapacityGB($rawCapacityGB)
	{
		$this->rawCapacityGB = $rawCapacityGB;
	}

    /**
     * @return mixed
     */
    public function getRawCapacityGB()
	{
		return $this->rawCapacityGB;
	}

    /**
     * @param $serialNumber
     */
    public function setSerialNumber($serialNumber)
	{
		$this->serialNumber = $serialNumber;
	}

    /**
     * @return mixed
     */
    public function getSerialNumber()
	{
		return $this->serialNumber;
	}

    /**
     * @param $startTime
     */
    public function setStartTime($startTime)
	{
		$this->startTime = $startTime;
	}

    /**
     * @return mixed
     */
    public function getStartTime()
	{
		return $this->startTime;
	}

    /**
     * @param $vendor
     */
    public function setVendor($vendor)
	{
		$this->vendor = $vendor;
	}

    /**
     * @return mixed
     */
    public function getVendor()
	{
		return $this->vendor;
	}

    /**
     * @param $objectType
     */
    public function setObjectType($objectType)
	{
		$this->objectType = $objectType;
	}

    /**
     * @return mixed
     */
    public function getObjectType()
	{
		return $this->objectType;
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

    /**
     * @param mixed $status
     * @return $this
     */
    public function setStatus($status) {
        $this->status = $status;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getStatus() {
        return $this->status;
    }

}

