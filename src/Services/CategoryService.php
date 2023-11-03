<?php

namespace App\Services;

use App\kernel\Database\DatabaseInterface;
use App\Models\Category;

class CategoryService
{
    public function __construct(
       private DatabaseInterface $db
    ){
    }

    /**
     * @return array<Category>
     */
    public function all(): array
    {
        $categories = $this->db->get('categories');
        return array_map(function ($category){
            return new Category(
                id: $category['id'],
                name: $category['name'],
                createAt: $category['created_at'],
                updateAt:$category['updated_at']
            );
        },$categories );
    }

    public function delete(int $id): void
    {
        $this->db->delete('categories',[
            'id'=>$id
        ]);
    }

    public function store(string $name): void
    {
        $this->db->insert('categories',[
            'name'=>$name
        ]);
    }

    public function update(string $name, int $id): void
    {
        $this->db->update('categories',
            ['name'=>$name],
            ['id'=>$id]
        );
    }

    public function find(int $id): ?Category
    {
        $category = $this->db->first('categories', ['id'=>$id ]);

        if (!$category)
        {
            return null;
        }

        return new Category(
            id: $category['id'],
            name: $category['name'],
            createAt: $category['created_at'],
            updateAt: $category['updated_at']
        );
    }
}