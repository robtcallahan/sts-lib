<?php
/*******************************************************************************
 *
 * $Id: ADUserTable.php 82972 2014-02-05 16:22:58Z rcallaha $
 * $Date: 2014-02-05 11:22:58 -0500 (Wed, 05 Feb 2014) $
 * $Author: rcallaha $
 * $Revision: 82972 $
 * $HeadURL: https://svn.ultradns.net/svn/sts_tools/sts-lib/trunk/STS/AD/ADUserTable.php $
 *
 *******************************************************************************
 */

namespace STS\AD;

class ADUserTable extends ADDAO
{
	protected static $nameMapping = array(
		'givenName'                  => 'firstName',
		'sn'                         => 'lastName',
		'displayName'                => 'displayName',
		'cn'                         => 'cn',
		'postOfficeBox'              => 'empId',
		'sAMAccountName'             => 'uid',
		'distinguishedName'          => 'dn',
		'memberOf'                   => 'memberOf',
		'managedObjects'             => 'managedObjects',
		'streetAddress'              => 'street',
		'l'                          => 'city',
		'st'                         => 'state',
		'c'                          => 'country',
		'postalCode'                 => 'zip',
		'title'                      => 'title',
		'department'                 => 'dept',
		'company'                    => 'company',
		'description'                => 'descr',
		'physicalDeliveryOfficeName' => 'office',
		'mail'                       => 'email',
		'telephoneNumber'            => 'officePhone',
		'homePhone'                  => 'homePhone',
		'mobile'                     => 'mobilePhone',
		'manager'                    => 'managerDN',
	);

	protected $baseDn = "ou=User-Accounts,dc=cis,dc=neustar,dc=com";

	public function __construct($config = null)
	{
		parent::__construct($config);

		$this->sysLog->debug();
	}

	/**
	 * @param $uid
	 * @return ADUser
	 */
	public function getByUid($uid)
	{
		$this->sysLog->debug();

		$baseDN = "ou=User-Accounts,dc=cis,dc=neustar,dc=com";
		$filter = "(&(sAMAccountname={$uid}))";

		$record = $this->getRecord($baseDN, $filter);
		return $this->_set($record);
	}

	/**
	 * @param $cn
	 * @return ADUser
	 */
	public function getByCN($cn)
	{
		$this->sysLog->debug();

		$baseDN = "ou=Employee," . $this->baseDn;
		$filter = "(&(cn={$cn}))";

		$record = $this->getRecord($baseDN, $filter);
		return $this->_set($record);
	}

	/**
	 * @param $lastName
	 * @return array
	 */
	public function getByLastName($lastName)
	{
		$this->sysLog->debug();

		$baseDN = $this->baseDn;
		$filter = "(&(sn={$lastName}))";

		$entries  = $this->getRecords($baseDN, $filter);
		$accounts = array();
		for ($i = 0; $i < count($entries); $i++) {
			$accounts[] = $this->_set($entries[$i]);
		}
		return $accounts;
	}

	/**
	 * @return array
	 */
	public function getAllEmployees()
	{
		$this->sysLog->debug();

		$baseDN = "ou=Employee," . $this->baseDn;
		$filter = "(&(postofficebox=*))";

		$entries   = $this->getRecords($baseDN, $filter);
		$employees = array();
		for ($i = 0; $i < count($entries); $i++) {
			$employees[] = $this->_set($entries[$i]);
		}
		return $employees;
	}

	// *******************************************************************************
	// * Getters and Setters
	// *****************************************************************************

	/**
	 * @return array
	 */
	public static function getNameMapping()
	{
		return self::$nameMapping;
	}

	/**
	 * @param $dbRowObj
	 * @return ADUser
	 */
	private function _set($dbRowObj)
	{
		$this->sysLog->debug();

		if (!$dbRowObj) {
			$dbRowObj = (object) array();
		}

		$o = new ADUser();
		foreach (self::$nameMapping as $ldapProp => $modelProp) {
			if ($dbRowObj && property_exists($dbRowObj, $ldapProp)) {
				$o->set($modelProp, $dbRowObj->$ldapProp);
			}
			else {
				$o->set($modelProp, null);
			}
		}
		if ($o->getUid()) {
			$ldapProp = 'sAMAccountName';
			$o->setUserName($dbRowObj->$ldapProp);
		}
		else {
			$o->setUserName(null);
		}

		return $o;
	}
}
