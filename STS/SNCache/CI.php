<?php
/*******************************************************************************
 *
 * $Id: CMDBCI.php 79833 2013-10-14 14:01:00Z rcallaha $
 * $Date: 2013-10-14 10:01:00 -0400 (Mon, 14 Oct 2013) $
 * $Author: rcallaha $
 * $Revision: 79833 $
 * $HeadURL: https://svn.ultradns.net/svn/sts_tools/sts-lib/trunk/STS/CMDB/CMDBCI.php $
 *
 *******************************************************************************
 */

namespace STS\SNCache;

class CI
{
	protected $sysId;
	protected $sysClassName;

	protected $name;
    protected $serialNumber;
    protected $assetTag;

    protected $installStatus;
    protected $installStatusId;

   	protected $location;
    protected $locationId;

   	protected $manufacturer;
    protected $manufacturerId;
   	protected $modelNumber;

    protected $deliveryDate;
    protected $assetReceiptDateTime;
    protected $poNumber;
    protected $assetId;
    protected $poRequestor;
    protected $poRequestorId;

	protected $sysCreatedBy;
	protected $sysCreatedOn;
	protected $sysUpdatedBy;
	protected $sysUpdatedOn;

    protected $changes = array();


	/**
	 * @return string
	 */
	public function __toString()
	{
        $return = "";
        foreach (get_class_vars(__CLASS__) as $prop => $x) {
            if (property_exists($this, $prop)) {
                $return .= sprintf("%-25s => %s\n", $prop, $this->$prop);
            }
        }
        return $return;
	}

	/**
	 * @return object
	 */
	public function toObject()
	{
        $obj = (object)array();
        foreach (get_class_vars(__CLASS__) as $prop => $x) {
            if (property_exists($this, $prop)) {
                $obj->$prop = $this->$prop;
            }
        }
        return $obj;
	}

