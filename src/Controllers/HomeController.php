<?php

namespace App\Controllers;

use App\kernel\Controller\Controller;
use App\Services\MovieService;

class HomeController extends Controller
{

    public function index(): void
    {
        $movies = new MovieService($this->db());

        $this->view('home',['movies'=>$movies->all()]);
    }
}
