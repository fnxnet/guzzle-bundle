<?php

namespace Fnx\GuzzleBundle\Command\Param;

use phpDocumentor\Reflection\Types\Self_;

/**
 * Class Map
 * @package Fnx\GuzzleBundle\Command\Param
 */
class Map
{
    /**
     * @var MapConfiguration
     */
    private $configuration;
    /**
     * @var array
     */
    private $params;

    public function __construct(MapConfiguration $mapConfiguration, array $params = [])
    {
        $this->configuration = $mapConfiguration;
        $this->params        = $params;
    }

    public function getConfiguration() : MapConfiguration
    {
        return $this->configuration;
    }

    public function getParams() : array
    {
        return $this->params;
    }

//    /**
//     * @param array $params
//     */
//    public function validateRequiredParams() : bool
//    {
//        $diff = array_diff_key($this->getRequiredMaps(), $params);
//
//        if ($diff) {
//            throw new \InvalidArgumentException("Missing required params: " . implode(',', array_keys($diff)));
//        }
//
//        return true;
//    }
//
//    /**
//     * @return array
//     */
//    public function getRequiredMaps() : array
//    {
//        return array_filter(
//            $this->configuration->getArrayCopy(),
//            function($v) {
//                return isset($v['required']) && $v['required'];
//            }
//        );
//    }
//
//    /**
//     * @param array $data
//     *
//     * @return array
//     */
//    public function extractParams(array $data) : array
//    {
//        $params = array_filter(
//            $data,
//            function($key) {
//                return $this->configuration->offsetExists($key) && $this->configuration->offsetGet(
//                        $key
//                    )['location'] !== 'uri';
//            },
//            ARRAY_FILTER_USE_KEY
//        );
//
//        return $params;
//    }
//
//    /**
//     * @param array $data
//     *
//     * @return array
//     */
//    public function extractUriParams(array $data) : array
//    {
//        $params = array_filter(
//            $data,
//            function($key) {
//                return $this->configuration->offsetExists($key) && $this->configuration->offsetGet(
//                        $key
//                    )['location'] === 'uri';
//            },
//            ARRAY_FILTER_USE_KEY
//        );
//
//        return $params;
//    }
//
//    /**
//     * @param array $data
//     *
//     * @return array
//     */
//    public function extractCustomVariables(array $data) : array
//    {
//        $custom = array_filter(
//            $data,
//            function($key) {
//                return !$this->configuration->offsetExists($key);
//            },
//            ARRAY_FILTER_USE_KEY
//        );
//
//        return $custom;
//    }

}
