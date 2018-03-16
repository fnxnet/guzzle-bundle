<?php

namespace Fnx\GuzzleBundle\Command;

use Fnx\GuzzleBundle\Command\Param\Mapper;

class GuzzleGommandConfiguration
{
    /**
     * @var Mapper
     */
    private $mapper;
    private $config;

    /**
     * GuzzleGommandConfiguration constructor.
     *
     * @param $mapper
     * @param $config
     */
    public function __construct(Mapper $mapper, array $config = null)
    {
        $this->mapper = $mapper;
        $this->config = $config;
    }

    public function getMapper()
    {
        return $this->mapper;
    }

    public function getConfig() : array
    {
        return $this->config ? : [];
    }

    public function getUri() : string
    {
        $uri    = $this->config['uri'] ?? '';
        $params = $this->config['params'] ?? [];

        $uri       = $this->mapper->mapString($uri, $params);
        $uriParams = $this->mapper->getUrlParams();

        return $uri;
    }

    public function getMethod() : string
    {
        return $this->config['method'] ?? '';
    }
}
