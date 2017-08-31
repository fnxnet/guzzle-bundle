<?php

namespace Fnx\GuzzleBundle\Command;

use Throwable;

class CommandNotFoundException
    extends \RuntimeException
{
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        $message = sprintf('Command %s not found.', $message);
        parent::__construct(
            $message,
            $code,
            $previous
        );
    }

}
