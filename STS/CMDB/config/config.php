<?php
/*******************************************************************************
 *
 * $Id: config.php 73888 2013-04-03 17:59:52Z rcallaha $
 * $Date: 2013-04-03 13:59:52 -0400 (Wed, 03 Apr 2013) $
 * $Author: rcallaha $
 * $Revision: 73888 $
 * $HeadURL: https://svn.ultradns.net/svn/sts_tools/sts-lib/trunk/config/config.php $
 *
 *******************************************************************************
 */

return array(
    // Application information
    "appName"    => "STS/CMDB",
    "appID"      => "sts/cmdb",
    "appDB"      => null,

    // who to email errors and such to
    "adminEmail" => "Core Tools Group <coretoolsgroup@neustar.biz>",

    // log level not implemented...much
    "logLevel"   => STS\Util\SysLog::NOTICE,

    "testField"  => "testValue",

    // Service Now connection info
    "servicenow" => array(
            "site" => "prod",

            "prod" => array(
                    "protocol" => "https",
                    "username" => "stsapp",
                    "password" => 'Z;$o(c`#X7)+>ItZ',
                    "server"   => "neustar.service-now.com"
                ),
            "prodjson" => array(
                    "protocol" => "https",
                    "username" => "stsapp",
                    "password" => 'Z;$o(c`#X7)+>ItZ',
                    "server"   => "neustar.service-now.com/api/now/table/"
                ),
            "test" => array(
                    "protocol" => "https",
                    "username" => "stsapp",
                    "password" => 'Z;$o(c`#X7)+>ItZ',
                    "server"   => "neustartest.service-now.com"
                ),
            "dev"  => array(
                    "protocol" => "https",
                    "username" => "stsapp",
                    "password" => 'Z;$o(c`#X7)+>ItZ',
                    "server"   => "neustardev.service-now.com"
                ),
            "int"  => array(
                    "protocol" => "https",
                    "username" => "stsapp",
                    "password" => 'Z;$o(c`#X7)+>ItZ',
                    "server"   => "neustarint.service-now.com"
                ),
            "dev2" => array(
                    "protocol" => "https",
                    "username" => "stsapp",
                    "password" => 'Z;$o(c`#X7)+>ItZ',
                    "server"   => "neustardev2.service-now.com"
                )
        )

);
