<?php

namespace Fnx\GuzzleBundle\Command;

interface CommandManagerInterface
{
    /**
     * @param string         $name
     * @param string|Command $command
     *
     * @return mixed
     */
    public function add(string $name, $command);

    /**
     * @param string $name
     *
     * @return Command
     * @throws CommandNotFoundException
     */
    public function get(string $name) : Command;
}