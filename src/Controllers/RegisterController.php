<?php

namespace App\Controllers;

use App\kernel\Controller\Controller;

class RegisterController extends Controller
{
    public function index()
    {
        $this->view('register');
    }

    public function register()
    {
        $validation = $this->request()->validate(
            [   'name'=>['required'=>true,'max'=>25,'min'=>4],
                'email'=>['required'=>true,'email'],
                'password'=>['required'=>true,'min'=>8],
                'password_confirmation'=>['required'=>true,'matches'=>'password']
            ]);
        //dd($this->request->errors());
        if (!$validation){
            foreach ($this->request->errors() as $key => $value){
                foreach ($value as $field => $errors) {
                    $this->session->set($field, $errors);
                }
            }
            $this->redirect('register');
        }

          $this->db()->insert('users',[
            'name'=>$this->request()->input('name'),
            'email'=>$this->request()->input('email'),
            'password'=>password_hash($this->request()->input('password'),PASSWORD_DEFAULT)
        ]);
        $this->redirect('');
    }
}