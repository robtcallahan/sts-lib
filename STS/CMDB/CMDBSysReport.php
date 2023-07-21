<?php
/*******************************************************************************
 *
 * $Id: CMDBSysReport.php 79018 2013-09-19 00:06:03Z rcallaha $
 * $Date: 2013-09-18 20:06:03 -0400 (Wed, 18 Sep 2013) $
 * $Author: rcallaha $
 * $Revision: 79018 $
 * $HeadURL: https://svn.ultradns.net/svn/sts_tools/sts-lib/trunk/STS/CMDB/CMDBSysReport.php $
 *
 *******************************************************************************
 */

namespace STS\CMDB;

class CMDBSysReport
{
	protected $sysId;

    protected $aggregate;
    protected $chartSize;
    protected $content;
    protected $column;
    protected $displayGrid;
    protected $field;
    protected $fieldList;
    protected $filter;
    protected $group;
    protected $interval;
    protected $orderByList;
    protected $otherThreshold;
    protected $others;
    protected $roles;
    protected $row;
    protected $showEmpty;
    protected $sumField;
    protected $sysModCount;
    protected $table;
    protected $title;
    protected $trendField;
    protected $type;
    protected $user;

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
		foreach (CMDBSysReportTable::getNameMapping() as $prop) {
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
		foreach (CMDBSysReportTable::getNameMapping() as $prop) {
			$obj->$prop = $this->$prop;
		}
		return $obj;
	}


	// *******************************************************************************
	// * Getters and Setters
	// *****************************************************************************

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
     * @param $prop
     * @param $value
     */
    public function setChanges($prop, $value)
    {
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
        $this->setChanges($prop, $value);
    }

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
     * @param mixed $aggregate
     */
    public function setAggregate($aggregate)
    {
        $this->updateChanges(func_get_arg(0));
        $this->aggregate = $aggregate;
    }

    /**
     * @return mixed
     */
    public function getAggregate()
    {
        return $this->aggregate;
    }

    /**
     * @param mixed $chartSize
     */
    public function setChartSize($chartSize)
    {
        $this->updateChanges(func_get_arg(0));
        $this->chartSize = $chartSize;
    }

    /**
     * @return mixed
     */
    public function getChartSize()
    {
        return $this->chartSize;
    }

    /**
     * @param mixed $column
     */
    public function setColumn($column)
    {
        $this->updateChanges(func_get_arg(0));
        $this->column = $column;
    }

    /**
     * @return mixed
     */
    public function getColumn()
    {
        return $this->column;
    }

    /**
     * @param mixed $content
     */
    public function setContent($content)
    {
        $this->updateChanges(func_get_arg(0));
        $this->content = $content;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param mixed $displayGrid
     */
    public function setDisplayGrid($displayGrid)
    {
        $this->updateChanges(func_get_arg(0));
        $this->displayGrid = $displayGrid;
    }

    /**
     * @return mixed
     */
    public function getDisplayGrid()
    {
        return $this->displayGrid;
    }

    /**
     * @param mixed $field
     */
    public function setField($field)
    {
        $this->updateChanges(func_get_arg(0));
        $this->field = $field;
    }

    /**
     * @return mixed
     */
    public function getField()
    {
        return $this->field;
    }

    /**
     * @param mixed $fieldList
     */
    public function setFieldList($fieldList)
    {
        $this->updateChanges(func_get_arg(0));
        $this->fieldList = $fieldList;
    }

    /**
     * @return mixed
     */
    public function getFieldList()
    {
        return $this->fieldList;
    }

    /**
     * @param mixed $filter
     */
    public function setFilter($filter)
    {
        $this->updateChanges(func_get_arg(0));
        $this->filter = $filter;
    }

    /**
     * @return mixed
     */
    public function getFilter()
    {
        return $this->filter;
    }

    /**
     * @param mixed $group
     */
    public function setGroup($group)
    {
        $this->updateChanges(func_get_arg(0));
        $this->group = $group;
    }

    /**
     * @return mixed
     */
    public function getGroup()
    {
        return $this->group;
    }

    /**
     * @param mixed $interval
     */
    public function setInterval($interval)
    {
        $this->updateChanges(func_get_arg(0));
        $this->interval = $interval;
    }

    /**
     * @return mixed
     */
    public function getInterval()
    {
        return $this->interval;
    }

    /**
     * @param mixed $orderByList
     */
    public function setOrderByList($orderByList)
    {
        $this->updateChanges(func_get_arg(0));
        $this->orderByList = $orderByList;
    }

    /**
     * @return mixed
     */
    public function getOrderByList()
    {
        return $this->orderByList;
    }

    /**
     * @param mixed $otherThreshold
     */
    public function setOtherThreshold($otherThreshold)
    {
        $this->updateChanges(func_get_arg(0));
        $this->otherThreshold = $otherThreshold;
    }

    /**
     * @return mixed
     */
    public function getOtherThreshold()
    {
        return $this->otherThreshold;
    }

    /**
     * @param mixed $others
     */
    public function setOthers($others)
    {
        $this->updateChanges(func_get_arg(0));
        $this->others = $others;
    }

    /**
     * @return mixed
     */
    public function getOthers()
    {
        return $this->others;
    }

    /**
     * @param mixed $roles
     */
    public function setRoles($roles)
    {
        $this->updateChanges(func_get_arg(0));
        $this->roles = $roles;
    }

    /**
     * @return mixed
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * @param mixed $row
     */
    public function setRow($row)
    {
        $this->updateChanges(func_get_arg(0));
        $this->row = $row;
    }

    /**
     * @return mixed
     */
    public function getRow()
    {
        return $this->row;
    }

    /**
     * @param mixed $showEmpty
     */
    public function setShowEmpty($showEmpty)
    {
        $this->updateChanges(func_get_arg(0));
        $this->showEmpty = $showEmpty;
    }

    /**
     * @return mixed
     */
    public function getShowEmpty()
    {
        return $this->showEmpty;
    }

    /**
     * @param mixed $sumField
     */
    public function setSumField($sumField)
    {
        $this->updateChanges(func_get_arg(0));
        $this->sumField = $sumField;
    }

    /**
     * @return mixed
     */
    public function getSumField()
    {
        return $this->sumField;
    }

    /**
     * @param mixed $sysModCount
     */
    public function setSysModCount($sysModCount)
    {
        $this->updateChanges(func_get_arg(0));
        $this->sysModCount = $sysModCount;
    }

    /**
     * @return mixed
     */
    public function getSysModCount()
    {
        return $this->sysModCount;
    }

    /**
     * @param mixed $table
     */
    public function setTable($table)
    {
        $this->updateChanges(func_get_arg(0));
        $this->table = $table;
    }

    /**
     * @return mixed
     */
    public function getTable()
    {
        return $this->table;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->updateChanges(func_get_arg(0));
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $trendField
     */
    public function setTrendField($trendField)
    {
        $this->updateChanges(func_get_arg(0));
        $this->trendField = $trendField;
    }

    /**
     * @return mixed
     */
    public function getTrendField()
    {
        return $this->trendField;
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
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->updateChanges(func_get_arg(0));
        $this->user = $user;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

}
