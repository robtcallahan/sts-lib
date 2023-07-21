<?php

namespace STS\Util;

use STS\Util\NeuConfig;

/**
 * Created by JetBrains PhpStorm.
 * User: bgant
 * Date: 7/27/12
 * Time: 1:41 PM
 * To change this template use File | Settings | File Templates.
 */
/**
 *
 */
class NeuMetric {

    /**
     * CLASS ATTRIBUTES
     */

    private $config;

    /**
     * CLASS METHODS
     */

    /**
     * A method to create NeuMetric objects
     */
    public function __construct() {

        // auto-config our NeuMetric object
        $this->config = new NeuConfig(__DIR__."/../conf/neumetric.econf", true);

    } // __construct()

    /**
     * @return string string representation of the NeuMetric class
     */
    public function __toString() { return $this->config->__toString(); } // __toString()


    /**
     * CLASS INTERFACE
     */

    /**
     * @param string $tool name of the tool to log for
     * @param string $message message to log
     */
    public function log($tool = "", $message = "") {

        // build our query
        $insert  = "INSERT INTO neumetric." . $tool . " ";
        $columns = "(date, host, user, tool, args) ";
        $values  = "VALUES (NOW(), '" . gethostname() . "', '" . $_SERVER['PHP_AUTH_USER'] . "', '". $tool ."', '" .$message ."')";

        // assemble our query
        $query = $insert . $columns . $values;

        // this could get messy
        try {

            // connect and execute our query
            $db = new \Mysqli($this->config->host, $this->config->user, $this->config->pass, $this->config->table, $this->config->port);
            $db->query($query);
            $db->close();

        } // try

        // die quietly
        catch (\Exception $e) { }

    } // log()

} // class NeuMetric
