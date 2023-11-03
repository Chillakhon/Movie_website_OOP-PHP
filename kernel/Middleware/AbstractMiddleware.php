<?php

namespace App\kernel\Middleware;

use App\kernel\Auth\AuthInterface;
use App\kernel\Http\RedirectInterface;
use App\kernel\Http\RequestInterface;

abstract class AbstractMiddleware
{
    public function __construct(
       protected RequestInterface $request,
        protected AuthInterface $auth,
        protected RedirectInterface $redirect
    ){
    }

    abstract public function handle(): void;
}