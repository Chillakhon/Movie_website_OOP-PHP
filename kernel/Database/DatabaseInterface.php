<?php

namespace App\kernel\Database;

interface DatabaseInterface
{
    public function insert(string $table, array $data): int|false;

    public function first(string $table, array $condition = []): ?array;

    public function get(string $table, array $condition = [], array $order = [], int $limit = -1): array;

    public function delete(string $table, array $condition = []): void;

    public function update(string $table, array $data, array $condition = []): void;

}