<?php

namespace App\kernel\Router;

use App\kernel\Auth\Auth;
use App\kernel\Controller\Controller;
use App\kernel\Database\DatabaseInterface;
use App\kernel\Http\RedirectInterface;
use App\kernel\Http\RequestInterface;
use App\kernel\Middleware\AbstractMiddleware;
use App\kernel\Session\SessionInterface;
use App\kernel\Storage\Storage;
use App\kernel\View\ViewInterface;

class Router implements RouterInterface
{
    private array $routes = [
        'GET' => [],
        'POST' => [],
    ];

    public ViewInterface $view;
    public RequestInterface $request;
    public RedirectInterface $redirect;
    public SessionInterface $session;

    public function __construct($view,$request,$redirect,$session,
    public DatabaseInterface $database,
    public Auth $auth,
    public Storage $storage,
    )
    {
        $this->initRoutes();
        $this->view = $view;
        $this->request = $request;
        $this->redirect = $redirect;
        $this->session = $session;
    }

    public function dispatch($uri, $method): void
    {
        $route = $this->findRouter($uri, $method);
        if (! $route) {
            $this->notfound();
        }else {

            if ($route->hasMiddlewares()){
                foreach ($route->getMiddlewares() as $middleware){
                    /** @var AbstractMiddleware $middleware */
                    $middleware = new $middleware($this->request, $this->auth, $this->redirect);

                    $middleware->handle();
                }
            }

            if (is_array($route->getAction())){
                [$controller,$action] = $route->getAction();

                 /** @var Controller $controller */

                $controller = new $controller();

                call_user_func([$controller,'setView'],$this->view);
                call_user_func([$controller,'setRequest'],$this->request);
                call_user_func([$controller,'setRedirect'],$this->redirect);
                call_user_func([$controller,'setSession'],$this->session);
                call_user_func([$controller,'setDatabase'],$this->database);
                call_user_func([$controller,'setAuth'],$this->auth);
                call_user_func([$controller,'setStorage'],$this->storage);
                call_user_func([$controller,$action]);
            }else{
                call_user_func($route->getAction());
            }
        }
    }

    private function notfound()
    {
        echo '404 | not found';
    }

    private function findRouter($uri, $method): Route|false
    {
        if (! isset($this->routes[$method][$uri])) {
            return false;
        }

        return $this->routes[$method][$uri];
    }

    private function initRoutes()
    {
        $routes = $this->getRouter();
        foreach ($routes as $route) {
            $this->routes[$route->getMethod()][$route->uri] = $route;
        }
    }

    /**
     * @return Route[]
     */
    private function getRouter(): array
    {
        return require_once APP_PATH.'/config/routes.php';
    }
}
