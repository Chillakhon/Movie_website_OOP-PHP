<?php

namespace App\kernel\Router;

interface RouterInterface
{
    public function dispatch($uri, $method): void;

}