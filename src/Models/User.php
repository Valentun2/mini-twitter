<?php

namespace App\Models;

use PDO;
use PDOException;

class User
{
    private $conn;

    private $table_name = "users";


    public function __construct($db)
    {
        $this->conn = $db;
    }


    public function register($name, $email, $password)
    {

        $query = "INSERT INTO " . $this->table_name . " (name, email, password) VALUES (:name, :email, :password)";

        $stmt = $this->conn->prepare($query);


        $password = password_hash($password, PASSWORD_BCRYPT);

        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);

        if ($stmt->execute()) {
            return $this->conn->lastInsertId();
        }
        return false;
    }


    public function login($email, $password)
    {
        $query = "SELECT * FROM " . $this->table_name . "
                  WHERE email = :email
                  LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$user) {
            return false;
        }

        if (!password_verify($password, $user['password'])) {
            return false;
        }

        unset($user['password']);

        return $user;
    }
}
