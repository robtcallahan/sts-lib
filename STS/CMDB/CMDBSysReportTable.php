<?php
/*******************************************************************************
 *
 * $Id: CMDBSysReportTable.php 81814 2013-12-09 19:40:39Z rcallaha $
 * $Date: 2013-12-09 14:40:39 -0500 (Mon, 09 Dec 2013) $
 * $Author: rcallaha $
 * $Revision: 81814 $
 * $HeadURL: https://svn.ultradns.net/svn/sts_tools/sts-lib/trunk/STS/CMDB/CMDBSysReportTable.php $
 *
 *******************************************************************************
 */

namespace STS\CMDB;



class CMDBSysReportTable extends CMDBDAO
{

    protected static $reverseNameMapping = array();
    protected static $nameMapping = array(
        "sys_id"          => "sysId",

        "aggregate"       => "aggregate",
        "chart_size"      => "chartSize",
        "content"         => "content",
        "column"          => "column",
        "display_grid"    => "displayGrid",
        "field"           => "field",
        "field_list"      => "fieldList",
        "filter"          => "filter",
        "group"           => "group",
        "interval"        => "interval",
        "orderby_list"    => "orderByList",
        "other_threshold" => "otherThreshold",
        "others"          => "others",
        "roles"           => "roles",
        "row"             => "row",
        "show_empty"      => "showEmpty",
        "sumfield"        => "sumField",
        "sys_mod_count"   => "sysModCount",
        "table"           => "table",
        "title"           => "title",
        "trend_field"     => "trendField",
        "type"            => "type",
        "user"            => "user",

        "sys_created_by"  => "sysCreatedBy",
        "sys_created_on"  => "sysCreatedOn",
        "sys_updated_by"  => "sysUpdatedBy",
        "sys_updated_on"  => "sysUpdatedOn",
    );


    protected $ciTable;
    protected $format;
    protected $printResult = false;

    /**
     * @param mixed $arg
     */
    public function __construct($arg = null)
    {
        $useUserCredentials = false;
        $config = null;
        if (is_bool($arg)) {
            $useUserCredentials = $arg;
            parent::__construct($useUserCredentials);
        }
        else if (is_array($arg)) {
            $config = $arg;
            parent::__construct($config);
        } else {
            parent::__construct($useUserCredentials);
        }
        $this->sysLog->debug();

        // define CMDB table and return format
        $this->ciTable = "sys_report";
        $this->format  = "JSON";


        // create reverse name mapping hash
        foreach (self::$nameMapping as $cmdbProp => $thisProp) {
            self::$reverseNameMapping[$thisProp] = $cmdbProp;
        }
    }

    /**
     * @param      $sysId
     * @param bool $raw
     * @return mixed|CMDBSysReport
     */
    public function getById($sysId, $raw = false)
    {
        $this->sysLog->debug("sysId=" . $sysId);
        return $this->getBySysId($sysId, $raw);
    }

    /**
     * @param      $sysId
     * @param bool $raw
     * @return mixed|CMDBSysReport
     */
    public function getBySysId($sysId, $raw = false)
    {
        $this->sysLog->debug("sysId=" . $sysId);
        $query  = "sys_id={$sysId}";
        $result = $this->getRecord($this->ciTable, $query);
        if (!$raw) {
            return $this->_set($result);
        } else {
            return $result;
        }
    }

    /**
     * @param      $title
     * @return mixed|CMDBSysReport
     */
    public function getByTitle($title)
    {
        $this->sysLog->debug("title=" . $title);
        $query  = "title={$title}";
        $result = $this->getRecord($this->ciTable, $query);
        return $this->_set($result);
    }

    /**
     * @param $query
     * @return CMDBSysReport[]
     */
    public function getByQueryString($query)
    {
        $records = $this->getRecords($this->ciTable, $query);
        $array   = array();
        for ($i = 0; $i < count($records); $i++) {
            $array[] = $this->_set($records[$i]);
        }
        return $array;
    }

