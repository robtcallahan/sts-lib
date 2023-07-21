<?php
/*******************************************************************************
 *
 * $Id: HPSIMVLAN.php 82756 2014-01-22 14:51:48Z rcallaha $
 * $Date: 2014-01-22 09:51:48 -0500 (Wed, 22 Jan 2014) $
 * $Author: rcallaha $
 * $Revision: 82756 $
 * $HeadURL: https://svn.ultradns.net/svn/sts_tools/sts-lib/trunk/STS/HPSIM/HPSIMVLAN.php $
 *
 *******************************************************************************
 */

namespace STS\HPSIM;

class HPSIMVLAN
{
	protected $id;
	protected $switchId;

	protected $name;
    protected $distSwitchName;
	protected $status;
	protected $sharedUplinkSet;
	protected $vlanId;
	protected $nativeVlan;
	protected $private;
	protected $preferredSpeed;

	public function __toString()
	{
		$return = "";
		foreach (HPSIMVLANTable::getColumnNames() as $prop) {
			$return .= sprintf("%-25s => %s\n", $prop, $this->$prop);
		}
		return $return;
	}

	public function toObject()
	{
		$obj = (object) array();
		foreach (HPSIMVLANTable::getColumnNames() as $prop) {
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

	public function setId($id)
	{
		$this->id = $id;
	}

	public function getId()
	{
		return $this->id;
	}

	public function setName($name)
	{
		$this->name = $name;
	}

	public function getName()
	{
		return $this->name;
	}

	public function setNativeVlan($nativeVlan)
	{
		$this->nativeVlan = $nativeVlan;
	}

	public function getNativeVlan()
	{
		return $this->nativeVlan;
	}

	public function setPreferredSpeed($preferredSpeed)
	{
		$this->preferredSpeed = $preferredSpeed;
	}

	public function getPreferredSpeed()
	{
		return $this->preferredSpeed;
	}

	public function setPrivate($private)
	{
		$this->private = $private;
	}

	public function getPrivate()
	{
		return $this->private;
	}

	public function setSharedUplinkSet($sharedUplinkSet)
	{
		$this->sharedUplinkSet = $sharedUplinkSet;
	}

	public function getSharedUplinkSet()
	{
		return $this->sharedUplinkSet;
	}

	public function setStatus($status)
	{
		$this->status = $status;
	}

	public function getStatus()
	{
		return $this->status;
	}

	public function setSwitchId($switchId)
	{
		$this->switchId = $switchId;
	}

	public function getSwitchId()
	{
		return $this->switchId;
	}

	public function setVlanId($vlanId)
	{
		$this->vlanId = $vlanId;
	}

	public function getVlanId()
	{
		return $this->vlanId;
	}

    /**
     * @param mixed $distSwitchName
     * @return $this
     */
    public function setDistSwitchName($distSwitchName)
    {
        $this->distSwitchName = $distSwitchName;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDistSwitchName()
    {
        return $this->distSwitchName;
    }

}

