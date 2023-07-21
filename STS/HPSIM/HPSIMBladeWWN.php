<?php
/*******************************************************************************
 *
 * $Id: HPSIMBladeWWN.php 81891 2013-12-11 21:08:55Z rcallaha $
 * $Date: 2013-12-11 16:08:55 -0500 (Wed, 11 Dec 2013) $
 * $Author: rcallaha $
 * $Revision: 81891 $
 * $HeadURL: https://svn.ultradns.net/svn/sts_tools/sts-lib/trunk/STS/HPSIM/HPSIMBladeWWN.php $
 *
 *******************************************************************************
 */

namespace STS\HPSIM;

class HPSIMBladeWWN
{
	protected $id;
	protected $bladeId;

	protected $wwn;
	protected $port;
	protected $fabricName;
	protected $speed;
	protected $status;
    protected $mac;

	public function __toString()
	{
		$return = "";
		foreach (HPSIMBladeWWNTable::getColumnNames() as $prop) {
			$return .= sprintf("%-25s => %s\n", $prop, $this->$prop);
		}
		return $return;
	}

	public function toObject()
	{
		$obj = (object) array();
		foreach (HPSIMBladeWWNTable::getColumnNames() as $prop) {
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

	public function setBladeId($bladeId)
	{
		$this->bladeId = $bladeId;
	}

	public function getBladeId()
	{
		return $this->bladeId;
	}

	public function setFabricName($fabricName)
	{
		$this->fabricName = $fabricName;
	}

	public function getFabricName()
	{
		return $this->fabricName;
	}

	public function setId($id)
	{
		$this->id = $id;
	}

	public function getId()
	{
		return $this->id;
	}

	public function setPort($port)
	{
		$this->port = $port;
	}

	public function getPort()
	{
		return $this->port;
	}

	public function setSpeed($speed)
	{
		$this->speed = $speed;
	}

	public function getSpeed()
	{
		return $this->speed;
	}

	public function setStatus($status)
	{
		$this->status = $status;
	}

	public function getStatus()
	{
		return $this->status;
	}

	public function setWwn($wwn)
	{
		$this->wwn = $wwn;
	}

	public function getWwn()
	{
		return $this->wwn;
	}

    /**
     * @param mixed $mac
     * @return $this
     */
    public function setMac($mac)
    {
        $this->mac = $mac;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMac()
    {
        return $this->mac;
    }

}

