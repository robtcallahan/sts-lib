<?php
/*******************************************************************************
 *
 * $Id: StubCMDBServerTable.php 78897 2013-09-13 15:46:04Z rcallaha $
 * $Date: 2013-09-13 11:46:04 -0400 (Fri, 13 Sep 2013) $
 * $Author: rcallaha $
 * $Revision: 78897 $
 * $HeadURL: https://svn.ultradns.net/svn/sts_tools/sts-lib/trunk/test/CMDB/StubCMDBServerTable.php $
 *
 *******************************************************************************
 */

class StubCMDBRequestTable extends \STS\CMDB\CMDBRequestTable
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

