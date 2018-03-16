<?php

namespace Fnx\GuzzleBundle\Tests\Command\Param;

use Fnx\GuzzleBundle\Command\Param\MappedParams;
use Fnx\GuzzleBundle\Command\Param\Mapper;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Routing\Loader\YamlFileLoader;

class MapperTest extends TestCase
{
    public function testDefault()
    {
        $mapper = new Mapper();
        $result = $mapper->map();

        $this->assertInstanceOf(MappedParams::class, $result);
        $this->assertEmpty($result->body());
        $this->assertEmpty($result->uri);
        $this->assertEmpty($result->post);
        $this->assertEmpty($result->json);
        $this->assertEmpty($result->header);
    }

    public function testMapping()
    {
        $configuration = include(__DIR__ . '/files/mapper_configuration.php');
        $mapper        = new Mapper($configuration);
        $result        = $mapper->map(
            [
                'test_json'    => 'json',
                'test_post'    => 'post',
                'test_uri'     => 'uri',
                'test_header'  => 'header',
                'test_query'   => 'query',
                'dummy_query'  => 'query',
                'test_static'  => 'dummy_static',
                'test_static2' => 'dummy_static',
            ]
        );

        $this->assertInstanceOf(MappedParams::class, $result);
        $this->assertEmpty($result->body());
        $this->assertSame(4, $result->uri->count());
        $this->assertSame(1, $result->query->count());
        $this->assertSame(1, $result->post->count());
        $this->assertSame(1, $result->header->count());
        $this->assertSame(1, $result->json->count());

        // test naming
        $this->assertTrue($result->post->offsetExists('testPost'));
        $this->assertTrue($result->json->offsetExists('test_json'));
        $this->assertTrue($result->query->offsetExists('test_query'));
        $this->assertTrue($result->header->offsetExists('test_header'));
        $this->assertTrue($result->uri->offsetExists('test_uri'));

        // test static with value - make sure it's has not been overriden
        $this->assertSame('static', $result->uri->offsetGet('test_static'));

        // test static without value - check default value
        $this->assertSame(null, $result->uri->offsetGet('test_static2'));

        // test static not overriden
        $this->assertSame('static3', $result->uri->offsetGet('test_static3'));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testRequiredParams()
    {
        $configuration = include(__DIR__ . '/files/mapper_configuration.php');
        $mapper        = new Mapper($configuration);
        $result        = $mapper->map();
    }
}