	// *******************************************************************************
	// * Getters and Setters
	// *****************************************************************************

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
            function($matches) {
                return strtolower($matches[1]);
            },
            $callerMethod
        );

        // update the changes array to keep track of this properties orig and new values
        if (!array_key_exists($prop, $this->changes)) {
            $this->changes[$prop] = (object) array(
                'originalValue' => $this->$prop,
                'modifiedValue' => $value
            );
        } else {
            $this->changes[$prop]->modifiedValue = $value;
        }
    }

    /**
     * @param $name
     * @return $this
     */
    public function setName($name)
	{
        $this->updateChanges(func_get_arg(0));
		$this->name = $name;
        return $this;
	}

    /**
     * @return mixed
     */
    public function getName()
	{
		return $this->name;
	}

    /**
     * @param $serialNumber
     * @return $this
     */
    public function setSerialNumber($serialNumber)
	{
        $this->updateChanges(func_get_arg(0));
		$this->serialNumber = $serialNumber;
        return $this;
	}

    /**
     * @return mixed
     */
    public function getSerialNumber()
	{
		return $this->serialNumber;
	}

    /**
     * @param $sysClassName
     * @return $this
     */
    public function setSysClassName($sysClassName)
	{
        $this->updateChanges(func_get_arg(0));
		$this->sysClassName = $sysClassName;
        return $this;
	}

    /**
     * @return mixed
     */
    public function getSysClassName()
	{
		return $this->sysClassName;
	}

    /**
     * @param $sysCreatedBy
     * @return $this
     */
    public function setSysCreatedBy($sysCreatedBy)
	{
        $this->updateChanges(func_get_arg(0));
		$this->sysCreatedBy = $sysCreatedBy;
        return $this;
	}

    /**
     * @return mixed
     */
    public function getSysCreatedBy()
	{
		return $this->sysCreatedBy;
	}

    /**
     * @param $sysCreatedOn
     * @return $this
     */
    public function setSysCreatedOn($sysCreatedOn)
	{
        $this->updateChanges(func_get_arg(0));
		$this->sysCreatedOn = $sysCreatedOn;
        return $this;
	}

    /**
     * @return mixed
     */
    public function getSysCreatedOn()
	{
		return $this->sysCreatedOn;
	}

    /**
     * @param $sysId
     * @return $this
     */
    public function setSysId($sysId)
	{
        $this->updateChanges(func_get_arg(0));
		$this->sysId = $sysId;
        return $this;
	}

    /**
     * @return mixed
     */
    public function getSysId()
	{
		return $this->sysId;
	}

    /**
     * @param $id
     * @return $this
     */
    public function setId($id)
	{
        $this->updateChanges(func_get_arg(0));
		$this->sysId = $id;
        return $this;
	}

    /**
     * @return mixed
     */
    public function getId()
	{
		return $this->sysId;
	}

    /**
     * @param $sysUpdatedBy
     * @return $this
     */
    public function setSysUpdatedBy($sysUpdatedBy)
	{
        $this->updateChanges(func_get_arg(0));
		$this->sysUpdatedBy = $sysUpdatedBy;
        return $this;
	}

    /**
     * @return mixed
     */
    public function getSysUpdatedBy()
	{
		return $this->sysUpdatedBy;
	}

    /**
     * @param $sysUpdatedOn
     * @return $this
     */
    public function setSysUpdatedOn($sysUpdatedOn)
	{
        $this->updateChanges(func_get_arg(0));
		$this->sysUpdatedOn = $sysUpdatedOn;
        return $this;
	}

    /**
     * @return mixed
     */
    public function getSysUpdatedOn()
	{
		return $this->sysUpdatedOn;
    }

    /**
     * @param $installStatus
     * @return $this
     */
    public function setInstallStatus($installStatus)
    {
        $this->updateChanges(func_get_arg(0));
        $this->installStatus = $installStatus;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getInstallStatus()
    {
        return $this->installStatus;
    }

    /**
     * @param $installStatusId
     * @return $this
     */
    public function setInstallStatusId($installStatusId)
    {
        $this->updateChanges(func_get_arg(0));
        $this->installStatusId = $installStatusId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getInstallStatusId()
    {
        return $this->installStatusId;
    }

    /**
     * @param $assetTag
     * @return $this
     */
    public function setAssetTag($assetTag)
    {
        $this->updateChanges(func_get_arg(0));
        $this->assetTag = $assetTag;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAssetTag()
    {
        return $this->assetTag;
    }

    /**
     * @param $location
     * @return $this
     */
    public function setLocation($location)
    {
        $this->updateChanges(func_get_arg(0));
        $this->location = $location;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * @param mixed $locationId
     * @return $this
     */
    public function setLocationId($locationId)
    {
        $this->updateChanges(func_get_arg(0));
        $this->locationId = $locationId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLocationId()
    {
        return $this->locationId;
    }

    /**
     * @param mixed $manufacturer
     * @return $this
     */
    public function setManufacturer($manufacturer)
    {
        $this->updateChanges(func_get_arg(0));
        $this->manufacturer = $manufacturer;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getManufacturer()
    {
        return $this->manufacturer;
    }

    /**
     * @param mixed $manufacturerId
     * @return $this
     */
    public function setManufacturerId($manufacturerId)
    {
        $this->updateChanges(func_get_arg(0));
        $this->manufacturerId = $manufacturerId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getManufacturerId()
    {
        return $this->manufacturerId;
    }

    /**
     * @param mixed $modelNumber
     * @return $this
     */
    public function setModelNumber($modelNumber)
    {
        $this->updateChanges(func_get_arg(0));
        $this->modelNumber = $modelNumber;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getModelNumber()
    {
        return $this->modelNumber;
    }

    /**
     * @param mixed $assetId
     * @return $this
     */
    public function setAssetId($assetId)
    {
        $this->updateChanges(func_get_arg(0));
        $this->assetId = $assetId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAssetId()
    {
        return $this->assetId;
    }

    /**
     * @param mixed $assetReceiptDateTime
     * @return $this
     */
    public function setAssetReceiptDateTime($assetReceiptDateTime)
    {
        $this->updateChanges(func_get_arg(0));
        $this->assetReceiptDateTime = $assetReceiptDateTime;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAssetReceiptDateTime()
    {
        return $this->assetReceiptDateTime;
    }

    /**
     * @param mixed $deliveryDate
     * @return $this
     */
    public function setDeliveryDate($deliveryDate)
    {
        $this->updateChanges(func_get_arg(0));
        $this->deliveryDate = $deliveryDate;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDeliveryDate()
    {
        return $this->deliveryDate;
    }

    /**
     * @param mixed $poNumber
     * @return $this
     */
    public function setPoNumber($poNumber)
    {
        $this->updateChanges(func_get_arg(0));
        $this->poNumber = $poNumber;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPoNumber()
    {
        return $this->poNumber;
    }

    /**
     * @param mixed $poRequestor
     * @return $this
     */
    public function setPoRequestor($poRequestor)
    {
        $this->updateChanges(func_get_arg(0));
        $this->poRequestor = $poRequestor;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPoRequestor()
    {
        return $this->poRequestor;
    }

    /**
     * @param mixed $poRequestorId
     * @return $this
     */
    public function setPoRequestorId($poRequestorId)
    {
        $this->updateChanges(func_get_arg(0));
        $this->poRequestorId = $poRequestorId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPoRequestorId()
    {
        return $this->poRequestorId;
    }

}
