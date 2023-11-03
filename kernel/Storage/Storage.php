<?php

namespace App\kernel\Storage;

use App\kernel\Config\ConfigInterface;

class Storage implements StorageInterface
{
    public function __construct(
        private ConfigInterface $config
    ){
    }

    public function url(string $path): string
    {
        $url = $this->config->get('app.url','http://localhost:8888');

        return "$url/storage/$path";
    }

    public function get(string $path): string
    {
        return file_get_contents($this->storagePath($path));
    }

    public function storagePath(string $path): string
    {
        return APP_PATH . "/storage/$path";
    }
}