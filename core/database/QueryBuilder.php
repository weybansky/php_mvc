<?php

namespace App\Core\Database;

use PDO;

class QueryBuilder
{
    public $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function selectAll($table)
    {
        $stmt = $this->pdo->prepare("SELECT * from {$table}");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS);
    }

    public function insert($table, $data)
    {
        $query = sprintf(
            "INSERT INTO %s (%s) VALUES (%s)",
            $table,
            implode(", ", array_keys($data)),
            ":" . implode(", :", array_keys($data))
        );
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($data);
    }
}
