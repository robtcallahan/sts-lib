<?php

namespace STS\Util;


/**
 * CLASS NEUCONFIG
 */
class NeuConfig {

    /**
     * CLASS ATTRIBUTES
     */

    /**
     * @var mixed $config variable to hold configuration values
     */
    private $config;

    /**
     * CLASS METHODS
     */

    /**
     * A method to create NeuConfig objects
     * @param null $configFile optional config file to into our object
     * @param null $encrypted whether or not to handle the input file as encrypted
     */
    public function __construct($configFile = null, $encrypted = null) {

        // use our unload to initialize our values and attempt to load the config file
        $this->unloadConfig();
        $this->loadConfig($configFile, $encrypted);

    } // __construct()

    /**
     * A method to convert config values into a string
     * @return string json encoded string of configuration values
     */
    public function __toString() { return json_encode($this->config); } // __toString()

    /**
     * A method to dynamically retrieve config values
     * @param mixed $property property to retrieve
     * @return mixed config value
     */
    public function __get($property) { return (array_key_exists($property, $this->config)) ? $this->config[$property] : null; } // __get()

    /**
     * A method to dynamically set config values
     * @param mixed $property config property to set
     * @param mixed $value value for config property
     */
    public function __set($property, $value) { $this->addConfig($property, $value); } // __set()

    /**
     * A method to add an individual configuration value (called by __set())
     * @param mixed $property configuration property to set to value
     * @param mixed $value value to set for property
     */
    public function addConfig($property, $value) { $this->config[$property] = $value; } // addConfig()

    /**
     * A method to delete individual configuration values
     * @param mixed $property configuration name to delete
     */
    public function delConfig($property) { unset($this->config[$property]); } // delConfig()

    /**
     * A method to alias loadConfig (makes more sense in usage context)
     * @param string $configFile name of file to read
     * @param boolean $encrypted whether or not to treat the file as encrypted
     */
    public function readConfig($configFile = null, $encrypted = null) { $this->loadConfig($configFile, $encrypted); } // readConfig()

    /**
     * A method to write configuration value to file
     * @param string $configFile file name to write
     * @param boolean $encrypted whether or not to encrypt the file
     */
    public function writeConfig($configFile = null, $encrypted = null) {

        // skip this if we aren't given a file name or it isn't a string
        if (is_null($configFile)) { return; }
        if (! is_string($configFile)) { return; }

        // capture our config values as a JSON string via the __toString() method
        $config = $this->__toString();

        // write the file all encrypted-like - or at least obfuscated since we're missing mcrypt :(
        if ($encrypted) { $config = base64_encode($config); }

        // write our config contents to file
        file_put_contents($configFile, $config);

    } // writeConfig()

    /**
     * A method to load a configuration from a file
     * @param string $configFile name of configuration file to load
     * @param boolean $encrypted whether or not to treat the file as encrypted
     */
    public function loadConfig($configFile = null, $encrypted = null) {

        // unload our current configuration
        $this->unloadConfig();

        // skip this if we don't have a file name
        if (is_null($configFile)) { return; }
        if (! file_exists($configFile)) { return; }

        // retrieve our configuration file contents
        $config = file_get_contents($configFile);

        // decode our config as necessary
        if ($encrypted) { $config = base64_decode($config); }

        // save our store our config values in our class
        $this->config = json_decode($config, true);

    } // loadConfig()

    /**
     * A method to unload our current configuration
     */
    public function unloadConfig() { $this->config = Array(); } // unloadConfig()

} // class NeuConfig