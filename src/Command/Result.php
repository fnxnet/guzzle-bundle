<?php

namespace Fnx\GuzzleBundle\Command;

use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Class Result
 * @package Fnx\GuzzleBundle\Command
 */
class Result
{
    /**
     * @var string
     */
    private $uri;
    /**
     * @var string
     */
    private $method;
    /**
     * @var array
     */
    private $requestParameters = [];
    /**
     * @var RequestInterface
     */
    private $request;
    /**
     * @var ResponseInterface
     */
    private $response;
    /**
     * @var mixed
     */
    private $data;

    /**
     * Result constructor.
     *
     * @param string            $uri
     * @param string            $method
     * @param array             $requestParameters
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     */
    public function __construct (
        string $uri,
        string $method,
        array $requestParameters,
        RequestInterface $request,
        ResponseInterface $response,
        $data = null
    ) {
        $this->uri               = $uri;
        $this->method            = $method;
        $this->requestParameters = $requestParameters;
        $this->request           = $request;
        $this->response          = $response;
        $this->data              = $data;
    }

    /**
     * @return string
     */
    public function getUri () : string
    {
        return $this->uri;
    }

    /**
     * @return string
     */
    public function getMethod () : string
    {
        return strtoupper($this->method);
    }

    /**
     * @return array
     */
    public function getRequestParameters () : array
    {
        return $this->requestParameters;
    }

    /**
     * @return RequestInterface
     */
    public function getRequest () : RequestInterface
    {
        return $this->request;
    }

    /**
     * @return ResponseInterface
     */
    public function getResponse () : ResponseInterface
    {
        return $this->response;
    }

    /**
     * @return mixed|null
     */
    public function getData ()
    {
        return $this->data;
    }
}
