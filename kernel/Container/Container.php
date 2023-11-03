<?php

namespace App\kernel\Container;

use App\kernel\Auth\Auth;
use App\kernel\Auth\AuthInterface;
use App\kernel\Config\Config;
use App\kernel\Config\ConfigInterface;
use App\kernel\Database\Database;
use App\kernel\Database\DatabaseInterface;
use App\kernel\Http\Redirect;
use App\kernel\Http\Request;
use App\kernel\Router\Router;
use App\kernel\Session\Session;
use App\kernel\Storage\Storage;
use App\kernel\Storage\StorageInterface;
use App\kernel\Validator\Validator;
use App\kernel\View\View;

class Container
{
    public Request $request;
    public Router $router;
    public View $view;
    public Validator $validator;
    public Redirect $redirect;
    public Session $session;
    public ConfigInterface $config;
    public DatabaseInterface $database;
    public AuthInterface $auth;
    public StorageInterface $storage;

    public function __construct()
    {
        $this->registerServices();
    }

    public function registerServices()
    {
         $this->request = Request::createFromGlobals();
        $this->validator = new Validator();
        $this->request->setValidator($this->validator);
        $this->redirect = new Redirect();
        $this->session = new Session();
        $this->config = new Config();
        $this->database = new Database($this->config);
        $this->auth = new Auth($this->database,$this->session,$this->config);
        $this->storage = new Storage($this->config);
        $this->view = new View($this->session,$this->auth,$this->storage) ;
        $this->router = new Router(
            $this->view,
            $this->request,
            $this->redirect,
            $this->session,
            $this->database,
            $this->auth,
            $this->storage);
    }
}