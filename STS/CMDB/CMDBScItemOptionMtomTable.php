<?php
/*******************************************************************************
 *
 * $Id: CMDBList.php 73209 2013-03-14 18:18:55Z rcallaha $
 * $Date: 2013-03-14 14:18:55 -0400 (Thu, 14 Mar 2013) $
 * $Author: rcallaha $
 * $Revision: 73209 $
 * $HeadURL: https://svn.ultradns.net/svn/sts_tools/sts-lib/trunk/STS/CMDB/CMDBList.php $
 *
 *******************************************************************************
 */

namespace STS\CMDB;

class CMDBScItemOptionMtomTable extends CMDBDAO
{
    protected static $nameMapping = array(
        'sys_id'          => 'sysId',
        'dv_request_item' => 'requestItem',
        'request_item'    => 'requestItemId',
        'sc_item_option'  => 'scItemOption', // the sysId pointing to the entry in the sc_item_option table (dependent item)

        "sys_created_by"  => "sysCreatedBy",
        "sys_created_on"  => "sysCreatedOn",
        "sys_updated_by"  => "sysUpdatedBy",
        "sys_updated_on"  => "sysUpdatedOn",
    );

    protected $ciTable;

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

		$this->ciTable   = "sc_item_option_mtom";
	}

    /**
     * @param $id
     * @param bool $raw
     * @return mixed|CMDBScItemOptionMtom
     */
    public function getById($id, $raw=false)
    {
        $this->sysLog->debug("id=" . $id);
        return $this->getBySysId($id, $raw);
    }

    /**
     * @param $sysId
     * @param bool $raw
     * @return mixed|CMDBScItemOptionMtom
     */
    public function getBySysId($sysId, $raw = false)
    {
        $this->sysLog->debug("sysId=" . $sysId);
        $query = "sys_id={$sysId}";
        $result = $this->getRecord($this->ciTable, $query);
        if (!$raw) {
            return $this->_set($result);
        } else {
            return $result;
        }
    }

    /**
     * @param $requestItemId
     * @param bool $raw
     * @return mixed|CMDBScItemOptionMtom
     */
    public function getByRequestItemId($requestItemId, $raw = false)
    {
        $this->sysLog->debug("requestItemId=" . $requestItemId);
        $query = "request_item=" . $requestItemId;
        $result = $this->getRecord($this->ciTable, $query);
        if (!$raw) {
            return $this->_set($result);
        } else {
            return $result;
        }
    }

    /**
     * @param $scItemOption
     * @param bool $raw
     * @return mixed|CMDBScItemOptionMtom
     */
    public function getByScItemOption($scItemOption, $raw = false)
    {
        $this->sysLog->debug("scItemOption=" . $scItemOption);
        $query = "sc_item_option=" . $scItemOption;
        $result = $this->getRecord($this->ciTable, $query);
        if (!$raw) {
            return $this->_set($result);
        } else {
            return $result;
        }
    }

    /**
     * @param string $requestItemId
     * @param string $questionText
     * @return CMDBScItemOptionMtom
     */
    public function getVariableRecordByRequestItemIdAndTextName($requestItemId, $questionText)
    {
        $this->sysLog->debug("requestItemId=" . $requestItemId);
        $query = "request_item=" . $requestItemId . "^sc_item_option.item_option_new.question_text=" . $questionText;
        $result = $this->getRecord($this->ciTable, $query);
        return $this->_set($result);
    }

    /**
     * @param $requestItemId
     * @return CMDBScItemOptionMtom
     */
    public function getTimeFrameVariableRecordByRequestItemId($requestItemId)
    {
        $this->sysLog->debug("requestItemId=" . $requestItemId);
        $query = "request_item=" . $requestItemId . "^sc_item_option.item_option_new.question_text=Timeframe";
        $result = $this->getRecord($this->ciTable, $query);
        return $this->_set($result);
    }

    /**
     * @param $query
     * @param bool $raw
     * @return array|CMDBScItemOptionMtom[]
     */
    public function getByQueryString($query, $raw = false)
    {
        $this->sysLog->debug("query=" . $query);
        $records = $this->getRecords($this->ciTable, $query);
        if (!$raw) {
            $objects = array();
            for ($i = 0; $i < count($records); $i++) {
                $objects[] = $this->_set($records[$i]);
            }
            return $objects;
        } else {
            return $records;
        }
    }


	// *******************************************************************************
	// * Getters and Setters
	// *****************************************************************************

	/**
	 * @param $logLevel
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
	 * @param null $dbRowObj
	 * @return CMDBScItemOptionMtom
	 */
	private function _set($dbRowObj = null)
	{
		$this->sysLog->debug();

		$o = new CMDBScItemOptionMtom();
		foreach (self::$nameMapping as $cmdbProp => $modelProp) {
			if ($dbRowObj && property_exists($dbRowObj, $cmdbProp)) {
				$o->set($modelProp, $dbRowObj->$cmdbProp);
			}
			else {
				$o->set($modelProp, null);
			}
		}
		return $o;
	}
}
