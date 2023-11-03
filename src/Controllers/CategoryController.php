<?php

namespace App\Controllers;

use App\kernel\Controller\Controller;
use App\Services\CategoryService;

class CategoryController extends Controller
{
    private CategoryService $service;

    public function create(): void
    {
        $this->view('admin/categories/add');
    }

    public function store(): void
    {
        $validation = $this->request->validate(
            [
                'name' => ['required'=>true, 'min'=>3,'max'=>255]
            ]);

        if (!$validation) {
            foreach ($this->request->errors() as $key => $value) {
                foreach ($value as $field => $errors) {
                    $this->session->set($field, $errors);
                }
            }
            $this->redirect('admin/categories/add');
        }
        $this->service()->store($this->request()->input('name'));
        $this->redirect('admin');
    }

    public function destroy(): void
    {
        $idCategories = $this->request->input('id');

        $this->service()->delete($idCategories);
        $this->redirect('admin');
    }

    public function edit(): void
    {
        $id = $this->request()->input('id');
        $this->view('admin/categories/update',[
            'category'=>$this->service()->find($id)
        ]);

    }
    public function update(): void
    {
        $name = $this->request->input('name');
        $id = $this->request->input('id');

        $validation = $this->request->validate(
            [
                'name' => ['required'=>true, 'min'=>3,'max'=>255]
            ]);

        if (!$validation) {
            foreach ($this->request->errors() as $key => $value) {
                foreach ($value as $field => $errors) {
                    $this->session->set($field, $errors);
                }
            }
            $this->redirect("admin/categories/update?id=$id");
        }
          $this->service()->update($name,$id);
        $this->redirect('admin');
    }
    private function service(): CategoryService
    {
        if (!isset($this->service)){
            $this->service = new CategoryService($this->db());
        }
        return $this->service;
    }
}