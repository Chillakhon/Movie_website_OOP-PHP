<?php

namespace App\kernel\Database;

use App\kernel\Config\ConfigInterface;

class Database implements DatabaseInterface
{
    public $pdo;

    public function __construct(
        public ConfigInterface $config
    )
    {
        $this->connect();
    }

    public function insert(string $table, array $data): int|false
    {
        $fields = array_keys($data);

        $columns = implode(',',$fields);

        $binds = implode(',', array_map( fn($field) => ":$field", $fields));

        $sql = "INSERT INTO $table ($columns) VALUE ($binds)";

        $stmt = $this->pdo->prepare($sql);

        try {
            $stmt->execute($data);
        }catch (\PDOException $exception){
            return false;
        }
        return (int) $this->pdo->lastInsertId();
    }

    private function connect()
    {
        $driver = $this->config->get('database.driver');
        $dbname = $this->config->get('database.dbname');
        $host = $this->config->get('database.host');
        $username = $this->config->get('database.username');
        $password = $this->config->get('database.password');
        try {
            $this->pdo = new \PDO("$driver:host=$host;dbname=$dbname",
                "$username",
                "$password");
        }catch (\PDOException $exception){
            exit("Database connection failed: {$exception->getMessage()}");
        }
    }

    public function first(string $table, array $condition = []): ?array
    {
        $where = '';

        if (count($condition) > 0 ) {
            $where = 'WHERE '.implode(' AND ', array_map(fn($field) => "$field = :$field", array_keys($condition)));
        }

        $sql = "SELECT * FROM $table $where LIMIT 1";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($condition);
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
       return $result ?: null;

    }

    public function get(string $table, array $condition = [], array $order = [], int $limit = -1): array
    {
        $where = '';

        if (count($condition) > 0 ) {
            $where = 'WHERE '.implode(' AND ', array_map(fn($field) => "$field = :$field", array_keys($condition)));
        }

        $sql = "SELECT * FROM $table $where";

        /*if (count($order) > 0 ) {
            $sql = ' ORDER BY '. implode(', ', array_map(fn($field,$direction) => "$field $direction = :$field", array_keys($order)));
        }*/


        $stm = $this->pdo->prepare($sql);
        $stm->execute($condition);
        return $stm->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function delete(string $table, array $condition = []): void
    {
        $where = '';

        if (count($condition) > 0 ) {
            $where = 'WHERE '.implode(' AND ', array_map(fn($field) => "$field = :$field", array_keys($condition)));
        }
        $sql = "DELETE FROM $table $where";
        $stm = $this->pdo->prepare($sql);
        $stm->execute($condition);
    }

    public function update(string $table, array $data, array $condition = []): void
    {
        $fields = array_keys($data);

        $set = implode(', ' ,array_map(fn($field)=>"$field = :$field",$fields));

        $where = '';
        if (count($condition) > 0 ) {
            $where = 'WHERE '.implode(' AND ', array_map(fn($field) => "$field = :$field", array_keys($condition)));
        }
        $sql = "UPDATE $table SET $set $where";
        $stm = $this->pdo->prepare($sql);
        $stm->execute(array_merge($data,$condition));
    }
}