<?php

namespace App\kernel\Middleware;

interface MiddlewareInterface
{
    public function check(array $middlewares = []): void;
}