<?php

// define our namespace
namespace STS\OpsCenter;

// include our STS\Config
use STS\NeuObject;

/**
 * OpsCenter
 */
class OpsCenter extends NeuObject
{

    /**
     * @var string $_host
     * @var string $_username
     * @var string $_password
     */
    private $_host;
    private $_username;
    private $_password;

    /**
     * @var string $_requestData
     * @var string $_lastRequest
     */
    private $_signature;
    private $_lastRequest;

    /**
     * @var string $_accountIdType
     * @var string $_distributionGroupIdType
     * @var string $_genericString
     * @var string $_hostnameType
     * @var string $_resourceIdType
     * @var string $_snapshotIdType
     * @var string $_serverTemplateIdType
     * @var string $_vnetIdType
     * @var string $_volumeIdType
     * @var string $_vserverIdType
    */
    protected $_accountIdType;
    protected $_distributionGroupIdType;
    protected $_genericString;
    protected $_hostnameType;
    protected $_resourceIdType;
    protected $_snapshotIdType;
    protected $_serverTemplateIdType;
    protected $_vnetIdType;
    protected $_volumeIdType;
    protected $_vserverIdType;

    /**
     * A method to create OpsCenter objects
     * @param null $host
     * @param null $username
     * @param null $password
     */
    public function __construct($host = null, $username = null, $password = null)
	{

        // construct our parent
        parent::__construct();

        // TODO: figure out how to set up the stsapps NeuConfig object
        // use provided un/pw or default to stsapps
        $this->_username = ($username) ? $username : $this->_stsapps->username;
        $this->_password = ($password) ? $password : $this->_stsapps->password;

        // use provided host or default to opsCenter config
        $this->_host = ($host) ? $host : $this->_opsCenter->host;

        // set up our regexps
        $this->_accountIdType           = "/^ACC-[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/";
        $this->_distributionGroupIdType = "/^DG-[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/";
        $this->_genericString           = "/^.+$/";
        $this->_hostnameType            = "/^[A-Za-z\\d]|([A-Za-z\\d][A-Za-z\\d\\-]{0,253}[A-Za-z\\d])$/";
        $this->_resourceIdType          = "/^[A-Z]{1,4}-[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/";
        $this->_snapshotIdType          = "/^SNAP-[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/";
        $this->_serverTemplateIdType    = "/^TMPL-[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/";
        $this->_vnetIdType              = "/^VNET-[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/";
        $this->_volumeIdType            = "/^VOL-[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/";
        $this->_vserverIdType           = "/^VSRV-[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/";

    } // __construct()

    /**
     * @param String $url
     *
     * @return string
     */
    protected function _request($url = "")
    {

        // prep our result;
        $result = "";

        // store this request as our new last request
        $this->_lastRequest = $url;

        // TODO: stuff

        // return our result
        return $result;

    } // _request();

    /**
     * @return String
     */
    public function lastRequest()
    {

        // return our last request
        return $this->_lastRequest;

    } // lastRequest()

    /**
     * AKM - Access Key Management request interface
     *
     * @param string $action
     * @param array  $options
     *
     * @return string
     */
    protected function _AKMRequest($action = "", $options = Array())
    {

        // stage our result as an empty string
        $result = "";

        // action is required
        if ($action) {

            /**
             * Action, version, timestamp, expires and accessKeyId are all required.
             * All but action can be auto-generated.
             */

            $time = time();
            $required = Array(
                'Action'      => $action,
                'Version'     => 1,
                'Timestamp'   => $time,
                'Expires'     => $time,
                'AccessKeyId' => null // TODO: figure this out
            );

            // merge our required with our options
            $request = http_build_query(array_merge($required, $options));

            // generate our URL
            $url = "https://{$this->_username}:{$this->_password}@{$this->_host}/akm/?{$request}";

            // perform our request
            $result = $this->_request($url);

        } // if action

        // return our result
        return $result;

    } // _AKMRequest()


    /**
     * IAAS - Infrastructure As A Service request interface
     *
     * @param string $action
     * @param array  $options
     * @return string empty or XML
     */
    protected function _IAASRequest($action = "", $options = Array())
    {

        // stage our result as an empty string
        $result = "";

        // action is required
        if ($action) {

            /**
             * Action, version, timestamp, expires and accessKeyId are all required.
             * All but action can be auto-generated.
             */

            $time = time();
            $required = Array(
                'Action'      => $action,
                'Version'     => 1,
                'Timestamp'   => $time,
                'Expires'     => $time,
                'AccessKeyId' => null // TODO: figure this out
            );

            // merge our required with our options
            $request = http_build_query(array_merge($required, $options));

            // generate our URL
            $url = "https://{$this->_host}/iaas/?{$request}&{$this->_signature}";

            // perform our request
            $result = $this->_request($url);

        } // if action

        // return our result
        return $result;

    } // _IAASRequest()

} // class OpsCenter
