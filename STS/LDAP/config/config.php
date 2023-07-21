<?php
/*******************************************************************************
 *
 * $Id: config.php 74786 2013-04-29 17:12:48Z rcallaha $
 * $Date: 2013-04-29 13:12:48 -0400 (Mon, 29 Apr 2013) $
 * $Author: rcallaha $
 * $Revision: 74786 $
 * $HeadURL: https://svn.ultradns.net/svn/sts_tools/sts-lib/trunk/config/config.php $
 *
 *******************************************************************************
 */

return (object) array(
	// Application information
	"appName" => "STS/LDAP",
	"appID"   => "sts/ldap",
	"appDB"   => null,

	// who to email errors and such to
	"adminEmail"  => "Core Tools Group <coretoolsgroup@neustar.biz>",
	
	// log level not implemented...much
	"logLevel" => STS\Util\SysLog::DEBUG,
	
	// LDAP connection info
	"ldap" => (object) array(
		"site" => "prod",
        "prod"    => array(
            "baseDN"            => "ou=Employee,ou=User-Accounts,dc=cis,dc=neustar,dc=com",
            "host"              => "stscsprdsm10.va.neustar.com",
            //"host"            => "ldapauth-vip.neustar.biz",
            "port"              => 636,
            "binduser"          => "cn=glassproxy,ou=profile,o=neustar",
            "password"          => 'mbP^H&V *<6},@$',
            "accountDomainName" => "va.neustar.com",
		)
    )
);
