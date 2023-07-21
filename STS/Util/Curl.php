<?php
/*******************************************************************************
 *
 * $Id$
 * $Date$
 * $Author$
 * $Revision$
 * $HeadURL$
 *
 *******************************************************************************
 */

namespace STS\Util;

use STS\Util\SysLog;

/**
 * Class abstracting the cURL library for easier use.
 *
 * Usage:
 *     $curl = new Curl();
 *     $curl->setUrl('http://www.google.com/#')
 *         ->setData('&q=testing+curl')
 *         ->setType('GET');
 *     $curl->send();
 *     echo $curl->getStatusCode(), PHP_EOL;
 *     echo $curl->getResponse(), PHP_EOL;
 */
class Curl
{
    /**
     * directory where cookie files will be saved.
     * @const
     */
    const COOKIE_DIR = "/tmp";

    /**
     * Body returned by the last request.
     * @var string
     */
    protected $body;

    /**
     * Actual CURL connection handle.
     * @var resource
     */
    protected $ch;

    /**
     * Data to send to server.
     * @var mixed
     */
    protected $data;

    /**
     * Response code from the last request.
     * @var integer
     */
    protected $status;

    /**
     * Request type.
     * @var string
     */
    protected $type;

    /**
     * Url for the connection.
     * @var string
     */
    protected $url;

    /**
     * TRUE to enable curl verbose mode.
     * @var string
     */
    protected $verbose;

    /**
     * cookieJar points to the file that saves cookies after disconnect.
     * @var string
     */
    protected $cookieJar;

    /**
     * pid is the id of the php process.
     * @var string
     */
    protected $pid;

    /**
     * version number of the SSL version to use. Defaults to 2.
     * @var int
     */
    protected $sslVersion = 2;

    /**
     * @var bool
     */
    protected $newCookieSession = true;

    /**
     * Should we use cookies or no
     * @var bool
     */
    protected $useCookies = true;

    /**
     * Array of values to add to the header
     * @var array
     */
    protected $header = array();

    /**
     * True to track the handle's request string.
     * @var bool
     */
    protected $headerOut = false;

    /**
     * Username to use for authentication.
     * @var string
     */
    protected  $username;
    /**
     * Password to use for authentication
     * @var string
     */
    protected  $password;

    /**
     * Keeps track of the instance for singleton use
     * @var null|Curl
     */
    private static $_instance = null;

    /**
     * Constructor.
     */
    public function __construct($useCookies = true, $cookieJar = null)
    {
        $this->useCookies = $useCookies;

        $this->ch     = curl_init();
        $this->url    = null;
        $this->type   = 'GET';
        $this->body   = null;
        $this->data   = null;
        $this->status = null;
        $this->verbose = false;
        $this->header  = array();

        // define the cookie file since we are connecting for the first time with this user
        $this->pid = getmypid();
        $this->cookieJar = $cookieJar ? $cookieJar : self::COOKIE_DIR . "/curl-cookie." . $this->pid;

        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->ch, CURLOPT_USERAGENT, 'Curl Client');
        curl_setopt($this->ch, CURLOPT_VERBOSE, $this->verbose);
        curl_setopt($this->ch, CURLOPT_HEADER, false);
        curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($this->ch, CURLOPT_SSL_VERIFYHOST, false);

