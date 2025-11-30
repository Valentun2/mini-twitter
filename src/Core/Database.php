<?php

namespace App\Core;

// Кажемо PHP, що ми будемо використовувати ці вбудовані класи
use PDO;

class Database
{


    private $host = 'mysql'; // Назва сервісу з docker-compose.yml
    private $db_name = 'my_database';
    private $username = 'root';
    private $password = 'mysecretpassword';
    public $conn; // З'єднання

    // Метод, який буде підключатися до БД
    public function getConnection()
    {
        $this->conn = null;



        $dsn = "mysql:host=" . $this->host . ";dbname=" . $this->db_name;
        $this->conn = new PDO($dsn, $this->username, $this->password);

        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        $this->conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $this->conn->exec("SET NAMES utf8mb4");

        return $this->conn;
    }
}
