<?php

namespace Fnx\GuzzleBundle\Tests\Command;

use Fnx\GuzzleBundle\Command\Command;
use Fnx\GuzzleBundle\Command\CommandManager;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

class CommandManagerTest extends TestCase
{
    /**
     * @expectedException Fnx\GuzzleBundle\Command\CommandNotFoundException
     */
    public function testMissingCommand()
    {
        $container      = $this->createMock(ContainerInterface::class);
        $commandManager = new CommandManager($container);

        $commandManager->get('test');
    }

    public function testAddingAndGettingFromManager()
    {
        $container      = $this->createMock(ContainerInterface::class);
        $command        = $this->createMock(Command::class);
        $commandManager = new CommandManager($container);

        $commandManager->add('test', $command);
        $this->assertSame($command, $commandManager->get('test'));
    }

    public function testAddingAndGettingServiceAsCommand()
    {
        $commandName    = 'test';
        $commandService = 'test_service';
        $command        = $this->createMock(Command::class);
        $container      = $this->createMock(ContainerInterface::class);
        $container->expects($this->once())
                  ->method('has')
                  ->with($this->stringContains($commandService))
                  ->willReturn(true);
        $container->expects($this->once())
                  ->method('get')
                  ->with($this->stringContains($commandService))
                  ->willReturn($command);

        $commandManager = new CommandManager($container);

        $commandManager->add($commandName, $commandService);
        $this->assertSame($command, $commandManager->get($commandName));
    }
}
