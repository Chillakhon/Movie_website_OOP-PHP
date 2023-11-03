<?php

namespace App\kernel\View;

use App\kernel\Auth\AuthInterface;
use App\kernel\Session\SessionInterface;
use App\kernel\Storage\StorageInterface;

class View implements ViewInterface
{

    public SessionInterface $session;

    public function __construct($session,
    public AuthInterface $auth,
    public StorageInterface $storage
    )
    {$this->session = $session;
    }

    public function page(string $name, array $data = []):void
    {
        extract(array_merge($this->defaultData(),$data));

        include_once APP_PATH.'/views/pages/'.$name.'.php';
    }

    public function component(string $name, array $data = []):void
    {
        extract(array_merge($this->defaultData(),$data));
        include APP_PATH.'/views/components/'.$name.'.php';
    }

    private function defaultData(): array
    {
        return [
            'view'=>$this,
            'session'=>$this->session,
            'auth'=>$this->auth,
            'storage'=>$this->storage
        ];
    }

}