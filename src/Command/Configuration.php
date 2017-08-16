<?php

namespace Fnx\GuzzleBundle\Command;

use GuzzleHttp\ClientInterface;

/**
 * Class Configuration
 * @package Fnx\GuzzleBundle\Command
 */
class Configuration
{
    /**
     * @var ClientInterface
     */
    private $client;
    /**
     * @var string
     */
    private $uri;
    /**
     * @var string
     */
    private $method;
    /**
     * @var string
     */
    private $resultClass = '';
    /**
     * @var string
     */
    private $resultType = '';
    /**
     * @var array
     */
    private $params = [];
    /**
     * @var array
     */
    private $defaults = [];

    /**
     * Configuration constructor.
     *
     * @param ClientInterface $client
     * @param string          $uri
     * @param string          $method
     * @param string          $resultClass
     * @param string          $resultType
     * @param array           $params
     * @param array           $defaults
     */
    public function __construct (
        ClientInterface $client,
        string $uri,
        string $method,
        array $params,
        array $defaults,
        string $resultClass = null,
        string $resultType = null
    ) {
        $this->client   = $client;
        $this->uri      = $uri;
        $this->method   = $method;
        $this->params   = $params;
        $this->defaults = $defaults;
        if ($resultClass) {
            $this->setResultClass($resultClass);
        }
        if ($resultType) {
            $this->setResultType($resultType);
        }
    }

    /**
     * @return ClientInterface
     */
    public function getClient () : ClientInterface
    {
        return $this->client;
    }

    /**
     * @return string
     */
    public function getUri ()
    {
        return $this->uri;
    }

    /**
     * @return string
     */
    public function getMethod () : string
    {
        return $this->method;
    }

    /**
     * @return string
     */
    public function getResultClass () : string
    {
        return $this->resultClass ? $this->resultClass : '';
    }

    /**
     * @param $resultClass
     *
     * @return $this
     */
    private function setResultClass (string $resultClass)
    {
        $this->resultClass = $resultClass;
        $this->resultType  = 'object';

        return $this;
    }

    /**
     * @return string
     */
    public function getResultType () : string
    {
        return $this->resultType;
    }

    /**
     * @param string $resultType
     *
     * @return $this
     */
    private function setResultType (string $resultType)
    {
        if (!$this->resultClass) {
            $this->resultType = $resultType;
        }

        return $this;
    }

    /**
     * @return array
     */
    public function getParams () : array
    {
        return $this->params;
    }

    /**
     * @return array
     */
    public function getDefaults () : array
    {
        return $this->defaults;
    }
}
