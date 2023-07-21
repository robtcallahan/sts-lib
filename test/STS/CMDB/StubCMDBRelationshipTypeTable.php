<?php
/*******************************************************************************
 *
 * $Id: StubCMDBRelationshipTypeTable.php 79018 2013-09-19 00:06:03Z rcallaha $
 * $Date: 2013-09-18 20:06:03 -0400 (Wed, 18 Sep 2013) $
 * $Author: rcallaha $
 * $Revision: 79018 $
 * $HeadURL: https://svn.ultradns.net/svn/sts_tools/sts-lib/trunk/test/CMDB/StubCMDBRelationshipTypeTable.php $
 *
 *******************************************************************************
 */

class StubCMDBRelationshipTypeTable extends \STS\CMDB\CMDBRelationshipTypeTable
{
    public function __construct($useUserCredentials = false)
    {
        parent::__construct($useUserCredentials);
    }

    public function setSite($site)
    {
        parent::setSite($site);
        $this->curlInit();
    }

    protected function curlInit()
    {
        $this->curl = StubCurl::singleton();
        $this->curl->setUserPassword($this->username, $this->password);
    }
}

