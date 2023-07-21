<?php

namespace STS\HPSIM;

class HPSIMBladeSnapshotAggregate
{
    protected $id;
    protected $dateStamp;
	protected $distSwitchName;
	protected $businessService;
    protected $builds;
    protected $decoms;

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
     * @param mixed $businessService
     * @return $this
     */
    public function setBusinessService($businessService) {
        $this->updateChanges(func_get_arg(0));
        $this->businessService = $businessService;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getBusinessService() {
        return $this->businessService;
    }

    /**
     * @return mixed
     */
    public function getDateStamp() {
        return $this->dateStamp;
    }

    /**
     * @param mixed $dateStamp
     * @return $this
     */
    public function setDateStamp($dateStamp) {
        $this->updateChanges(func_get_arg(0));
        $this->dateStamp = $dateStamp;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDistSwitchName() {
        return $this->distSwitchName;
    }

    /**
     * @param mixed $distSwitchName
     * @return $this
     */
    public function setDistSwitchName($distSwitchName) {
        $this->updateChanges(func_get_arg(0));
        $this->distSwitchName = $distSwitchName;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getBuilds() {
        return $this->builds;
    }

    /**
     * @param mixed $builds
     * @return $this
     */
    public function setBuilds($builds) {
        $this->updateChanges(func_get_arg(0));
        $this->builds = $builds;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDecoms() {
        return $this->decoms;
    }

    /**
     * @param mixed $decoms
     * @return $this
     */
    public function setDecoms($decoms) {
        $this->updateChanges(func_get_arg(0));
        $this->decoms = $decoms;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getId() {
        return $this->id;
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


}
