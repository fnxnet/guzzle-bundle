<?php

namespace Fnx\GuzzleBundle\Command\Param;

class MapConfiguration
{
    /**
     * @var \ArrayObject
     */
    private $configuration;

    public function __construct(array $mapConfiguration = [])
    {
        $this->configuration = new \ArrayObject($mapConfiguration);
    }

    public function has(string $key) : bool
    {
    }
}
