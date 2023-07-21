<?php

/*******************************************************************************
 *
 * $Id: DistSwitchLookup.php 77524 2013-08-05 16:53:18Z rcallaha $
 * $Date: 2013-08-05 12:53:18 -0400 (Mon, 05 Aug 2013) $
 * $Author: rcallaha $
 * $Revision: 77524 $
 * $HeadURL: https://svn.ultradns.net/svn/sts_tools/sts-lib/trunk/STS/Util/DistSwitchLookup.php $
 *
 *******************************************************************************
 */

namespace STS\Util;

use Net_IPv4;

class DistSwitchLookup
{
    private $switchMap;
    private $cidrMap;
    private $nameToDevice;

    private static $instance;

    protected $sysLog;
    protected $logLevel;

    public function __construct() {
        // Set up SysLog
        $this->sysLog   = SysLog::singleton($GLOBALS['config']->appName);
        $this->logLevel = $GLOBALS['config']->logLevel;
        $this->sysLog->debug();

        $this->switchMap = $this->getSwitchMap();

        // map a hash of CIDR to switch name from the map in the config file
        $this->cidrMap      = array();
        $this->nameToDevice = array();

        foreach ($this->switchMap as $switchName => $obj) {
            $this->nameToDevice[$switchName] = $obj->switches;
            $networks                        = $obj->networks;
            for ($i = 0; $i < count($networks); $i++) {
                $this->cidrMap[$networks[$i]] = $switchName;
            }
        }
    }

    /**
     * @return mixed
     */
    public static function singleton() {
        if (!self::$instance) {
            $c              = __CLASS__;
            self::$instance = new $c();
        }
        return self::$instance;
    }

    /**
     * @param $swName
     * @return array
     */
    public function getSwitchDeviceByName($swName) {
        $this->sysLog->debug();
        if (array_key_exists($swName, $this->nameToDevice)) {
            return $this->nameToDevice[$swName];
        } else {
            return array();
        }
    }

    /**
     * @param $ip
     * @param $switchName
     * @param $network
     * @return bool
     */
    public function getDistSwitchNameByIp($ip, &$switchName, &$network) {
        $this->sysLog->debug();
        $ipv4 = new Net_IPv4();
        foreach ($this->cidrMap as $net => $sw) {
            if ($ipv4->ipInNetwork($ip, $net)) {
                $switchName = $sw;
                $network    = $net;
                return true;
            }
        }
        $switchName = null;
        $network    = null;
        return false;
    }

