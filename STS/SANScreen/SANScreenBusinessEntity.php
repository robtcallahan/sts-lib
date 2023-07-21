<?php
/*******************************************************************************
 *
 * $Id: SANScreenArray.php 74756 2013-04-26 17:08:54Z rcallaha $
 * $Date: 2013-04-26 13:08:54 -0400 (Fri, 26 Apr 2013) $
 * $Author: rcallaha $
 * $Revision: 74756 $
 * $HeadURL: https://svn.ultradns.net/svn/sts_tools/sts-lib/trunk/STS/SANScreen/SANScreenArray.php $
 *
 *******************************************************************************
 */

class SANScreenBusinessEntity
{

    protected $id;
    protected $businessUnit;
	protected $defaultApplicationName;
	protected $displayName;
	protected $lineOfBusiness;
	protected $project;
    protected $tenant;

	public function __toString()
	{
		$return = "";
		foreach (self::getClassProperties() as $prop) {
			$return .= sprintf("%-25s => %s\n", $prop, $this->$prop);
		}
		return $return;
	}

	public function toObject()
	{
		$obj = (object) array();
        foreach (self::getClassProperties() as $prop) {
			$obj->$prop = $this->$prop;
		}
		return $obj;
	}

    public static function getClassProperties()
    {
        return array_keys(get_class_vars(__CLASS__));
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
     * @param mixed $businessUnit
     */
    public function setBusinessUnit($businessUnit)
    {
        $this->businessUnit = $businessUnit;
    }

    /**
     * @return mixed
     */
    public function getBusinessUnit()
    {
        return $this->businessUnit;
    }

    /**
     * @param mixed $defaultApplicationName
     */
    public function setDefaultApplicationName($defaultApplicationName)
    {
        $this->defaultApplicationName = $defaultApplicationName;
    }

    /**
     * @return mixed
     */
    public function getDefaultApplicationName()
    {
        return $this->defaultApplicationName;
    }

    /**
     * @param mixed $displayName
     */
    public function setDisplayName($displayName)
    {
        $this->displayName = $displayName;
    }

    /**
     * @return mixed
     */
    public function getDisplayName()
    {
        return $this->displayName;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $lineOfBusiness
     */
    public function setLineOfBusiness($lineOfBusiness)
    {
        $this->lineOfBusiness = $lineOfBusiness;
    }

    /**
     * @return mixed
     */
    public function getLineOfBusiness()
    {
        return $this->lineOfBusiness;
    }

    /**
     * @param mixed $project
     */
    public function setProject($project)
    {
        $this->project = $project;
    }

    /**
     * @return mixed
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * @param mixed $tenant
     */
    public function setTenant($tenant)
    {
        $this->tenant = $tenant;
    }

    /**
     * @return mixed
     */
    public function getTenant()
    {
        return $this->tenant;
    }

}

