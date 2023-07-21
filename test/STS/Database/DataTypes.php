<?php
/*******************************************************************************
 *
 * $Id: User.php 74756 2013-04-26 17:08:54Z rcallaha $
 * $Date: 2013-04-26 13:08:54 -0400 (Fri, 26 Apr 2013) $
 * $Author: rcallaha $
 * $Revision: 74756 $
 * $HeadURL: https://svn.ultradns.net/svn/sts_tools/sts-lib/trunk/STS/Login/User.php $
 *
 *******************************************************************************
 */


class DataTypes
{
    // column names
    protected $id;

    protected $colVarcharNotNull;
    protected $colVarcharNull;
    protected $colCharNotNull;
    protected $colCharNull;

    protected $colFloatNotNull;
    protected $colFloatNull;
    protected $colDoubleNotNull;
    protected $colDoubleNull;
    protected $colDecimalNotNull;
    protected $colDecimalNull;

    protected $colDateNotNull;
    protected $colDateNull;
    protected $colDatetimeNotNull;
    protected $colDatetimeNull;
    protected $colTimestampNotNull;
    protected $colTimestampNull;
    protected $colTimeNotNull;
    protected $colTimeNull;

    protected $colEnumNotNull;
    protected $colEnumNull;

    protected $colBlobNotNull;
    protected $colBlobNull;

    // meta array to keep track of changed properties
    protected $changes = array();

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
     * @return mixed
     */
    public function set($prop, $value)
    {
        $this->$prop = $value;
        return $this;
    }

    /**
     * @return array
     */
    public function getChanges()
    {
        return $this->changes;
    }

    /**
     *
     */
    public function clearChanges()
    {
        $this->changes = array();
    }

    /**
     * @param $value
     */
    private function updateChanges($value)
    {
        $trace = debug_backtrace();

        // get the calling method name, eg., setSysId
        $callerMethod = $trace[1]["function"];

        // perform a replace to remove "set" from the method name and change first letter to lowercase
        // so, setSysId becomes sysId. This will be the property name that needs to be added to the changes array
        $prop = preg_replace_callback(
            "/^set(\w)/",
            function ($matches) {
                return strtolower($matches[1]);
            },
            $callerMethod
        );

        // update the changes array to keep track of this properties orig and new values
        if (!array_key_exists($prop, $this->changes)) {
            $this->changes[$prop] = (object)array(
                'originalValue' => $this->$prop,
                'modifiedValue' => $value
            );
        } else {
            $this->changes[$prop]->modifiedValue = $value;
        }
    }

