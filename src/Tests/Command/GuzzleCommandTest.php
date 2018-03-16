<?php

namespace Fnx\GuzzleBundle\Tests\Command;

use Fnx\GuzzleBundle\Command\CommandResult;
use Fnx\GuzzleBundle\Command\GuzzleCommand;
use Fnx\GuzzleBundle\Command\GuzzleGommandConfiguration;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Promise\PromiseInterface;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;

class GuzzleCommandTest extends TestCase
{
    public function testInvalidClient()
    {
        $uri    = 'dummyUri';
        $method = 'dummyMethod';

        $ex     = new \Exception();
        $client = $this->createMock(ClientInterface::class);
        $client->expects($this->once())
               ->method('requestAsync')
               ->with($method, $uri)
               ->willThrowException($ex);

        $configuration = $this->createMock(GuzzleGommandConfiguration::class);
        $configuration->expects($this->once())
                      ->method('getUri');

        $cmd    = new GuzzleCommand($client, $configuration);
        $result = $cmd->execute([]);

        $this->assertSame($client, $cmd->getClient());
        $this->assertInstanceOf(CommandResult::class, $result);
        $this->assertEmpty($result->getResponse());
        $this->assertSame($ex, $result->getError());
    }

    public function testValidClient()
    {
        $client = $this->createMock(ClientInterface::class);
        $client->expects($this->once())
               ->method('requestAsync')
               ->willReturn($this->getPromise());

        $configuration = $this->createMock(GuzzleGommandConfiguration::class);
        $configuration->expects($this->once())
                      ->method('getUri');

        $configuration->expects($this->once())
                      ->method('getMethod');

        $cmd    = new GuzzleCommand($client, $configuration);
        $result = $cmd->execute();

        $this->assertSame($client, $cmd->getClient());
        $this->assertInstanceOf(CommandResult::class, $result);
        $this->assertInstanceOf(ResponseInterface::class, $result->getResponse());
        $this->assertEmpty($result->getError());
    }

    /**
     * @return PromiseInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private function getPromise()
    {
        $promise = $this->createMock(PromiseInterface::class);
        $promise->expects($this->once())
                ->method('wait')
                ->willReturn($this->createMock(ResponseInterface::class));
        return $promise;
    }
}
