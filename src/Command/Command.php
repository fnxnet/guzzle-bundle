<?php

namespace Fnx\GuzzleBundle\Command;

use Fnx\GuzzleBundle\Command\Param\Map;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Request;
use JMS\Serializer\Serializer;
use Psr\Http\Message\ResponseInterface;

/**
 * Class CommandConfiguration
 * @package Fnx\GuzzleBundle\Command
 */
class Command
{
    /**
     * @var Configuration
     */
    private $config;
    /**
     * @var Serializer
     */
    private $jms;

    /**
     * CommandConfiguration constructor.
     *
     * @param ClientInterface $client
     * @param string          $uri
     * @param string          $method
     * @param array           $params
     */
    public function __construct (
        Serializer $jms,
        Configuration $config
    ) {
        $this->jms    = $jms;
        $this->config = $config;
    }

    /**
     * @return ClientInterface
     */
    public function getClient () : ClientInterface
    {
        return $this->config->getClient();
    }

    /**
     * @return Configuration
     */
    public function getConfiguration () : Configuration
    {
        return $this->config;
    }

    /**
     * @param array $data
     *
     * @return Result
     */
    public function execute (array $data = []) : Result
    {
        $config = $this->config;
        $method = $config->getMethod();

        $map = new Map($config->getParams());

        $params    = $map->extractParams($data);
        $uriParams = $map->extractUriParams($data);
        $custom    = $map->extractCustomVariables($data);

        $map->validateRequiredParams($params);

        $configuration = $config->getDefaults() + $custom + $this->mapParams($map, $params);
        $uri           = $this->buildUri($uriParams);

        $request  = new Request($method, $uri);
        $response = $this->getClient()->send($request, $configuration);
        $result   = $this->buildResponse($response);

        return new Result($uri, $method, $configuration, $request, $response, $result);
    }

    /**
     * @param ResponseInterface $response
     *
     * @return mixed
     */
    private function buildResponse (ResponseInterface $response)
    {
        $config = $this->config;

        switch ($config->getResultType()) {
            case 'string':
                return (string)$response->getBody();
            case 'array':
                return json_decode($response->getBody(), true);
            case 'object':
                $resultClass = $config->getResultClass();
                if (!$resultClass) {
                    return json_decode($response->getBody());
                } else {
                    return $this->jms->deserialize($response->getBody(), $resultClass, 'json');
                }
        }

        return '';
    }

    /**
     * @param Map   $map
     * @param array $params
     *
     * @return array
     */
    private function mapParams (Map $map, array $params = []) : array
    {
        $result = [];

        foreach ($params as $name => $param) {
            if (!($paramMap = $map[ $name ])) {
                continue;
            }
            $location = $this->mapLocation($paramMap['location']);
            if ($location === 'uri') {
                continue;
            }
            $static  = isset($paramMap['static']) && $paramMap['static'];
            $varName = isset($paramMap['map']) ? $paramMap['map'] : $name;
            $value   = isset($paramMap['value']) && $static ? $paramMap['value'] : $param;

            $result[ $location ][ $varName ] = $value;
        }

        return $result;
    }

    /**
     * @param array $params
     *
     * @return string
     */
    private function buildUri (array $params = []) : string
    {
        return preg_replace_callback(
            '/{(.*)}/',
            function($matches) use ($params) {
                $key = $matches[1];

                return isset($params[ $key ]) ? $params[ $key ] : '';
            },
            $this->config->getUri()
        );
    }

    /**
     * @param string $location
     *
     * @return string
     */
    private function mapLocation (string $location) : string
    {
        switch ($location) {
            case 'data':
                return 'form_params';
        }

        return $location;
    }
}
