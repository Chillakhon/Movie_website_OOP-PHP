<?php

namespace App\Controllers;

use App\kernel\Controller\Controller;
use App\Services\CategoryService;
use App\Services\MovieService;

class MoviesController extends Controller
{
    private MovieService $service;
    private CategoryService $categoryService;

    public function create(): void
    {
        $categories = $this->categoryService()->all();
        $this->view('admin/movies/add',['categories'=>$categories]);
    }

    public function store(): void
    {
         $validate = $this->request()->validate([
             'name'=> ['required'=>true, 'max'=>20, 'min'=>3],
             'description'=>['required'=>true],
             'category'=>['required'=>true]
             ]);
        if(!$validate){
            foreach ($this->request->errors() as $key => $value) {
                foreach ($value as $field => $errors) {
                    $this->session->set($field, $errors);
                }
            }
             $this->redirect('admin/movies/add');
        }
         $this->service()->store(
            $this->request->input('name'),
            $this->request->input('description'),
            $this->request->file('image'),
            $this->request->input('category'),
        );
        $this->redirect('admin');
    }

    public function destroy()
    {
        $this->service()->delete($this->request->input('id'));
        $this->redirect('admin');
    }

    public function edit()
    {
        $id = $this->request->input('id');
        $categories = $this->categoryService()->all();
        $this->view('admin/movies/update',[
            'movie'=>$this->service()->find($id),
            'categories'=>$categories
        ]);
    }

    public function update()
    {
        $validate = $this->request()->validate([
            'name'=> ['required'=>true, 'max'=>20, 'min'=>3],
            'description'=>['required'=>true],
        ]);
        if(!$validate){
            foreach ($this->request->errors() as $key => $value) {
                foreach ($value as $field => $errors) {
                    $this->session->set($field, $errors);
                }
            }
            $this->redirect("admin/movies/update?id={$this->request->input('id')}");
        }
        $this->service()->update(
            $this->request()->input('id'),
            $this->request()->input('name'),
            $this->request()->input('description'),
            $this->request()->file('image'),
            $this->request()->input('category'),
        );
        $this->redirect('admin');
    }

    public function show(): void
    {
        $this->view('/movie',[
            'movie'=>$this->service()->find($this->request()->input('id'))
        ]);
    }

    private function service(): MovieService
    {
        if (!isset($this->service)){
            $this->service = new MovieService($this->db());
        }
        return $this->service;
    }

    private function categoryService(): CategoryService
    {
        if (!isset($this->categoryService)){
            $this->categoryService = new CategoryService($this->db());
        }
        return $this->categoryService;
    }

}
