<?php

namespace Fnx\GuzzleBundle\Command;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Request;

class GuzzleCommand
{
    /**
     * @var ClientInterface
     */
    private $client;
    /**
     * @var GuzzleGommandConfiguration
     */
    private $config;

    /**
     * GuzzleCommand constructor.
     *
     * @param Client $client
     */
    public function __construct(ClientInterface $client, GuzzleGommandConfiguration $config)
    {
        $this->client = $client;
        $this->config = $config;
    }

    public function execute(array $options = []) : CommandResult
    {
        $result = new CommandResult();
        try {
            $uri      = $this->config->getUri();
            $method   = $this->config->getMethod();
            $response = $this->client->requestAsync($method, $uri, $options)->wait();
            $result->setResponse($response);
        } catch (\Exception $ex) {
            $result->setError($ex);
        }
        return $result;
    }

    public function getClient() : ClientInterface
    {
        return $this->client;
    }

    private function getOptions() { }
}
