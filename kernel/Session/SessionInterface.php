<?php

namespace App\kernel\Session;

interface SessionInterface
{
    public function set(string $key, $value);

    public function get(string $key, $default = null);

    public function getFlash(string $key, $default = null);

    public function has($key):bool;

    public function remove(string $key);

    public function destroy():void;

}