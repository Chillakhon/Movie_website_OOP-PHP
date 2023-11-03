<?php

namespace App\Services;

use App\kernel\Auth\User;
use App\kernel\Database\DatabaseInterface;
use App\kernel\Upload\UploadedFileInterface;
use App\Models\Movie;
use App\Models\Review;

class MovieService
{
    public function __construct(
        private DatabaseInterface $db
    )
    {}

    public function store(string $name, string $description, UploadedFileInterface $image, string $category): false|int
    {
        $filePath =  $image->move('movies');

        return $this->db->insert('movies',
            [
                'name'=>$name,
                'description'=>$description,
                'preview'=>$filePath,
                'category_id'=>$category
            ]);
    }

    public function all(): array
    {
        $movies = $this->db->get('movies');

        return array_map(function ($movie){
            return new Movie(
                $movie['id'],
                $movie['name'],
                $movie['description'],
                $movie['preview'],
                $movie['category_id'],
                $movie['created_at'],
            );
        }, $movies);
    }

    public function delete(int $id): void
    {
        $this->db->delete('movies',[
            'id'=>$id
        ]);
    }

    public function update(int $id, string $name, string $description, UploadedFileInterface $image, int $category): void
    {
        $data = [
            'name'=>$name,
            'description'=>$description,
            'category_id'=>$category,
        ];

        if ($image && ! $image->hasError()){
            $filePath = $image->move('movies');
            $data['preview'] = $filePath;
        }
        $this->db->update('movies',$data, [
                'id'=>$id
            ]);
    }

    public function new()
    {
        $news = $this->db->get('movies');
    }

    public function find(int $id): ?Movie
    {
        $movies = $this->db->first('movies', ['id'=>$id ]);

        if (!$movies)
        {
            return null;
        }

        return new Movie(
            id: $movies['id'],
            name: $movies['name'],
            description: $movies['description'],
            preview: $movies['preview'],
            categoryId: $movies['category_id'],
            createdAt: $movies['created_at'],
            reviews: $this->getReviews($id)
        );
    }

    private function getReviews(int $id): array
    {
        $reviews = $this->db->get('reviews',[
            'movie_id'=>$id]);


         return array_map(function ($review){

             $user = $this->db->first('users',[
                 'id'=>$review['user_id']
             ]);

            return new Review(
                $review['id'],
                $review['rating'],
                $review['review'],
                $review['created_at'],
                new User(
                    $user['id'],
                    $user['name'],
                    $user['email'],
                    $user['password'],
                )
            );
        },$reviews);

    }
}