<?php

namespace App\kernel\Http;

class Redirect implements RedirectInterface
{
    public function to(string $url)
    {
        header("location:/$url");
        exit();
    }
}