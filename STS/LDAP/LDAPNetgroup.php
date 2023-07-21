<?php
/*******************************************************************************
 *
 * $Id: LDAPNetgroup.php 76358 2013-06-27 02:13:37Z rcallaha $
 * $Date: 2013-06-26 22:13:37 -0400 (Wed, 26 Jun 2013) $
 * $Author: rcallaha $
 * $Revision: 76358 $
 * $HeadURL: https://svn.ultradns.net/svn/sts_tools/sts-lib/trunk/STS/LDAP/LDAPNetgroup.php $
 *
 *******************************************************************************
 */

namespace STS\LDAP;

class LDAPNetgroup
{
    private $cn;
    private $description;
    private $neuOwner;
    private $nisNetgroupTriple;
    private $memberNisNetgroup;
    private $objectClass;

    /**
     * @return string
     */
    public function __toString()
   	{
   		$return = "";
   		foreach (LDAPNetgroupTable::getAttributes() as $prop) {
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
   		foreach (LDAPNetgroupTable::getAttributes() as $prop) {
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
     * @return $this
     */
    public function set($prop, $value)
   	{
   		$this->$prop = $value;
        return $this;
   	}

    /**
     * @param mixed $cn
     * @return $this
     */
    public function setCn($cn)
    {
        $this->cn = $cn;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCn()
    {
        return $this->cn;
    }

    /**
     * @param mixed $description
     * @return $this
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $memberNisNetgroup
     * @return $this
     */
    public function setMemberNisNetgroup($memberNisNetgroup)
    {
        $this->memberNisNetgroup = $memberNisNetgroup;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMemberNisNetgroup()
    {
        return $this->memberNisNetgroup;
    }

    /**
     * @param mixed $neuOwner
     * @return $this
     */
    public function setNeuOwner($neuOwner)
    {
        $this->neuOwner = $neuOwner;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNeuOwner()
    {
        return $this->neuOwner;
    }

    /**
     * @param mixed $nisNetgroupTriple
     * @return $this
     */
    public function setNisNetgroupTriple($nisNetgroupTriple)
    {
        $this->nisNetgroupTriple = $nisNetgroupTriple;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNisNetgroupTriple()
    {
        return $this->nisNetgroupTriple;
    }

    /**
     * @param mixed $objectClass
     * @return $this
     */
    public function setObjectClass($objectClass)
    {
        $this->objectClass = $objectClass;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getObjectClass()
    {
        return $this->objectClass;
    }

}