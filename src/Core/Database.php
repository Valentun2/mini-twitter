<?php

namespace App\Core;

// Кажемо PHP, що ми будемо використовувати ці вбудовані класи
use PDO;

class Database
{


    private $host;
    private $db_name;
    private $username;
    private $password;
    public $conn;

    public function __construct()
    {

        $config = require __DIR__ . '/../config.php';

        $this->host = $config['db_host'];
        $this->db_name = $config['db_name'];
        $this->username = $config['db_user'];
        $this->password = $config['db_pass'];
    }

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
