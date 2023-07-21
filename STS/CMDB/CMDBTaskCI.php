<?php
/*******************************************************************************
 *
 * $Id: CMDBTaskCI.php 79018 2013-09-19 00:06:03Z rcallaha $
 * $Date: 2013-09-18 20:06:03 -0400 (Wed, 18 Sep 2013) $
 * $Author: rcallaha $
 * $Revision: 79018 $
 * $HeadURL: https://svn.ultradns.net/svn/sts_tools/sts-lib/trunk/STS/CMDB/CMDBTaskCI.php $
 *
 *******************************************************************************
 */

namespace STS\CMDB;

class CMDBTaskCI
{
	protected $sysId;

	protected $taskId;
	protected $task;
	protected $ciItemId;
	protected $ciItem;

	protected $applied;

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
		foreach (CMDBTaskCITable::getNameMapping() as $prop) {
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
		foreach (CMDBTaskCITable::getNameMapping() as $prop) {
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
     * @param $applied
     */
    public function setApplied($applied)
	{
        $this->updateChanges(func_get_arg(0));
		$this->applied = $applied;
	}

    /**
     * @return mixed
     */
    public function getApplied()
	{
		return $this->applied;
	}

    /**
     * @param $ciItem
     */
    public function setCiItem($ciItem)
	{
        $this->updateChanges(func_get_arg(0));
		$this->ciItem = $ciItem;
	}

    /**
     * @return mixed
     */
    public function getCiItem()
	{
		return $this->ciItem;
	}

    /**
     * @param $ciItemId
     */
    public function setCiItemId($ciItemId)
	{
        $this->updateChanges(func_get_arg(0));
		$this->ciItemId = $ciItemId;
	}

    /**
     * @return mixed
     */
    public function getCiItemId()
	{
		return $this->ciItemId;
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
     * @param $task
     */
    public function setTask($task)
	{
        $this->updateChanges(func_get_arg(0));
		$this->task = $task;
	}

    /**
     * @return mixed
     */
    public function getTask()
	{
		return $this->task;
	}

    /**
     * @param $taskId
     */
    public function setTaskId($taskId)
	{
        $this->updateChanges(func_get_arg(0));
		$this->taskId = $taskId;
	}

    /**
     * @return mixed
     */
    public function getTaskId()
	{
		return $this->taskId;
	}

}
