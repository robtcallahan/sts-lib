<?php
/*******************************************************************************
 *
 * $Id: global.php 62033 2012-04-09 14:06:07Z rcallaha $
 * $Date: 2012-04-09 14:06:07 +0000 (Mon, 09 Apr 2012) $
 * $Author: rcallaha $
 * $Revision: 62033 $
 * $HeadURL: https://svn.ultradns.net/svn/sts_tools/sns/trunk/php/global.php $
 *
 *******************************************************************************
 */

define("DATADIR", __DIR__ . "/data");
define("CONFIGFILE", __DIR__ . "/../config/config.php");
define("MYSQLBIN", "/Applications/MAMP/Library/bin");

set_include_path(
    implode(':',
        array(
            __DIR__,
            __DIR__ . "/..",
            __DIR__ . "/STS/Util",
            __DIR__ . "/STS/CMDB",
            "/usr/share/php/ZF2/library",
            "/usr/share/pear"
        )
    )
);

// register our autoloader that replaces '\' with '/' and '_' with ''
spl_autoload_register(function ($className) {
    $className = (string)str_replace('\\', DIRECTORY_SEPARATOR, $className);
    $className = str_replace('_', DIRECTORY_SEPARATOR, $className);
    require_once($className . ".php");
});