    /**
     * @param mixed $colBlobNotNull
     * @return $this
     */
    public function setColBlobNotNull($colBlobNotNull)
    {
        $this->updateChanges(func_get_arg(0));
        $this->colBlobNotNull = $colBlobNotNull;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getColBlobNotNull()
    {
        return $this->colBlobNotNull;
    }

    /**
     * @param mixed $colBlobNull
     * @return $this
     */
    public function setColBlobNull($colBlobNull)
    {
        $this->updateChanges(func_get_arg(0));
        $this->colBlobNull = $colBlobNull;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getColBlobNull()
    {
        return $this->colBlobNull;
    }

    /**
     * @param mixed $colCharNotNull
     * @return $this
     */
    public function setColCharNotNull($colCharNotNull)
    {
        $this->updateChanges(func_get_arg(0));
        $this->colCharNotNull = $colCharNotNull;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getColCharNotNull()
    {
        return $this->colCharNotNull;
    }

    /**
     * @param mixed $colCharNull
     * @return $this
     */
    public function setColCharNull($colCharNull)
    {
        $this->updateChanges(func_get_arg(0));
        $this->colCharNull = $colCharNull;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getColCharNull()
    {
        return $this->colCharNull;
    }

    /**
     * @param mixed $colDateNotNull
     * @return $this
     */
    public function setColDateNotNull($colDateNotNull)
    {
        $this->updateChanges(func_get_arg(0));
        $this->colDateNotNull = $colDateNotNull;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getColDateNotNull()
    {
        return $this->colDateNotNull;
    }

    /**
     * @param mixed $colDateNull
     * @return $this
     */
    public function setColDateNull($colDateNull)
    {
        $this->updateChanges(func_get_arg(0));
        $this->colDateNull = $colDateNull;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getColDateNull()
    {
        return $this->colDateNull;
    }

    /**
     * @param mixed $colDatetimeNotNull
     * @return $this
     */
    public function setColDatetimeNotNull($colDatetimeNotNull)
    {
        $this->updateChanges(func_get_arg(0));
        $this->colDatetimeNotNull = $colDatetimeNotNull;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getColDatetimeNotNull()
    {
        return $this->colDatetimeNotNull;
    }

    /**
     * @param mixed $colDatetimeNull
     * @return $this
     */
    public function setColDatetimeNull($colDatetimeNull)
    {
        $this->updateChanges(func_get_arg(0));
        $this->colDatetimeNull = $colDatetimeNull;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getColDatetimeNull()
    {
        return $this->colDatetimeNull;
    }

    /**
     * @param mixed $colDecimalNotNull
     * @return $this
     */
    public function setColDecimalNotNull($colDecimalNotNull)
    {
        $this->updateChanges(func_get_arg(0));
        $this->colDecimalNotNull = $colDecimalNotNull;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getColDecimalNotNull()
    {
        return $this->colDecimalNotNull;
    }

    /**
     * @param mixed $colDecimalNull
     * @return $this
     */
    public function setColDecimalNull($colDecimalNull)
    {
        $this->updateChanges(func_get_arg(0));
        $this->colDecimalNull = $colDecimalNull;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getColDecimalNull()
    {
        return $this->colDecimalNull;
    }

    /**
     * @param mixed $colDoubleNotNull
     * @return $this
     */
    public function setColDoubleNotNull($colDoubleNotNull)
    {
        $this->updateChanges(func_get_arg(0));
        $this->colDoubleNotNull = $colDoubleNotNull;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getColDoubleNotNull()
    {
        return $this->colDoubleNotNull;
    }

    /**
     * @param mixed $colDoubleNull
     * @return $this
     */
    public function setColDoubleNull($colDoubleNull)
    {
        $this->updateChanges(func_get_arg(0));
        $this->colDoubleNull = $colDoubleNull;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getColDoubleNull()
    {
        return $this->colDoubleNull;
    }

    /**
     * @param mixed $colEnumNotNull
     * @return $this
     */
    public function setColEnumNotNull($colEnumNotNull)
    {
        $this->updateChanges(func_get_arg(0));
        $this->colEnumNotNull = $colEnumNotNull;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getColEnumNotNull()
    {
        return $this->colEnumNotNull;
    }

    /**
     * @param mixed $colEnumNull
     * @return $this
     */
    public function setColEnumNull($colEnumNull)
    {
        $this->updateChanges(func_get_arg(0));
        $this->colEnumNull = $colEnumNull;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getColEnumNull()
    {
        return $this->colEnumNull;
    }

    /**
     * @param mixed $colFloatNotNull
     * @return $this
     */
    public function setColFloatNotNull($colFloatNotNull)
    {
        $this->updateChanges(func_get_arg(0));
        $this->colFloatNotNull = $colFloatNotNull;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getColFloatNotNull()
    {
        return $this->colFloatNotNull;
    }

    /**
     * @param mixed $colFloatNull
     * @return $this
     */
    public function setColFloatNull($colFloatNull)
    {
        $this->updateChanges(func_get_arg(0));
        $this->colFloatNull = $colFloatNull;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getColFloatNull()
    {
        return $this->colFloatNull;
    }

    /**
     * @param mixed $colTimeNotNull
     * @return $this
     */
    public function setColTimeNotNull($colTimeNotNull)
    {
        $this->updateChanges(func_get_arg(0));
        $this->colTimeNotNull = $colTimeNotNull;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getColTimeNotNull()
    {
        return $this->colTimeNotNull;
    }

    /**
     * @param mixed $colTimeNull
     * @return $this
     */
    public function setColTimeNull($colTimeNull)
    {
        $this->updateChanges(func_get_arg(0));
        $this->colTimeNull = $colTimeNull;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getColTimeNull()
    {
        return $this->colTimeNull;
    }

    /**
     * @param mixed $colTimestampNotNull
     * @return $this
     */
    public function setColTimestampNotNull($colTimestampNotNull)
    {
        $this->updateChanges(func_get_arg(0));
        $this->colTimestampNotNull = $colTimestampNotNull;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getColTimestampNotNull()
    {
        return $this->colTimestampNotNull;
    }

    /**
     * @param mixed $colTimestampNull
     * @return $this
     */
    public function setColTimestampNull($colTimestampNull)
    {
        $this->updateChanges(func_get_arg(0));
        $this->colTimestampNull = $colTimestampNull;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getColTimestampNull()
    {
        return $this->colTimestampNull;
    }

    /**
     * @param mixed $colVarcharNotNull
     * @return $this
     */
    public function setColVarcharNotNull($colVarcharNotNull)
    {
        $this->updateChanges(func_get_arg(0));
        $this->colVarcharNotNull = $colVarcharNotNull;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getColVarcharNotNull()
    {
        return $this->colVarcharNotNull;
    }

    /**
     * @param mixed $colVarcharNull
     * @return $this
     */
    public function setColVarcharNull($colVarcharNull)
    {
        $this->updateChanges(func_get_arg(0));
        $this->colVarcharNull = $colVarcharNull;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getColVarcharNull()
    {
        return $this->colVarcharNull;
    }

    /**
     * @param mixed $id
     * @return $this
     */
    public function setId($id)
    {
        $this->updateChanges(func_get_arg(0));
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

}
