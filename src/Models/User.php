<?php

namespace App\Models;

use PDO;

class User
{
    private string $table = 'users';

    public function __construct(
        private PDO $conn
    ) {}

    public function create(string $name, string $email, string $hashedPassword): int|false
    {
        $query = "INSERT INTO {$this->table} (name, email, password)
                  VALUES (:name, :email, :password)";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedPassword);

        if ($stmt->execute()) {
            return (int)$this->conn->lastInsertId();
        }

        return false;
    }

    public function findByEmail(string $email): array|false
    {
        $query = "SELECT * FROM {$this->table}
                  WHERE email = :email
                  LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
