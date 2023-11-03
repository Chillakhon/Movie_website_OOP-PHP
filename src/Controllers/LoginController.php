<?php

namespace App\Controllers;

use App\kernel\Controller\Controller;

class LoginController extends Controller
{
    public function index():void
    {
        $this->view('login');
    }

    public function login()
    {
        $email = $this->request->input('email');
        $password = $this->request->input('password');

      $login = $this->Auth()->attempt($email, $password);
      if (!$login){
          $this->session->set('error','Incorrect login or password');
          $this->redirect('login');
          exit();
      }
      $this->redirect('');
    }

    public function logout()
    {
        $this->Auth()->logout();
        $this->redirect('login');
    }
}