<?php

namespace App\kernel\Config;

interface ConfigInterface
{
    public function get(string $key,$default = null): mixed;
}