<?php
/*******************************************************************************
 *
 * $Id: PostgresDB.php 76808 2013-07-15 19:16:25Z rcallaha $
 * $Date: 2013-07-15 15:16:25 -0400 (Mon, 15 Jul 2013) $
 * $Author: rcallaha $
 * $Revision: 76808 $
 * $HeadURL: https://svn.ultradns.net/svn/sts_tools/sts-lib/trunk/STS/DB/PostgresDB.php $
 *
 *******************************************************************************
 */

namespace STS\DB;

use STS\Util\Obfuscation;
use STS\Util\SysLog;

class PostgresDB implements DB
{
    private $config;
    private $link;
    
	protected $sysLog;
	protected $logLevel;

    public function __construct($config)
   	{
       #print print_r($config, true); exit;
       $this->config = $config['databases'][$config['dbIndex']];

   		// Set up SysLog
   		$this->sysLog   = SysLog::singleton($config['appName']);
   		$this->logLevel = $config['logLevel'];
   		$this->sysLog->debug();
   	}

	/**
	 * @throws
	 */
	public function connect()
    {
	    $this->sysLog->debug();
  		$crypt = new Obfuscation();
        
        $connString = "host={$this->config['server']} dbname={$this->config['database']} user={$this->config['username']} password=" . $crypt->decrypt($this->config['password']);

        if (isset($this->config['port']))
        {
        	$connString .= " port={$this->config['port']}";
        }
        $this->link = pg_pconnect($connString);
        if (!$this->link) 
        {
        	throw new \ErrorException("Could not connect to PostgreSQL database " . pg_last_error());
        }
    }

	/**
	 * @throws \ErrorException
	 */
	public function errno()
	{
		throw new \ErrorException("Function not implemented");
	}

	/**
	 * @return string
	 */
	public function error()
	{
		return pg_last_error();
	}

	/**
	 * @param $string
	 * @return string
	 */
	public function escape_string($string)
	{
		return pg_escape_string($this->link, $string);
	}

	/**
	 * @param $sql
	 * @return resource|string
	 * @throws \ErrorException
	 */
	public function query($sql)
	{
        if ($sql == "") return "";
        try
        {
        	$result = pg_query($this->link, $sql);
        	if (!$result) {
        		throw new \ErrorException("SQL failed on PostgreSQL database. Query: {$sql}. PostgreSQL Error: " . $this->error());
        	}
        	return $result;
        }
        catch (\Exception $e)
        {
        	throw new \ErrorException($e->getMessage() . "\nsql={$sql}", $e->getCode(), 0, $e->getFile(), $e->getLine());
        }
	}

	/**
	 * @param $result
	 * @return array
	 */
	public function fetch_array($result)
	{
		return pg_fetch_array($result);
	}

	/**
	 * @param $result
	 * @return array
	 */
	public function fetch_row($result)
	{
		return pg_fetch_row($result);
	}

	/**
	 * @param $result
	 * @return array
	 */
	public function fetch_assoc($result)
	{
		return pg_fetch_assoc($result);
	}

	/**
	 * @param $result
	 * @return object
	 */
	public function fetch_object($result)
	{
		return pg_fetch_object($result);
	}

	/**
	 * @param $result
	 * @return int
	 */
	public function num_rows($result)
	{
		return pg_num_rows($result);
	}

	/**
	 * @return bool
	 */
	public function close()
	{
		return pg_close($this->link);
	}

	/**
	 * @throws \ErrorException
	 */
	public function getInsertId()
	{
		//throw new \ErrorException("Function not implemented");
        return null;
	}

	/**
	 * @param string $sql
	 * @return array|string
	 */
	public function getRow($sql="")
    {
        if ($sql == "") return "";
        $result = $this->query($sql);
        return $this->fetch_assoc($result);
    }

	/**
	 * @param string $sql
	 * @return object|string
	 */
	public function getObject($sql="")
    {
        if ($sql == "") return "";
        
        $result = $this->query($sql);
        return $this->fetch_object($result);
    }

	/**
	 * @param string $sql
	 * @return array
	 */
	public function getAllRows($sql = "")
    {
        $rows = array();
        if ($sql == "") return $rows;
        
        $result = $this->query($sql);
        while ($hash = $this->fetch_assoc($result)) {
            $rows[] = $hash;
        }
        return($rows);
    }

	/**
	 * @param string $sql
	 * @return array
	 */
	public function getAllObjects($sql = "")
    {
        $rows = array();
        if ($sql == "") return $rows;
        
        $result = $this->query($sql);
        while ($hash = $this->fetch_object($result)) {
            $rows[] = $hash;
        }
        return($rows);
    }    
}   
