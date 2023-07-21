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



class CMDBSysJournalFieldListTable extends CMDBDAO
{
    protected static $nameMapping = array(

        'element'        => 'element',
        'element_id'     => 'elementId',
        'name'           => 'name',
        'sys_id'         => 'sysId',
        'value'          => 'value',
        'sys_created_by' => 'sysCreatedBy',
        'sys_created_on' => 'sysCreatedOn',
        "sys_updated_by" => "sysUpdatedBy",
        "sys_updated_on" => "sysUpdatedOn",
    );


    protected $ciTable;
    protected $format;
    

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
        $this->ciTable = "sys_journal_field_list";
        $this->format  = "JSON";
    }

    /**
     * @param  $elementId
     * @return mixed|CMDBSysJournalFieldList
     */
    public function getNotesByElementId($elementId)
    {
        $this->sysLog->debug("elementId=" . $elementId);
        $query  = "element_id={$elementId}";
        $result = $this->getRecords($this->ciTable, $query);
        
        return $result;
        
    }
    public function createCRNote($elementId, $note)
    {
        $this->sysLog->debug("elementId=" . $elementId);    
        $this->sysLog->debug("note=" . $note);
        
        $note['element'] = "work_notes";
        $note['element_id'] = $elementId;
        $note['name'] = "task";
        $note['value'] = $note;
        
        $result = parent::createCI($this->ciTable, json_encode($note));
        
        return $result;
        
    }

    // *******************************************************************************
    // * Getters and Setters
    // *****************************************************************************

    /**
     * @param $logLevel
     * @return $this|void
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

}
