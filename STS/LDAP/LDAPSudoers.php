<?php
/*******************************************************************************
 *
 * $Id: LDAPSudoers.php 76358 2013-06-27 02:13:37Z rcallaha $
 * $Date: 2013-06-26 22:13:37 -0400 (Wed, 26 Jun 2013) $
 * $Author: rcallaha $
 * $Revision: 76358 $
 * $HeadURL: https://svn.ultradns.net/svn/sts_tools/sts-lib/trunk/STS/LDAP/LDAPSudoers.php $
 *
 *******************************************************************************
 */

namespace STS\LDAP;

class LDAPSudoers
{
    private $cn;
    private $description;
    private $sudoCommand = array();
    private $sudoHost = array();
    private $sudoUser = array();
    private $sudoOption;
    private $sudoRunAs;

    /**
     * @return string
     */
    public function __toString()
   	{
   		$return = "";
   		foreach (LDAPSudoersTable::getAttributes() as $prop) {
            if (is_array($this->$prop)) {
                $propArray = $this->$prop;
                $return .= sprintf("%-25s => \n", $prop);
                for ($i=0; $i<count($propArray); $i++) {
                    $return .= sprintf("%-29s%s\n", " ", $propArray[$i]);
                }
            } else {
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
   		$obj = (object) array();
   		foreach (LDAPSudoersTable::getAttributes() as $prop) {
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
     * @param string $cn
     */
    public function setCn($cn)
    {
        $this->cn = $cn;
    }

    /**
     * @return string
     */
    public function getCn()
    {
        return $this->cn;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

     /**
     * @param array $sudoUserArray
     */
    public function setSudoUser($sudoUserArray)
    {
        $this->sudoUser = $sudoUserArray;
    }

    /**
     * @return array
     */
    public function getSudoUser()
    {
        return $this->sudoUser;
    }

    /**
     * @param string $uid
     */
    public function addSudoUser($uid)
    {
        // check for existence first
        $return = array_search($uid, $this->sudoUser, true);
        if ($return == false) {
            array_push($this->sudoUser, $uid);
        }
    }

    /**
     * @param string $uid
     */
    public function removeSudoUser($uid)
    {
        // check for existence first
        $return = array_search($uid, $this->sudoUser, true);
        if ($return != false) {
            array_splice($this->sudoUser, $return, 1);
        }
    }

    /**
     * @param string $sudoRunAs
     */
    public function setSudoRunAs($sudoRunAs)
    {
        $this->sudoRunAs = $sudoRunAs;
    }

    /**
     * @return string
     */
    public function getSudoRunAs()
    {
        return $this->sudoRunAs;
    }

    /**
     * @param string $sudoOption
     */
    public function setSudoOption($sudoOption)
    {
        $this->sudoOption = $sudoOption;
    }

    /**
     * @return string
     */
    public function getSudoOption()
    {
        return $this->sudoOption;
    }

    /**
     * @param array $sudoCommandArray
     */
    public function setSudoCommand($sudoCommandArray)
    {
        $this->sudoCommand = $sudoCommandArray;
    }

    /**
     * @return array
     */
    public function getSudoCommand()
    {
        return $this->sudoCommand;
    }

    /**
     * @param string $command
     */
    public function addSudoCommand($command)
    {
        // check for existence first
        $return = array_search($command, $this->sudoCommand, true);
        if ($return == false) {
            array_push($this->sudoCommand, $command);
        }
    }

    /**
     * @param string $command
     */
    public function removeSudoCommand($command)
    {
        // check for existence first
        $return = array_search($command, $this->sudoCommand, true);
        if ($return != false) {
            array_splice($this->sudoCommand, $return, 1);
        }
    }

    /**
     * @param array $sudoHostArray
     */
    public function setSudoHost($sudoHostArray)
    {
        $this->sudoHost = $sudoHostArray;
    }



    /**
     * @return array
     */
    public function getSudoHost()
    {
        return $this->sudoHost;
    }

    /**
     * @param string $hostName
     */
    public function addSudoHost($hostName)
    {
        // check for existence first
        $return = array_search($hostName, $this->sudoHost, true);
        if ($return == false) {
            array_push($this->sudoHost, $hostName);
        }
    }

    /**
     * @param string $hostName
     */
    public function removeSudoHost($hostName)
    {
        // check for existence first
        $return = array_search($hostName, $this->sudoHost, true);
        if ($return != false) {
            array_splice($this->sudoHost, $return, 1);
        }
    }

}