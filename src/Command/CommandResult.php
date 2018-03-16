<?php

namespace Fnx\GuzzleBundle\Command;

use Psr\Http\Message\ResponseInterface;

class CommandResult
{
    /**
     * @var ResponseInterface|null
     */
    private $response;
    /**
     * @var \Exception
     */
    private $error;

    public function setResponse(ResponseInterface $response)
    {
        $this->response = $response;
    }

    public function getResponse()
    {
        return $this->response;
    }

    public function setError(\Exception $exception)
    {
        $this->error = $exception;
    }

    public function getError()
    {
        return $this->error;
    }
}