        if ($this->useCookies) {
            curl_setopt($this->ch, CURLOPT_COOKIESESSION, $this->newCookieSession);
            curl_setopt($this->ch, CURLOPT_COOKIEJAR, $this->cookieJar);
            curl_setopt($this->ch, CURLOPT_COOKIEFILE, $this->cookieJar);
        }
    }

    /**
     * @return void
     */
    public function __destruct() {
        // close the connection so that we can remove the cookie file
        if ($this->ch && gettype($this->ch) == "resource") {
            curl_close($this->ch);
        }

        // remove the cookie file
        if (file_exists($this->cookieJar)) {
            //unlink($this->cookieJar);
        }
    }

    /**
     * A singleton is used here by the CMDBDAO class since each CMDB*Table classes extend CDMBDAO. This will
     * prevent multiple instances of Curl from being created and therefore, curl will use the existing
     * connection and cookies
     *
     * @return null|Curl
     */
    public static function singleton()
    {
        if (self::$_instance == null) {
            self::$_instance = new Curl();
        }
        return self::$_instance;
    }

    /**
     * Return the connection's URL.
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set the URL to make an HTTP connection to.
     * @param string $url URL to connect to.
     * @return Curl
     */
    public function setUrl($url)
    {
        $this->url = $url;
        curl_setopt($this->ch, CURLOPT_URL, $url);
        return $this;
    }


    /**
     * @param $username
     * @param $password
     * @return $this
     */
    public function setUsernamePassword($username, $password)
    {
        #error_log("Curl::setUsernamePassword(): username " . $username);
        $this->username = $username;
        $this->password = $password;

        $this->__destruct();
        $this->__construct();
        curl_setopt($this->ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($this->ch, CURLOPT_USERPWD, "{$username}:{$password}");
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Return the body returned by the last request.
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Return the current payload.
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Set the payload for the request.
     *
     * This can either by a string, formatted like a query
     * string:
     *      foo=bar&mitz=fah
     * or a single-dimensional array:
     *      array('foo' => 'bar', 'mitz' => 'fah')
     * @param mixed $data
     * @return Curl
     */
    public function setData($data)
    {
        if (is_array($data)) {
            $data = http_build_query($data);
        }
        $this->data = $data;
        return $this;
    }

    /**
     * Return the current type of request.
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set the type of request to make (GET, POST, PUT,
     * DELETE, etc)
     * @param string $type Request type to send.
     * @return Curl
     */
    public function setType($type)
    {
        $this->type = $type;
        curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST, $type);
        return $this;
    }

    /**
     * Send the request.
     * @return Curl|null
     */
    public function send()
    {
        if (!$this->url) {
            return null;
        }

        if ('GET' == $this->type && $this->data) {
            $this->url .= '?' . $this->data;
            $header = array("Accept: application/json");
            if (is_array($this->header) && count($this->header) > 0) {
                $header = array_merge($header, $this->header);
            }
            curl_setopt($this->ch, CURLOPT_HTTPHEADER, $header);
        } else {
            $header = array("Content-Type: application/json", 'Content-Length: ' . strlen($this->data));
            if (is_array($this->header) && count($this->header) > 0) {
                $header = array_merge($header, $this->header);
            }

            if ('POST' == $this->type) {
                curl_setopt($this->ch, CURLOPT_POST, true);
                curl_setopt($this->ch, CURLOPT_POSTFIELDS, $this->data);
                curl_setopt($this->ch, CURLOPT_HTTPHEADER, $header);
            } else {
                $header = array("Accept: application/json");
                if (is_array($this->header) && count($this->header) > 0) {
                    $header = array_merge($header, $this->header);
                }
                curl_setopt($this->ch, CURLOPT_HTTPHEADER, $header);
            }
        }


        $this->body   = curl_exec($this->ch);
        $this->status = curl_getinfo($this->ch, CURLINFO_HTTP_CODE);

        if ($this->useCookies) {
            // this is no longer a new cookie session
            $this->newCookieSession = false;
            curl_setopt($this->ch, CURLOPT_COOKIESESSION, $this->newCookieSession);
        }

        return $this;
    }

    /**
     * @param string $verbose
     * @return $this
     */
    public function setVerbose($verbose)
    {
        $this->verbose = $verbose;
        curl_setopt($this->ch, CURLOPT_VERBOSE, $this->verbose);
        return $this;
    }

    /**
     * @return string
     */
    public function getVerbose()
    {
        return $this->verbose;
    }

    /**
     * @return resource
     */
    public function getCh()
    {
        return $this->ch;
    }

    /**
     * @param string $cookieJar
     * @return $this
     */
    public function setCookieJar($cookieJar)
    {
        $this->cookieJar = $cookieJar;
        curl_setopt($this->ch, CURLOPT_COOKIEJAR, $this->cookieJar);
        curl_setopt($this->ch, CURLOPT_COOKIEFILE, $this->cookieJar);
        return $this;
    }

    /**
     * @return string
     */
    public function getCookieJar()
    {
        return $this->cookieJar;
    }

    /**
     * @return string
     */
    public function getPid()
    {
        return $this->pid;
    }

    /**
     * Return the status code for the last request.
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param boolean $useCookies
     * @return $this
     */
    public function setUseCookies($useCookies)
    {
        $this->useCookies = $useCookies;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getUseCookies()
    {
        return $this->useCookies;
    }

    /**
     * @param int $sslVersion
     * @return $this
     */
    public function setSslVersion($sslVersion)
    {
        $this->sslVersion = $sslVersion;
        curl_setopt($this->ch, CURLOPT_SSLVERSION, $this->sslVersion);
        return $this;
    }

    /**
     * @return \STS\Util\version
     */
    public function getSslVersion()
    {
        return $this->sslVersion;
    }

    /**
     * @param array $header
     * @return $this
     */
    public function setHeader($header) {
        $this->header = $header;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getHeader() {
        return $this->header;
    }

    /**
     * @param boolean $headerOut
     * @return $this
     */
    public function setHeaderOut($headerOut) {
        $this->headerOut = $headerOut;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getHeaderOut() {
        return $this->headerOut;
    }

}