    /**
     * @return object
     */
    public function getSwitchMap() {
        return (object)array(
            "Denver General purpose"        => (object)array(
                    "switches" => array(
                    ),
                    "networks" => array(
                    )
                ),
            "Sterling DECE Prod"        => (object)array(
                    "switches" => array(
                        "stde2960sa/b"
                    ),
                    "networks" => array(
                        "10.91.50.0/24",
                        "10.91.51.0/24",
                        "10.91.52.0/24",
                        "156.154.122.0"
                    )
                ),
            "Sterling DECE QA"          => (object)array(
                    "switches" => array(
                        "stdeq2960sa"
                    ),
                    "networks" => array(
                        "10.31.152.0/22",
                        "10.31.156.0/24",
                        "10.31.170.0/22"
                    )
                ),
            "Sterling RMS"              => (object)array(
                    "switches" => array(
                        "stfms3560sa/b"
                    ),
                    "networks" => array(
                        "10.91.48.0/24",
                        "10.91.37.0/24"
                    )
                ),
            "Sterling OMS"              => (object)array(
                    "switches" => array(
                        "stdn7010c/d"
                    ),
                    "networks" => array(
                        "10.31.6.168/29",
                        "10.31.16.0/27",
                        "10.31.16.32/27",
                        "10.31.16.128/26",
                        "10.31.16.192/26",
                        "10.31.19.0/24",
                        "10.31.21.0/24",

                        "10.41.1.0/24",
                        "10.41.4.0/24",
                        "10.41.8.0/24",
                        "10.41.9.0/24",

                        "10.61.1.0/24", // behind firewall
                        "10.61.1.32/28",
                        "10.61.2.0/24",
                        "10.61.2.1/24",
                        "10.61.3.0/24", // behind firewall
                        "10.61.4.0/24", // behind firewall
                        "10.61.5.0/27",
                        "10.61.6.144/28", // behind firewall
                        "10.61.9.0/24", // behind firewall
                        "10.61.10.0/24",
                        "10.61.11.0/24", // behind firewall
                        "10.61.12.0/24", // behind firewall

                        "156.154.33.0/24", // behind firewall

                        "209.173.61.0/24", // behind firewall
                    )
                ),
            "Sterling General Purpose"  => (object)array(
                    "switches" => array(
                        "stdn7010a/b"
                    ),
                    "networks" => array(
                        "10.31.0.40/29",
                        "10.31.0.64/27",
                        "10.31.0.216/29",
                        "10.31.6.224/29",
                        "10.31.10.192/26",
                        "10.31.11.0/30",
                        "10.31.12.0/24",
                        "10.31.13.0/24",
                        "10.31.15.0/24",
                        "10.31.18.0/24",
                        "10.31.20.0/24",
                        "10.31.22.0/24",
                        "10.31.23.128/25",
                        "10.31.38.0/24",
                        "10.31.44.0/24",
                        "10.31.45.0/24",
                        "10.31.45.32/27",
                        "10.31.40.96/27",
                        "10.31.40.128/27",
                        "10.31.40.160/27",
                        "10.31.40.192/27",
                        "10.31.47.128/25",
                        "10.31.50.0/24",
                        "10.31.51.0/24",
                        "10.31.52.0/22",
                        "10.31.56.0/27",
                        "10.31.56.32/27",
                        "10.31.56.64/27",
                        "10.31.56.128/28",
                        "10.31.58.0/24", // Added by Rob 7/17/2012
                        "10.31.59.0/24", // Added by Rob 7/17/2012
                        "10.31.66.0/26",
                        "10.31.66.240/29",
                        "10.31.87.0/24",
                        "10.31.80.0/22",
                        "10.31.88.0/24",
                        "10.31.105.0/24",
                        "10.31.110.0/24",
                        "10.31.124.0/24",
                        "10.31.133.0/24",
                        "10.31.139.0/24",
                        "10.31.190.0/26",
                        "10.31.190.64/28",
                        "10.31.190.96/28",
                        "10.31.190.128/25",
                        "10.31.191.0/24",
                        "10.31.200.0/21",
                        "10.31.208.0/24",
                        "10.31.254.0/27",

                        "10.41.3.224/28",
                        "10.41.3.240/28",

                        "209.173.51.0/26",
                    )
                ),
            "Sterling IHN"              => (object)array(
                    "switches" => array(
                        "stih3560a1/a2",
                        "stih4507a1/a2"
                    ),
                    "networks" => array(
                        "10.91.2.0/24",
                        "10.91.3.0/24",
                        "10.91.4.0/24",
                        "10.91.16.0/20",
                        "10.91.33.0/24",
                        "10.91.34.0/24",

                        "156.154.16.0/27",
                        "156.154.16.128/25",
                        "156.154.19.0/27",

                        "209.173.53.224/28",
                    )
                ),
            "Sterling Lab"              => (object)array(
                    "switches" => array(
                        "stlab7609ma",
                        "stlab7010a"
                    ),
                    "networks" => array(
                        "172.30.32.0/24"
                    )
                ),
            "Sterling LEAP"             => (object)array(
                    "switches" => array(
                        "stlp2960sa/b"
                    ),
                    "networks" => array(
                        "10.91.47.0/24"
                    )
                ),
            "Sterling Mobile Cloud"     => (object)array(
                    "switches" => array(
                        "stmc3560sa/b"
                    ),
                    "networks" => array(
                        "10.91.55.0/24"
                    )
                ),
            "Sterling NPAC"             => (object)array(
                    "switches" => array(
                        "stdn7010e/f"
                    ),
                    "networks" => array(
                        "10.31.0.16/28",
                        "10.31.6.40/29",
                        "10.31.6.80/29",
                        "10.31.8.96/28",
                        "10.31.120.0/24",
                        "10.31.121.0/24",
                        "10.31.123.0/24",
                        "10.31.125.0/24",
                        "10.31.128.0/23",
                        "10.31.127.0/24", // behind firewall
                        "10.31.130.0/23", // behind firewall
                        "10.31.132.0/24", // behind firewall
                    )
                ),
            "Sterling Registry"         => (object)array(
                    "switches" => array(
                        "stre7609ma/b"
                    ),
                    "networks" => array(
                        "10.31.6.112/30",
                        "10.31.6.116/30",
                        "10.31.6.120/30",
                        "10.31.6.124/30",

                        "10.51.12.0/24",

                        "10.91.128.0/28",
                        "10.91.128.16/28",
                        "10.91.128.48/28",
                        "10.91.129.0/24", // behind firewall
                        "10.91.130.0/24",
                        "10.91.131.0/24",
                        "10.91.132.0/24",
                        "10.91.133.0/24",
                        "10.91.136.0/24",
                        "10.91.137.0/24",
                        "10.91.138.0/24",
                        "10.91.142.0/24",

                        "192.168.32.136/29",
                    )
                ),
            "Sterling Ultra Lab"        => (object)array(
                    "switches" => array(
                        "undefined"
                    ),
                    "networks" => array(
                        "10.31.141.0/24",
                        "10.31.142.0/24",
                    )
                ),
            "Charlotte DECE Prod"       => (object)array(
                    "switches" => array(
                        "chde2960sa/b"
                    ),
                    "networks" => array(
                        "10.90.50.0/24",
                        "10.90.51.0/24",
                        "10.90.52.0/24",

                        "156.154.123.0",
                    )
                ),
            "Charlotte DECE QA"         => (object)array(
                    "switches" => array(
                        "chdeq2960sa"
                    ),
                    "networks" => array(
                        "10.30.152.0/22",
                        "10.30.156.0/24",
                    )
                ),
            "Charlotte General Purpose" => (object)array(
                    "switches" => array(
                        "chco6509a/b"
                    ),
                    "networks" => array(
                        "10.30.6.64/28",
                        "10.30.10.192/26",
                        "10.30.23.128/25",
                        "10.30.47.128/25",

                        "10.32.5.0/28",
                        "10.32.8.48/29",
                        "10.32.8.56/29",
                        "10.32.8.112/30",
                        "10.32.8.116/30",
                        "10.32.44.0/24",
                        "10.32.50.0/24",
                        "10.32.52.0/22",
                        "10.32.58.0/24", // Added by Rob 7/17/2013
                        "10.32.66.0/26",
                        "10.32.81.0/24",
                        "10.32.90.0/24",
                        "10.32.91.0/24",
                        "10.32.133.0/24",
                        "10.32.135.0/24",
                        "10.32.150.96/27",
                        "10.32.181.96/27",
                        "10.32.253.0/24",

                        "192.168.32.0/28",
                        "192.168.32.48/28",
                        "192.168.32.64/28",
                        "192.168.32.192/28",
                    )
                ),
            "Charlotte IHN"             => (object)array(
                    "switches" => array(
                        "chih3560a1/a2",
                        "chih3560b1/b2",
                        "chih3560c1/c2"
                    ),
                    "networks" => array(
                        "10.90.2.0/24",
                        "10.90.3.0/24",
                        "10.90.4.0/24",
                        "10.90.16.0/20",
                        "10.90.33.0/24",
                        "10.90.34.0/24",

                        "156.154.24.0/27",
                        "156.154.24.128/25",

                        "209.173.57.224/28",
                    )
                ),
            "Charlotte NPAC"            => (object)array(
                    "switches" => array(
                        "chdn7010c/d"
                    ),
                    "networks" => array(
                        "1.1.1.0/24",

                        "10.32.6.40/29",
                        "10.32.6.80/29",
                        "10.32.32.80/29",
                        "10.32.120.0/24",
                        "10.32.121.0/24",
                        "10.32.123.0/24",
                        "10.32.125.0/24",
                        "10.32.129.0/24",
                        "10.32.130.0/23", // behind firewall
                        "10.32.132.0/24", // behind firewall
                    )
                ),
            "Charlotte OMS"             => (object)array(
                    "switches" => array(
                        "chdn7010a/b" // was chdn6509mb
                    ),
                    "networks" => array(
                        "10.30.6.224/28",
                        "10.30.16.0/27",
                        "10.30.16.32/27",
                        "10.30.16.128/26",
                        "10.30.16.192/26",
                        "10.30.25.0/24",
                        "10.30.40.64/27",
                        "10.30.40.160/27",
                        "10.30.40.128/27",
                        "10.30.47.0/25",

                        "10.32.6.8/29",
                        "10.32.6.16/29",
                        "10.32.12.0/24",
                        "10.32.19.0/24",
                        "10.32.28.0/29",
                        "10.32.32.0/28",
                        "10.32.32.64/29",
                        "10.32.32.72/29",
                        "10.32.51.0/24",
                        "10.32.56.32/27",

                        "10.42.1.0/24",
                        "10.42.4.0/24",
                        "10.42.8.0/24",
                        "10.42.9.0/24",

                        "10.62.1.0", // behind firewall
                        "10.62.1.4/30",
                        "10.62.1.8/30",
                        "10.62.2.0/24",
                        "10.62.4.0", // behind firewall
                        "10.62.5.0/24",
                        "10.62.6.128/28", // behind firewall
                        "10.62.8.0/24",
                        "10.62.9.0/25", // behind firewall
                        "10.62.10.0/24",
                        "10.62.11.0/24", // changed by Rob 7/17/2012
                    )
                ),
            "Charlotte Registry"        => (object)array(
                    "switches" => array(
                        "chre7609ma/b"
                    ),
                    "networks" => array(
                        "10.32.6.112/30",
                        "10.32.6.116/30",
                        "10.32.6.120/30",
                        "10.32.6.124/30",
                        "10.32.6.128/30",

                        "10.90.128.0/28",
                        "10.90.128.16/28",
                        "10.90.128.48/28",
                        "10.90.129.0/24", // behind firewall
                        "10.90.130.0/24",
                        "10.90.131.0/24",
                        "10.90.132.0/24",
                        "10.90.133.0/24",
                        "10.90.136.0/24",
                        "10.90.137.0/24",
                        "10.90.138.0/24",
                        "10.90.142.0/24",
                    )
                )
        );
    }
}