<?php

namespace Fnx\GuzzleBundle\Tests\Command;

use Fnx\GuzzleBundle\Command\GuzzleGommandConfiguration;
use Fnx\GuzzleBundle\Command\Param\Mapper;
use PHPUnit\Framework\TestCase;

class GuzzleGommandConfigurationTest extends TestCase
{
    public function testGetUri()
    {
        $expectedUri = 'dummy.com/v1/?test1=1&test2=2';

        $config = new GuzzleGommandConfiguration(
            new Mapper(), [
                  'uri'    => 'dummy.com/{{version}}/',
                  'method' => 'POST',
                  'params' => [
                      'test1'   => 1,
                      'test2'   => 2,
                      'version' => 'v1',
                  ],
              ]
        );

        $this->assertSame($expectedUri, $config->getUri());
    }

    public function testGetMethod()
    {
        $expectedMethod = 'POST';

        $config = new GuzzleGommandConfiguration(
            new Mapper(), [
                  'uri'    => 'dummy.com/{{version}}/',
                  'method' => 'POST',
              ]
        );

        $this->assertSame($expectedMethod, $config->getMethod());
    }
}
