<?php
/*******************************************************************************
 *
 * $Id: LDAPGroups.php 76358 2013-06-27 02:13:37Z rcallaha $
 * $Date: 2013-06-26 22:13:37 -0400 (Wed, 26 Jun 2013) $
 * $Author: rcallaha $
 * $Revision: 76358 $
 * $HeadURL: https://svn.ultradns.net/svn/sts_tools/sts-lib/trunk/STS/LDAP/LDAPGroups.php $
 *
 *******************************************************************************
 */

namespace STS\LDAP;

class LDAPGroups
{
    private $cn;
    private $description;

    /**
     * @return string
     */
    public function __toString()
	{
		$return = "";
		foreach (LDAPGroupsTable::getAttributes() as $prop) {
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
		foreach (LDAPGroupsTable::getAttributes() as $prop) {
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
     * @param $cn
     */
    public function setCn($cn)
	{
		$this->cn = $cn;
	}

    /**
     * @return mixed
     */
    public function getCn()
	{
		return $this->cn;
	}

    /**
     * @param $description
     */
    public function setDescription($description)
	{
		$this->description = $description;
	}

    /**
     * @return mixed
     */
    public function getDescription()
	{
		return $this->description;
	}
}
