<?php
/*******************************************************************************
 *
 * $Id: HPSIMBladeException.php 82460 2014-01-04 15:38:17Z rcallaha $
 * $Date: 2014-01-04 10:38:17 -0500 (Sat, 04 Jan 2014) $
 * $Author: rcallaha $
 * $Revision: 82460 $
 * $HeadURL: https://svn.ultradns.net/svn/sts_tools/sts-lib/trunk/STS/HPSIM/HPSIMBladeException.php $
 *
 *******************************************************************************
 */

namespace STS\HPSIM;

class HPSIMBladeException
{
	protected $id;
	protected $bladeId;

	protected $exceptionTypeId;

    protected $errorText;

	protected $exceptionTypeNumber;
	protected $exceptionTypeDescr;

	protected $dateUpdated;
	protected $userUpdated;


    /**
     * @return string
     */
    public function __toString()
	{
		$return = "";
		foreach (HPSIMBladeExceptionTable::getColumnNames() as $prop) {
			$return .= sprintf("%-25s => %s\n", $prop, $this->$prop);
		}
		return $return;
	}

    /**
     * @return object
     */
    public function toObject()
	{
		$obj = (object) array();
		foreach (HPSIMBladeExceptionTable::getColumnNames() as $prop) {
			$obj->$prop = $this->get($prop);
		}
		return $obj;
	}

	// *******************************************************************************
	// Getters and Setters
	// *******************************************************************************

    /**
     * @param $prop
     * @return mixed
     */
    public function get($prop)
	{
		return $this->$prop;
	}

    /**
     * @param $prop
     * @param $value
     * @return $this
     */
    public function set($prop, $value)
	{
		$this->$prop = $value;
        return $this;
	}

    /**
     * @param $bladeId
     * @return $this
     */
    public function setBladeId($bladeId)
	{
		$this->bladeId = $bladeId;
        return $this;
	}

    /**
     * @return mixed
     */
    public function getBladeId()
	{
		return $this->bladeId;
	}

    /**
     * @param $dateUpdated
     * @return $this
     */
    public function setDateUpdated($dateUpdated)
	{
		$this->dateUpdated = $dateUpdated;
        return $this;
	}

    /**
     * @return mixed
     */
    public function getDateUpdated()
	{
		return $this->dateUpdated;
	}

    /**
     * @param $exceptionTypeDescr
     * @return $this
     */
    public function setExceptionTypeDescr($exceptionTypeDescr)
	{
		$this->exceptionTypeDescr = $exceptionTypeDescr;
        return $this;
	}

    /**
     * @return mixed
     */
    public function getExceptionTypeDescr()
	{
		return $this->exceptionTypeDescr;
	}

    /**
     * @param $exceptionTypeId
     * @return $this
     */
    public function setExceptionTypeId($exceptionTypeId)
	{
		$this->exceptionTypeId = $exceptionTypeId;
        return $this;
	}

    /**
     * @return mixed
     */
    public function getExceptionTypeId()
	{
		return $this->exceptionTypeId;
	}

    /**
     * @param $exceptionTypeNumber
     * @return $this
     */
    public function setExceptionTypeNumber($exceptionTypeNumber)
	{
		$this->exceptionTypeNumber = $exceptionTypeNumber;
        return $this;
	}

    /**
     * @return mixed
     */
    public function getExceptionTypeNumber()
	{
		return $this->exceptionTypeNumber;
	}

    /**
     * @param $id
     * @return $this
     */
    public function setId($id)
	{
		$this->id = $id;
        return $this;
	}

    /**
     * @return mixed
     */
    public function getId()
	{
		return $this->id;
	}

    /**
     * @param $userUpdated
     * @return $this
     */
    public function setUserUpdated($userUpdated)
	{
		$this->userUpdated = $userUpdated;
        return $this;
	}

    /**
     * @return mixed
     */
    public function getUserUpdated()
	{
		return $this->userUpdated;
	}

    /**
     * @param mixed $errorText
     * @return $this
     */
    public function setErrorText($errorText)
    {
        $this->errorText = $errorText;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getErrorText()
    {
        return $this->errorText;
    }

}
