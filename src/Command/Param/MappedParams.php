<?php

namespace Fnx\GuzzleBundle\Command\Param;

class MappedParams
{
    /**
     * @var \ArrayObject
     */
    public $uri;
    /**
     * @var \ArrayObject
     */
    public $query;
    /**
     * @var \ArrayObject
     */
    public $post;
    /**
     * @var \ArrayObject
     */
    public $header;
    /**
     * @var \ArrayObject
     */
    public $json;
    /**
     * @var string
     */
    private $body = '';

    public function __construct()
    {
        $this->header = new \ArrayObject();
        $this->post   = new \ArrayObject();
        $this->uri    = new \ArrayObject();
        $this->json   = new \ArrayObject();
        $this->query  = new \ArrayObject();
    }

    public function setBody(string $body)
    {
        $this->body = $body;
    }

    public function body()
    {
        return $this->body;
    }

    public function add(string $type, string $name, $value)
    {
        if (isset($this->$type) && $this->$type instanceof \ArrayObject) {
            /** @var \ArrayObject $bag */
            $bag = $this->$type;
            $bag->offsetSet($name, $value);
        }
    }
}
