<?php

namespace App\kernel\Auth;

use App\kernel\Config\ConfigInterface;
use App\kernel\Database\DatabaseInterface;
use App\kernel\Session\SessionInterface;

class Auth implements AuthInterface
{
    public function __construct(
        private DatabaseInterface $db,
        public SessionInterface $session,
        public ConfigInterface $config
    )
    {}

    public function attempt(string $username, string $password): bool
    {

        $user = $this->db->first($this->table(),[
            $this->username()=>$username,
            ]);

        if (!$user){
            return false;
        }
        if (!password_verify($password,$user[$this->password()])){
            return false;
        }

        $this->session->set($this->sessionField(), $user['id']);

        return true;
    }

    public function check(): bool
    {
        return $this->session->has($this->sessionField());
    }

    public function logout(): void
    {
        $this->session->remove($this->sessionField());
    }
    public function user(): ?User
    {
        if (! $this->check()){
            return null;
        }

        $user = $this->db->first($this->table(),[
            'id'=>$this->session->get($this->sessionField())
        ]);

        if ($user){
            return new User(
                $user['id'],
                $user['name'],
                $user['email'],
                $user['password']
            );
        }
        return null;
    }

    public function table(): string
    {
        return $this->config->get('auth.table','users');
    }

    public function username(): string
    {
        return $this->config->get('auth.username','email');
    }

    public function password(): string
    {
        return $this->config->get('auth.password','password');
    }

    public function sessionField(): string
    {
        return $this->config->get('auth.session_field','user_id');
    }

}