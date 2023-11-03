<?php

namespace App\kernel\Controller;

use App\kernel\Auth\AuthInterface;
use App\kernel\Database\DatabaseInterface;
use App\kernel\Http\RedirectInterface;
use App\kernel\Http\RequestInterface;
use App\kernel\Session\SessionInterface;
use App\kernel\Storage\StorageInterface;
use App\kernel\View\ViewInterface;

abstract class Controller
{
    private ViewInterface $view;

    protected RequestInterface $request;

    private RedirectInterface $redirect;

    protected SessionInterface $session;

    private  DatabaseInterface $database;

    private AuthInterface $auth;

    private StorageInterface $storage;

    public function view(string $name,array $data = [])
    {
        $this->view->page($name,$data);
    }

    /**
     * @param ViewInterface $view
     */
    public function setView(ViewInterface $view): void
    {
        $this->view = $view;
    }

    /**
     * @return RequestInterface
     */
    public function request(): RequestInterface
    {
        return $this->request;
    }

    /**
     * @param RequestInterface $request
     */
    public function setRequest(RequestInterface $request): void
    {
        $this->request = $request;
    }

    public function redirect(string $url)
    {
        $this->redirect->to($url);
    }

    /**
     * @param RedirectInterface $redirect
     */
    public function setRedirect(RedirectInterface $redirect): void
    {
        $this->redirect = $redirect;
    }

    public function setSession(SessionInterface $session)
    {
        $this->session = $session;
    }

    /**
     * @return SessionInterface
     */
    public function getSession(): SessionInterface
    {
        return $this->session;
    }

    /**
     * @param DatabaseInterface $database
     */
    public function setDatabase(DatabaseInterface $database): void
    {
        $this->database = $database;
    }

    /**
     * @return DatabaseInterface
     */
    public function db(): DatabaseInterface
    {
        return $this->database;
    }


    /**
     * @param AuthInterface $auth
     */
    public function setAuth(AuthInterface $auth): void
    {
        $this->auth = $auth;
    }

    /**
     * @return AuthInterface
     */
    public function Auth(): AuthInterface
    {
        return $this->auth;
    }

    /**
     * @param StorageInterface $storage
     */
    public function setStorage(StorageInterface $storage): void
    {
        $this->storage = $storage;
    }

    /**
     * @return StorageInterface
     */
    public function storage(): StorageInterface
    {
        return $this->storage;
    }
}