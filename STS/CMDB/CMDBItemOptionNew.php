<?php
/*******************************************************************************
 *
 * $Id: CMDBItemOptionNew.php 79018 2013-09-19 00:06:03Z rcallaha $
 * $Date: 2013-09-18 20:06:03 -0400 (Wed, 18 Sep 2013) $
 * $Author: rcallaha $
 * $Revision: 79018 $
 * $HeadURL: https://svn.ultradns.net/svn/sts_tools/sts-lib/trunk/STS/CMDB/CMDBItemOptionNew.php $
 *
 *******************************************************************************
 */

namespace STS\CMDB;

class CMDBItemOptionNew
{
	protected $sysId;
	protected $sysClassName;

	protected $name;
    protected $type;
    protected $typeId;
    protected $variableSet;
    protected $variableSetId;
    protected $active;
    protected $questionText;

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
		foreach (CMDBItemOptionNewTable::getNameMapping() as $prop) {
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
		foreach (CMDBItemOptionNewTable::getNameMapping() as $prop) {
			$obj->$prop = $this->$prop;
		}
		return $obj;
	}


	// *******************************************************************************
	// * Getters and Setters
	// *****************************************************************************

	public function get($prop)
	{
		return $this->$prop;
	}

	public function set($prop, $value)
	{
		return $this->$prop = $value;
	}

    public function getChanges()
    {
        return $this->changes;
    }

    public function clearChanges()
    {
        $this->changes = array();
    }

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
     * @param mixed $active
     */
    public function setActive($active)
    {
        $this->updateChanges(func_get_arg(0));
        $this->active = $active;
    }

    /**
     * @return mixed
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * @param mixed $name
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
     * @param mixed $questionText
     */
    public function setQuestionText($questionText)
    {
        $this->updateChanges(func_get_arg(0));
        $this->questionText = $questionText;
    }

    /**
     * @return mixed
     */
    public function getQuestionText()
    {
        return $this->questionText;
    }

    /**
     * @param mixed $sysClassName
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
     * @param mixed $type
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
     * @param mixed $typeId
     */
    public function setTypeId($typeId)
    {
        $this->updateChanges(func_get_arg(0));
        $this->typeId = $typeId;
    }

    /**
     * @return mixed
     */
    public function getTypeId()
    {
        return $this->typeId;
    }

    /**
     * @param mixed $variableSet
     */
    public function setVariableSet($variableSet)
    {
        $this->updateChanges(func_get_arg(0));
        $this->variableSet = $variableSet;
    }

    /**
     * @return mixed
     */
    public function getVariableSet()
    {
        return $this->variableSet;
    }

    /**
     * @param mixed $variableSetId
     */
    public function setVariableSetId($variableSetId)
    {
        $this->updateChanges(func_get_arg(0));
        $this->variableSetId = $variableSetId;
    }

    /**
     * @return mixed
     */
    public function getVariableSetId()
    {
        return $this->variableSetId;
    }

}
