<?php

namespace Fnx\GuzzleBundle\Command;

use Fnx\GuzzleBundle\DependencyInjection\FnxGuzzleExtension;
use Psr\Container\ContainerInterface;

/**
 * Class CommandManager
 * @package Fnx\GuzzleBundle\Command
 */
class CommandManager
    implements CommandManagerInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;
    /**
     * @var array
     */
    private $commands = [];

    /**
     * CommandManager constructor.
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /** {@inheritdoc} */
    public function add(string $name, $command)
    {
        if (is_string($command) || $command instanceof Command) {
            $this->commands[ $name ] = $command;
        }
    }

    /** {@inheritdoc} */
    public function get(string $name) : Command
    {
        $command = isset($this->commands[ $name ]) ? $this->commands[ $name ] : null;

        switch (true) {
            case $command instanceof Command:
                return $command;
            case is_string($command):
                $commandName = FnxGuzzleExtension::COMMAND_PREFIX . $command;
                if (!$this->container->has($commandName)) {
                    throw new CommandNotFoundException($name);
                }

                return $this->container->get($commandName);
            default:
                throw new CommandNotFoundException($name);
        }
    }
}
