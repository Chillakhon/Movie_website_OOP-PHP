<?php

namespace App\kernel\Http;

interface RedirectInterface
{
    public function to(string $url);
}