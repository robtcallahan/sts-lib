<?php
/*******************************************************************************
 *
 * $Id: CMDBCI.php 75922 2013-06-12 01:15:03Z rcallaha $
 * $Date: 2013-06-11 21:15:03 -0400 (Tue, 11 Jun 2013) $
 * $Author: rcallaha $
 * $Revision: 75922 $
 * $HeadURL: https://svn.ultradns.net/svn/sts_tools/sts-lib/trunk/STS/CMDB/CMDBCI.php $
 *
 *******************************************************************************
 */

namespace STS\CMDB;

class CMDBScItemOption
{
	protected $sysId;

    protected $value;
    protected $cartItem;
    protected $itemOptionNew;
    protected $scCatItemOption;


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
		foreach (CMDBScItemOptionTable::getNameMapping() as $prop) {
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
		foreach (CMDBScItemOptionTable::getNameMapping() as $prop) {
			$obj->$prop = $this->$prop;
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
     * @param mixed $cartItem
     */
    public function setCartItem($cartItem)
    {
        $this->updateChanges(func_get_arg(0));
        $this->cartItem = $cartItem;
    }

    /**
     * @return mixed
     */
    public function getCartItem()
    {
        return $this->cartItem;
    }

    /**
     * @param mixed $itemOptionNew
     */
    public function setItemOptionNew($itemOptionNew)
    {
        $this->updateChanges(func_get_arg(0));
        $this->itemOptionNew = $itemOptionNew;
    }

    /**
     * @return mixed
     */
    public function getItemOptionNew()
    {
        return $this->itemOptionNew;
    }

    /**
     * @param mixed $scCatItemOption
     */
    public function setScCatItemOption($scCatItemOption)
    {
        $this->updateChanges(func_get_arg(0));
        $this->scCatItemOption = $scCatItemOption;
    }

    /**
     * @return mixed
     */
    public function getScCatItemOption()
    {
        return $this->scCatItemOption;
    }

    /**
     * @param mixed $sysCreatedBy
     */
    public function setSysCreatedBy($sysCreatedBy)
    {
        $this->updateChanges(func_get_arg(0));
        $this->sysCreatedBy = $sysCreatedBy;
    }

    /**
     * @return mixed
     */
    public function getSysCreatedBy()
    {
        return $this->sysCreatedBy;
    }

    /**
     * @param mixed $sysCreatedOn
     */
    public function setSysCreatedOn($sysCreatedOn)
    {
        $this->updateChanges(func_get_arg(0));
        $this->sysCreatedOn = $sysCreatedOn;
    }

    /**
     * @return mixed
     */
    public function getSysCreatedOn()
    {
        return $this->sysCreatedOn;
    }

    /**
     * @param mixed $sysId
     */
    public function setSysId($sysId)
    {
        $this->updateChanges(func_get_arg(0));
        $this->sysId = $sysId;
    }

    /**
     * @return mixed
     */
    public function getSysId()
    {
        return $this->sysId;
    }

    /**
     * @param mixed $sysUpdatedBy
     */
    public function setSysUpdatedBy($sysUpdatedBy)
    {
        $this->updateChanges(func_get_arg(0));
        $this->sysUpdatedBy = $sysUpdatedBy;
    }

    /**
     * @return mixed
     */
    public function getSysUpdatedBy()
    {
        return $this->sysUpdatedBy;
    }

    /**
     * @param mixed $sysUpdatedOn
     */
    public function setSysUpdatedOn($sysUpdatedOn)
    {
        $this->updateChanges(func_get_arg(0));
        $this->sysUpdatedOn = $sysUpdatedOn;
    }

    /**
     * @return mixed
     */
    public function getSysUpdatedOn()
    {
        return $this->sysUpdatedOn;
    }

    /**
     * @param mixed $value
     */
    public function setValue($value)
    {
        $this->updateChanges(func_get_arg(0));
        $this->value = $value;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

}
