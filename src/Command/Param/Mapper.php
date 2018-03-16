<?php

namespace Fnx\GuzzleBundle\Command\Param;

class Mapper
{
    /**
     * @var \ArrayObject
     */
    private $config;

    /**
     * Mapper constructor.
     *
     * @param $config
     */
    public function __construct(array $config = [])
    {
        $this->config = new \ArrayObject($config);
    }

    public function getUrlParams()
    {
        $params = $this->config['params'] ?? [];
        return $this->fetchByCallback(
            $params,
            function(array $item) {
                var_dump('sd');
                die;
            }
        );
    }

    private function fetchByCallback(array $data = [], callable $callback)
    {
        $result = [];

        foreach ($data as $item) {
            if (call_user_func($callback, $item)) {
                $result[] = $item;
            }
        }

        return $result;
    }

    public function mapString(string $tpl, array $params = [])
    {
        return preg_replace_callback(
            '/{{(.*)}}/',
            function($a) use ($params) {
                return $params[ $a[1] ] ?? '';
            },
            $tpl
        );
    }

    private function mapParam(string $name, $paramValue = null)
    {

        $config = $this->config->offsetExists($name) ? $this->config->offsetGet($name) : null;

        $location   = $config['location'] ?? null;
        $mappedName = $config['map'] ?? $name;
        $value      = $paramValue;
        $isStatic   = (bool)($config['static'] ?? false);
        if ($isStatic) {
            $value = $config['value'] ?? null;
        }

        return [
            (string)$location,
            (string)$mappedName,
            $value,
        ];
    }

    private function mapParams(array $params = [])
    {
        $mappedParams = new MappedParams();

        foreach ($params as $name => $paramValue) {
            call_user_func_array(
                [
                    $mappedParams,
                    'add',
                ],
                $this->mapParam((string)$name, $paramValue)
            );
        }

        return $mappedParams;
    }

    private function addStaticParams(array $params = [])
    {
        $params += array_filter(
            $this->config->getArrayCopy(),
            function($config) use ($params) {
                return (bool)($config['static'] ?? false);
            }
        );

        return $params;
    }

    private function checkRequired(array $params = [])
    {
        $required = array_keys(
            array_filter(
                $this->config->getArrayCopy(),
                function($config) use ($params) {
                    return (bool)($config['required'] ?? false);
                }
            )
        );

        if ($diff = array_diff_key($required, array_keys($params))) {
            $message = sprintf('Missing required parameters: %s', implode(',', $diff));
            throw new \InvalidArgumentException($message);
        }
    }

    public function map(array $params = []) : MappedParams
    {
        $this->checkRequired($params);
        $params = $this->addStaticParams($params);
        return $this->mapParams($params);
    }
}
