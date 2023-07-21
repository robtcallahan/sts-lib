<?php
/*******************************************************************************
 *
 * $Id: ADUser.php 75251 2013-05-14 20:50:39Z rcallaha $
 * $Date: 2013-05-14 16:50:39 -0400 (Tue, 14 May 2013) $
 * $Author: rcallaha $
 * $Revision: 75251 $
 * $HeadURL: https://svn.ultradns.net/svn/sts_tools/sts-lib/trunk/STS/AD/ADUser.php $
 *
 *******************************************************************************
 */

namespace STS\AD;

class ADUser
{
    protected $firstName;        # givenName
    protected $lastName;         # sn

    protected $displayName;      # displayName
    protected $cn;               # cn
    protected $empId;            # postOfficeBox
    protected $userName;         # sAMAccountName
    protected $uid;              # sAMAccountName
    protected $dn;               # distinguishedName
    protected $memberOf;         # memberOf (array)
    protected $managedObjects;   # managedObjects (array)
    
    protected $street;           # streetAddress
    protected $city;             # l
    protected $state;            # st
    protected $country;          # c
    protected $zip;              # postalCode

    protected $title;            # title
    protected $dept;             # department
    protected $company;          # company
    protected $descr;            # description
	protected $office;           # physicalDeliveryOfficeName
	
    protected $email;            # mail
	protected $officePhone;      # telephoneNumber
	protected $homePhone;        # homePhone
	protected $mobilePhone;      # mobile

	protected $managerDN;        # manager [CN=Salinger\, Geoff,OU=Employee,OU=User-Accounts,DC=cis,DC=neustar,DC=com]


	public function __toString()
	{
		$return = "";
		foreach (ADUserTable::getNameMapping() as $prop) {
			$return .= sprintf("%-25s => %s\n", $prop, $this->$prop);
		}
		return $return;
	}

	public function toObject()
	{
		$obj = (object) array();
		foreach (ADUserTable::getNameMapping() as $prop) {
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

	public function setCity($city)
	{
		$this->city = $city;
	}

	public function getCity()
	{
		return $this->city;
	}

	public function setCn($cn)
	{
		$this->cn = $cn;
	}

	public function getCn()
	{
		return $this->cn;
	}

	public function setCompany($company)
	{
		$this->company = $company;
	}

	public function getCompany()
	{
		return $this->company;
	}

	public function setCountry($country)
	{
		$this->country = $country;
	}

	public function getCountry()
	{
		return $this->country;
	}

	public function setDept($dept)
	{
		$this->dept = $dept;
	}

	public function getDept()
	{
		return $this->dept;
	}

	public function setDescr($descr)
	{
		$this->descr = $descr;
	}

	public function getDescr()
	{
		return $this->descr;
	}

	public function setDisplayName($displayName)
	{
		$this->displayName = $displayName;
	}

	public function getDisplayName()
	{
		return $this->displayName;
	}

	public function setDn($dn)
	{
		$this->dn = $dn;
	}

	public function getDn()
	{
		return $this->dn;
	}

	public function setEmail($email)
	{
		$this->email = $email;
	}

	public function getEmail()
	{
		return $this->email;
	}

	public function setEmpId($empId)
	{
		$this->empId = $empId;
	}

	public function getEmpId()
	{
		return $this->empId;
	}

	public function setFirstName($firstName)
	{
		$this->firstName = $firstName;
	}

	public function getFirstName()
	{
		return $this->firstName;
	}

	public function setHomePhone($homePhone)
	{
		$this->homePhone = $homePhone;
	}

	public function getHomePhone()
	{
		return $this->homePhone;
	}

	public function setLastName($lastName)
	{
		$this->lastName = $lastName;
	}

	public function getLastName()
	{
		return $this->lastName;
	}

	public function setManagedObjects($managedObjects)
	{
		$this->managedObjects = $managedObjects;
	}

	public function getManagedObjects()
	{
		return $this->managedObjects;
	}

	public function setManagerDN($managerDN)
	{
		$this->managerDN = $managerDN;
	}

	public function getManagerDN()
	{
		return $this->managerDN;
	}

	public function setMemberOf($memberOf)
	{
		$this->memberOf = $memberOf;
	}

	public function getMemberOf()
	{
		return $this->memberOf;
	}

	public function setMobilePhone($mobilePhone)
	{
		$this->mobilePhone = $mobilePhone;
	}

	public function getMobilePhone()
	{
		return $this->mobilePhone;
	}

	public function setOffice($office)
	{
		$this->office = $office;
	}

	public function getOffice()
	{
		return $this->office;
	}

	public function setOfficePhone($officePhone)
	{
		$this->officePhone = $officePhone;
	}

	public function getOfficePhone()
	{
		return $this->officePhone;
	}

	public function setState($state)
	{
		$this->state = $state;
	}

	public function getState()
	{
		return $this->state;
	}

	public function setStreet($street)
	{
		$this->street = $street;
	}

	public function getStreet()
	{
		return $this->street;
	}

	public function setTitle($title)
	{
		$this->title = $title;
	}

	public function getTitle()
	{
		return $this->title;
	}

	public function setUid($uid)
	{
		$this->uid = $uid;
	}

	public function getUid()
	{
		return $this->uid;
	}

	public function setUserName($userName)
	{
		$this->userName = $userName;
	}

	public function getUserName()
	{
		return $this->userName;
	}

	public function setZip($zip)
	{
		$this->zip = $zip;
	}

	public function getZip()
	{
		return $this->zip;
	}
}
