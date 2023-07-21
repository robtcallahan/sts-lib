<?php
/*******************************************************************************
 *
 * $Id: StubCMDBServerTable.php 79019 2013-09-19 01:14:27Z rcallaha $
 * $Date: 2013-09-18 21:14:27 -0400 (Wed, 18 Sep 2013) $
 * $Author: rcallaha $
 * $Revision: 79019 $
 * $HeadURL: https://svn.ultradns.net/svn/sts_tools/sts-lib/trunk/test/CMDB/StubCMDBServerTable.php $
 *
 *******************************************************************************
 */

class StubCMDBServerTable extends \STS\CMDB\CMDBServerTable
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

