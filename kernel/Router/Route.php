<?php

namespace App\kernel\Router;

class Route
{
    public $uri;

    public $method;

    public $action;

    public function __construct($uri, $method, $action,
    private array $middlewares = []
    )
    {
        $this->uri = $uri;
        $this->method = $method;
        $this->action = $action;
    }

    public static function get($uri, $action, array $middlewares = []): static
    {
        return new static($uri,'GET',$action,$middlewares);
    }

    public static function post($uri, $action,$middlewares = []): static
    {
        return new static($uri,'POST',$action, $middlewares);
    }

    public function getUri()
    {
        return $this->uri;
    }

    public function getAction()
    {
        return $this->action;
    }

    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @return array
     */
    public function getMiddlewares(): array
    {
        return $this->middlewares;
    }

    /**
     * @param array $middlewares
     */
    public function hasMiddlewares(): bool
    {
       return ! empty($this->middlewares);
    }
}
