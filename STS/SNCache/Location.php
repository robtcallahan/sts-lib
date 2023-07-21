<?php

namespace STS\SNCache;

class Location
{
	protected $sysId;
	protected $sysClassName;

	protected $name;

	protected $street;
	protected $city;
	protected $state;
	protected $zip;
	protected $type;

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
     * @param $zip
     */
    public function setZip($zip)
	{
        $this->updateChanges(func_get_arg(0));
		$this->zip = $zip;
	}

    /**
     * @return mixed
     */
    public function getZip()
	{
		return $this->zip;
	}

    /**
     * @param $city
     */
    public function setCity($city)
	{
        $this->updateChanges(func_get_arg(0));
		$this->city = $city;
	}

    /**
     * @return mixed
     */
    public function getCity()
	{
		return $this->city;
	}

    /**
     * @param $name
     */
    public function setName($name)
	{
        $this->updateChanges(func_get_arg(0));
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
     * @param $street
     */
    public function setStreet($street)
	{
        $this->updateChanges(func_get_arg(0));
		$this->street = $street;
	}

    /**
     * @return mixed
     */
    public function getStreet()
	{
		return $this->street;
	}

    /**
     * @param $sysClassName
     */
    public function setSysClassName($sysClassName)
	{
        $this->updateChanges(func_get_arg(0));
		$this->sysClassName = $sysClassName;
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
     * @param $sysCreatedOn
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
     * @param $sysId
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
     * @param $id
     */
    public function setId($id)
	{
        $this->updateChanges(func_get_arg(0));
		$this->sysId = $id;
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
     * @param $sysUpdatedOn
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
     * @param $type
     */
    public function setType($type)
	{
        $this->updateChanges(func_get_arg(0));
		$this->type = $type;
	}

    /**
     * @return mixed
     */
    public function getType()
	{
		return $this->type;
	}

    /**
     * @param $state
     */
    public function setState($state)
	{
        $this->updateChanges(func_get_arg(0));
		$this->state = $state;
	}

    /**
     * @return mixed
     */
    public function getState()
	{
		return $this->state;
	}

}
