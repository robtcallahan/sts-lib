<?php
/*******************************************************************************
 *
 * $Id: SANScreenSwitch.php 74756 2013-04-26 17:08:54Z rcallaha $
 * $Date: 2013-04-26 13:08:54 -0400 (Fri, 26 Apr 2013) $
 * $Author: rcallaha $
 * $Revision: 74756 $
 * $HeadURL: https://svn.ultradns.net/svn/sts_tools/sts-lib/trunk/STS/SANScreen/SANScreenSwitch.php $
 *
 *******************************************************************************
 */

namespace STS\SANScreen;

class SANScreenVolumeMask
{
	protected $id;
    protected $arrayId;
	protected $volumeId;
	protected $initiatorPortOrNodeWwn;
	protected $protocolController;
	protected $storagePortId;
	protected $storagePortWwn;

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
     * @param mixed $id
     * @return $this
     */
    public function setId($id)
    {
        $this->updateChanges(func_get_arg(0));
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

    /**
     * @param mixed $initiatorPortOrNodeWwn
     * @return $this
     */
    public function setInitiatorPortOrNodeWwn($initiatorPortOrNodeWwn)
    {
        $this->updateChanges(func_get_arg(0));
        $this->initiatorPortOrNodeWwn = $initiatorPortOrNodeWwn;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getInitiatorPortOrNodeWwn()
    {
        return $this->initiatorPortOrNodeWwn;
    }

    /**
     * @param mixed $protocolController
     * @return $this
     */
    public function setProtocolController($protocolController)
    {
        $this->updateChanges(func_get_arg(0));
        $this->protocolController = $protocolController;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getProtocolController()
    {
        return $this->protocolController;
    }

    /**
     * @param mixed $storagePortId
     * @return $this
     */
    public function setStoragePortId($storagePortId)
    {
        $this->updateChanges(func_get_arg(0));
        $this->storagePortId = $storagePortId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getStoragePortId()
    {
        return $this->storagePortId;
    }

    /**
     * @param mixed $storagePortWwn
     * @return $this
     */
    public function setStoragePortWwn($storagePortWwn)
    {
        $this->updateChanges(func_get_arg(0));
        $this->storagePortWwn = $storagePortWwn;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getStoragePortWwn()
    {
        return $this->storagePortWwn;
    }

    /**
     * @param mixed $volumeId
     * @return $this
     */
    public function setVolumeId($volumeId)
    {
        $this->updateChanges(func_get_arg(0));
        $this->volumeId = $volumeId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getVolumeId()
    {
        return $this->volumeId;
    }

    /**
     * @param mixed $arrayId
     * @return $this
     */
    public function setArrayId($arrayId)
    {
        $this->updateChanges(func_get_arg(0));
        $this->arrayId = $arrayId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getArrayId()
    {
        return $this->arrayId;
    }

}