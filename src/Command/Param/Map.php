<?php

namespace Fnx\GuzzleBundle\Command\Param;

/**
 * Class Map
 * @package Fnx\GuzzleBundle\Command\Param
 */
class Map
    extends \ArrayObject
{
    /**
     * @param array $params
     */
    public function validateRequiredParams (array $params)
    {
        $diff = array_diff_key($this->getRequiredMaps(), $params);

        if ($diff) {
            throw new \InvalidArgumentException("Missing required params: " . implode(',', array_keys($diff)));
        }
    }

    /**
     * @return array
     */
    public function getRequiredMaps ()
    {
        return array_filter(
            $this->getArrayCopy(),
            function($v) {
                return isset($v['required']) && $v['required'];
            }
        );
    }

    /**
     * @param array $data
     *
     * @return array
     */
    public function extractParams (array $data) : array
    {
        $params = array_filter(
            $data,
            function($key) {
                return $this->offsetExists($key) && $this->offsetGet($key)['location'] !== 'uri';
            },
            ARRAY_FILTER_USE_KEY
        );

        return $params;
    }

    /**
     * @param array $data
     *
     * @return array
     */
    public function extractUriParams (array $data) : array
    {
        $params = array_filter(
            $data,
            function($key) {
                return $this->offsetExists($key) && $this->offsetGet($key)['location'] === 'uri';
            },
            ARRAY_FILTER_USE_KEY
        );

        return $params;
    }

    /**
     * @param array $data
     *
     * @return array
     */
    public function extractCustomVariables (array $data) : array
    {
        $custom = array_filter(
            $data,
            function($key) {
                return !$this->offsetExists($key);
            },
            ARRAY_FILTER_USE_KEY
        );

        return $custom;
    }

}
