<?php

/*******************************************************************************
 *
 * $Id: StubCMDBDAO.php 78866 2013-09-12 22:09:42Z rcallaha $
 * $Date: 2013-09-12 18:09:42 -0400 (Thu, 12 Sep 2013) $
 * $Author: rcallaha $
 * $Revision: 78866 $
 * $HeadURL: https://svn.ultradns.net/svn/sts_tools/sts-lib/trunk/test/CMDB/StubCMDBDAO.php $
 *
 *******************************************************************************
 */

/**
 * Class StubCMDBDAO
 *
 * This class overwrites CMDBDAO setSite() and curlInit() methods to use the StubCurl class
 * which does not perform actual curl_execs for testing purposes
 */
class StubCMDBDAO extends STS\CMDB\CMDBDAO
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
