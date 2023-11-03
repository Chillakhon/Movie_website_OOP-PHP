<?php

namespace App\Models;

use App\kernel\Auth\User;

class Review
{
    public function __construct(
        private int $id,
        private string $rating,
        private string $comment,
        private string $createAt,
        private User $user,
    )
    {}

    /**
     * @return string
     */
    public function createAt(): string
    {
        return $this->createAt;
    }

    /**
     * @return string
     */
    public function comment(): string
    {
        return $this->comment;
    }

    /**
     * @return int
     */
    public function id(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function rating(): string
    {
        return $this->rating;
    }

    /**
     * @return User
     */
    public function user(): User
    {
        return $this->user;
    }
}