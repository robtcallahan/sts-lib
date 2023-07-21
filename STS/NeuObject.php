<?php

// declare our namespace
namespace STS;

// include our NeuConfig
use STS\Util\NeuConfig;

/**
 *
 */
class NeuObject
{

    /**
     * @var NeuConfig $_stsapps
     * @var NeuConfig $_stsapps
     */
    protected $_neumetric;
    protected $_opsCenter;
    protected $_stsapps;

    /**
	 * A method to create Config objects
	 */
	public function __construct() 
	{
	
	} // __construct()
	
	/**
	 * A method to destroy Config objects
	 */
	public function __destruct() 
	{
	
	} // __destruct()
	
	/**
	 * A method to print Config objects
	 */
	public function __toString() 
	{
	
	    return "";
	
	} // __toString()

} // class Config