    /**
     * @param $sysId
     * @param $json
     * @return mixed|object
     */
    public function updateByJson($sysId, $json)
    {
        $this->sysLog->debug("json=" . $json);
        return parent::updateCI($this->ciTable, $sysId, $json);
    }

    /**
     * @param $json
     * @return mixed|object
     */
    public function createByJson($json)
    {
        $this->sysLog->debug("json=" . $json);
        return parent::createCI($this->ciTable, $json);
    }

    /**
     * @param CMDBSysReport $sysReport
     * @return CMDBSysReport
     * @throws \ErrorException
     */
    public function update(CMDBSysReport $sysReport)
    {
        $this->sysLog->debug();
        if (count($sysReport->getChanges()) > 0) {
            // build json
            $json = '';
            foreach ($sysReport->getChanges() as $prop => $o) {
                if (array_key_exists($prop, self::$reverseNameMapping)) {
                    if ($json != "") $json .= ',';
                    $json .= '"' . self::$reverseNameMapping[$prop] . '":"' . $o->modifiedValue . '"';
                } else {
                    throw new \ErrorException("Trying to set a non-existent property: " . $prop);
                }
            }
            $json = '{' . $json . '}';

            if (!property_exists($sysReport, 'sysId')) {
                throw new \ErrorException("Server instance does not have a sysId defined");
            }

            $sysReport->clearChanges();
            $this->updateByJson($sysReport->getSysId(), $json);
            return $this->getBySysId($sysReport->getSysId());
        } else {
            return $sysReport;
        }
    }

    /**
     * @param CMDBSysReport $sysReport
     * @return mixed|CMDBSysReport
     * @throws \ErrorException
     */
    public function create(CMDBSysReport $sysReport)
    {
        $this->sysLog->debug();
        if (count($sysReport->getChanges()) > 0) {
            // build json
            $json = '';
            foreach ($sysReport->getChanges() as $prop => $o) {
                if (array_key_exists($prop, self::$reverseNameMapping)) {
                    if ($json != "") $json .= ',';
                    $json .= '"' . self::$reverseNameMapping[$prop] . '":"' . $o->modifiedValue . '"';
                } else {
                    throw new \ErrorException("Trying to set a non-existent property: " . $prop);
                }
            }
            $json = '{' . $json . '}';

            $sysReport->clearChanges();
            $return = $this->createByJson($json);
            if (property_exists($return, 'records')) {
                if (array_key_exists(0, $return->records)) {
                    return $this->_set($return->records[0]);
                } else {
                    return $sysReport;
                }
            } else {
                return $this->getByTitle($sysReport->getTitle());
            }
        } else {
            return $sysReport;
        }
    }


    // *******************************************************************************
    // * Getters and Setters
    // *****************************************************************************

    /**
     * @param string $format
     */
    public function setFormat($format)
    {
        $this->format = $format;
    }

    /**
     * @return string
     */
    public function getFormat()
    {
        return $this->format;
    }

    /**
     * @param $logLevel
     * @return void
     */
    public function setLogLevel($logLevel)
    {
        $this->logLevel = $logLevel;
    }

    /**
     * @return mixed
     */
    public function getLogLevel()
    {
        return $this->logLevel;
    }

    /**
     * @return array
     */
    public static function getNameMapping()
    {
        return self::$nameMapping;
    }

    /**
     * @return array
     */
    public static function getReverseNameMapping()
    {
        return self::$reverseNameMapping;
    }

    /**
     * @param boolean $printResult
     * @return $this
     */
    public function setPrintResult($printResult)
    {
        $this->printResult = $printResult;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getPrintResult()
    {
        return $this->printResult;
    }

    /**
     * @param null $dbRowObj
     * @return CMDBSysReport
     */
    private function _set($dbRowObj = null)
    {
        $this->sysLog->debug();

        $o = new CMDBSysReport();
        foreach (self::$nameMapping as $cmdbProp => $modelProp) {
            if ($dbRowObj && property_exists($dbRowObj, $cmdbProp)) {
                $o->set($modelProp, $dbRowObj->$cmdbProp);
            } else {
                $o->set($modelProp, null);
            }
        }
        return $o;
    }

}
