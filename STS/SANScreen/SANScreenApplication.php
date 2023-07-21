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

class SANScreenApplication
{

    protected $id;
    protected $name;
	protected $businessEntityId;
	protected $priority;
	protected $ignoreSharing;
	protected $hostIds;

	public function __toString()
	{
		$return = "";
        foreach (self::getClassProperties() as $prop) {
            if ($prop != "hostIds") {
                $return .= sprintf("%-25s => %s\n", $prop, $this->$prop);
            } else {
                $return .= sprintf("%-25s => %s\n", $prop, count($this->hostIds));
                foreach ($this->hostIds as $hostId) {
                    $return .= sprintf("%-25s => %s\n", "", $hostId);
                }
            }
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
        if ($prop == "hostIds") {
            $this->hostIds = array();
            if (!is_array($value) && $value) {
                $this->hostIds = array($value);
            } else {
                $this->hostIds = $value;
            }
        } else {
            $this->$prop = $value;
        }
	}

    /**
     * @param mixed $businessEntityId
     */
    public function setBusinessEntityId($businessEntityId)
    {
        $this->businessEntityId = $businessEntityId;
    }

    /**
     * @return mixed
     */
    public function getBusinessEntityId()
    {
        return $this->businessEntityId;
    }

    /**
     * @param mixed $hostIds
     */
    public function setHostIds($hostIds)
    {
        $this->hostIds = $hostIds;
    }

    /**
     * @return mixed
     */
    public function getHostIds()
    {
        return $this->hostIds;
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
     * @param mixed $ignoreSharing
     */
    public function setIgnoreSharing($ignoreSharing)
    {
        $this->ignoreSharing = $ignoreSharing;
    }

    /**
     * @return mixed
     */
    public function getIgnoreSharing()
    {
        return $this->ignoreSharing;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
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
     * @param mixed $priority
     */
    public function setPriority($priority)
    {
        $this->priority = $priority;
    }

    /**
     * @return mixed
     */
    public function getPriority()
    {
        return $this->priority;
    }

}

