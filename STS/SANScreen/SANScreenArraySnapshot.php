<?php

namespace STS\SANScreen;

class SANScreenArraySnapshot
{
	protected $id;
	protected $dateStamp;

	protected $arrayName;
    protected $sanName;
    protected $tier;

    protected $rawTb;
	protected $useableTb;
    protected $provisionedTb;
    protected $availableTb;

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
		foreach (SANScreenArraySnapshotTable::getColumnNames() as $prop) {
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
		foreach (SANScreenArraySnapshotTable::getColumnNames() as $prop) {
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
     * @param mixed $arrayName
     * @return $this
     */
    public function setArrayName($arrayName) {
        $this->updateChanges(func_get_arg(0));
        $this->arrayName = $arrayName;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getArrayName() {
        return $this->arrayName;
    }

    /**
     * @param mixed $availableTb
     * @return $this
     */
    public function setAvailableTb($availableTb) {
        $this->updateChanges(func_get_arg(0));
        $this->availableTb = $availableTb;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAvailableTb() {
        return $this->availableTb;
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
    public function getDateStamp() {
        return $this->dateStamp;
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
     * @param mixed $provisionedTb
     * @return $this
     */
    public function setProvisionedTb($provisionedTb) {
        $this->updateChanges(func_get_arg(0));
        $this->provisionedTb = $provisionedTb;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getProvisionedTb() {
        return $this->provisionedTb;
    }

    /**
     * @param mixed $sanName
     * @return $this
     */
    public function setSanName($sanName) {
        $this->updateChanges(func_get_arg(0));
        $this->sanName = $sanName;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSanName() {
        return $this->sanName;
    }

    /**
     * @param mixed $tier
     * @return $this
     */
    public function setTier($tier) {
        $this->updateChanges(func_get_arg(0));
        $this->tier = $tier;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTier() {
        return $this->tier;
    }

    /**
     * @param mixed $useableTb
     * @return $this
     */
    public function setUseableTb($useableTb) {
        $this->updateChanges(func_get_arg(0));
        $this->useableTb = $useableTb;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUseableTb() {
        return $this->useableTb;
    }

    /**
     * @param mixed $rawTb
     * @return $this
     */
    public function setRawTb($rawTb) {
        $this->updateChanges(func_get_arg(0));
        $this->rawTb = $rawTb;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRawTb() {
        return $this->rawTb;
    }

}

