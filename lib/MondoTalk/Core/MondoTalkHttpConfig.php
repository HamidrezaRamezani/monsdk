<?php

namespace MondoTalk\Core;

use MondoTalk\Exception\MondoTalkConfigurationException;

/**
 * Class MondoTalkHttpConfig
 * Http Configuration Class
 *
 * @package MondoTalk\Core
 */
class MondoTalkHttpConfig
{
    /**
     * Some default options for curl
     * These are typically overridden by MondoTalkConnectionManager
     *
     * @var array
     */
    public static $defaultCurlOptions = array(
        CURLOPT_SSLVERSION => 6,
        CURLOPT_CONNECTTIMEOUT => 10,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 60,    // maximum number of seconds to allow cURL functions to execute
        CURLOPT_USERAGENT => 'MondoTalk-PHP-SDK',
        CURLOPT_HTTPHEADER => array(),
        CURLOPT_SSL_VERIFYHOST => 2,
        CURLOPT_SSL_VERIFYPEER => 1,
        CURLOPT_SSL_CIPHER_LIST => 'TLSv1:TLSv1.2'
        //Allowing TLSv1 cipher list.
        //Adding it like this for backward compatibility with older versions of curl
    );

    const HEADER_SEPARATOR = ';';
    const HTTP_GET = 'GET';
    const HTTP_POST = 'POST';

    private $headers = array();

    private $curlOptions;

    private $url;

    private $method;

    /***
     * Number of times to retry a failed HTTP call
     */
    private $retryCount = 0;

    /**
     * Default Constructor
     *
     * @param string $url
     * @param string $method HTTP method (GET, POST etc) defaults to POST
     * @param array $configs All Configurations
     */
    public function __construct($url = null, $method = self::HTTP_POST, $configs = array())
    {
        $this->url = $url;
        $this->method = $method;
        $this->curlOptions = $this->getHttpConstantsFromConfigs($configs, 'http.') + self::$defaultCurlOptions;
        // Update the Cipher List based on OpenSSL or NSS settings
        $curl = curl_version();
        $sslVersion = isset($curl['ssl_version']) ? $curl['ssl_version'] : '';
        if($sslVersion && substr_compare($sslVersion, "NSS/", 0, strlen("NSS/")) === 0) {
            //Remove the Cipher List for NSS
            $this->removeCurlOption(CURLOPT_SSL_CIPHER_LIST);
        }
    }

    /**
     * Gets Url
     *
     * @return null|string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Gets Method
     *
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * Gets all Headers
     *
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * Get Header by Name
     *
     * @param $name
     * @return string|null
     */
    public function getHeader($name)
    {
        if (array_key_exists($name, $this->headers)) {
            return $this->headers[$name];
        }
        return null;
    }

    /**
     * Sets Url
     *
     * @param $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * Set Headers
     *
     * @param array $headers
     */
    public function setHeaders(array $headers = array())
    {
        $this->headers = $headers;
    }

    /**
     * Adds a Header
     *
     * @param      $name
     * @param      $value
     * @param bool $overWrite allows you to override header value
     */
    public function addHeader($name, $value, $overWrite = true)
    {
        if (!array_key_exists($name, $this->headers) || $overWrite) {
            $this->headers[$name] = $value;
        } else {
            $this->headers[$name] = $this->headers[$name] . self::HEADER_SEPARATOR . $value;
        }
    }

    /**
     * Removes a Header
     *
     * @param $name
     */
    public function removeHeader($name)
    {
        unset($this->headers[$name]);
    }

    /**
     * Gets all curl options
     *
     * @return array
     */
    public function getCurlOptions()
    {
        return $this->curlOptions;
    }

    /**
     * Add Curl Option
     *
     * @param string $name
     * @param mixed  $value
     */
    public function addCurlOption($name, $value)
    {
        $this->curlOptions[$name] = $value;
    }

    /**
     * Removes a curl option from the list
     *
     * @param $name
     */
    public function removeCurlOption($name)
    {
        unset($this->curlOptions[$name]);
    }

    /**
     * Set Curl Options. Overrides all curl options
     *
     * @param $options
     */
    public function setCurlOptions($options)
    {
        $this->curlOptions = $options;
    }

}