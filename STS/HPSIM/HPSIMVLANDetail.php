<?php
/*******************************************************************************
 *
 * $Id: HPSIMVLANDetail.php 82444 2014-01-03 14:28:59Z rcallaha $
 * $Date: 2014-01-03 09:28:59 -0500 (Fri, 03 Jan 2014) $
 * $Author: rcallaha $
 * $Revision: 82444 $
 * $HeadURL: https://svn.ultradns.net/svn/sts_tools/sts-lib/trunk/STS/HPSIM/HPSIMVLANDetail.php $
 *
 *******************************************************************************
 */

namespace STS\HPSIM;

class HPSIMVLANDetail
{
    protected $id;
    protected $vlanId;
    protected $distSwitchName;
    protected $ipSubnet;
    protected $subnetMask;
    protected $gateway;


    public function __toString()
    {
        $return = "";
        foreach (HPSIMVLANDetailTable::getColumnNames() as $prop) {
            $return .= sprintf("%-25s => %s\n", $prop, $this->$prop);
        }
        return $return;
    }

    public function toObject()
    {
        $obj = (object)array();
        foreach (HPSIMVLANDetailTable::getColumnNames() as $prop) {
            $obj->$prop = $this->get($prop);
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

    /**
     * @param mixed $gateway
     * @return $this
     */
    public function setGateway($gateway)
    {
        $this->gateway = $gateway;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getGateway()
    {
        return $this->gateway;
    }

    /**
     * @param mixed $ipSubnet
     * @return $this
     */
    public function setIpSubnet($ipSubnet)
    {
        $this->ipSubnet = $ipSubnet;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIpSubnet()
    {
        return $this->ipSubnet;
    }

    /**
     * @param mixed $subnetMask
     * @return $this
     */
    public function setSubnetMask($subnetMask)
    {
        $this->subnetMask = $subnetMask;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSubnetMask()
    {
        return $this->subnetMask;
    }

    /**
     * @param mixed $vlanId
     * @return $this
     */
    public function setVlanId($vlanId)
    {
        $this->vlanId = $vlanId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getVlanId()
    {
        return $this->vlanId;
    }

    /**
     * @param mixed $chassisId
     * @return $this
     */
    public function setChassisId($chassisId)
    {
        $this->chassisId = $chassisId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getChassisId()
    {
        return $this->chassisId;
    }

    /**
     * @param mixed $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

}
