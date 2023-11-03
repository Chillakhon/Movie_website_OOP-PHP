<?php

namespace App\Controllers;

use App\kernel\Controller\Controller;

class ReviewController extends Controller
{
    public function store ()
    {
        $validate = $this->request()->validate([
            'comment' => ['required' => true],
            'rating' => ['required' => true],
        ]);
        if (!$validate) {
            foreach ($this->request->errors() as $key => $value) {
                foreach ($value as $field => $errors) {
                    $this->session->set($field, $errors);
                }
            }
            $this->redirect("movie?id={$this->request()->input('id')}");
        }

        $this->db()->insert('reviews',[
            'review'=>$this->request()->input('comment'),
            'rating'=>$this->request()->input('rating'),
            'movie_id'=>$this->request()->input('id'),
            'user_id'=>$this->session->get('user_id')
        ]);
        $this->redirect("movie?id={$this->request()->input('id')}");
    }
}