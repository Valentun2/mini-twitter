<?php
session_start();
header('Content-Type: application/json');


require_once __DIR__ . '/../../vendor/autoload.php';

use App\Core\Database;
use App\Models\User;

// use PDOException;


$database = new Database();
$db = $database->getConnection();
$user = new User($db);



if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $errors = [];
    $name = $_POST['name'] ?? "";
    $email = $_POST['email'] ?? "";
    $password = $_POST['password'] ?? "";
    if (strlen($name) < 3) {
        $errors['name'] = "Ім'я користувача має бути не менше 3 символів.";
    } elseif (strlen($name) > 20) { // Додано перевірку на максимальну довжину
        $errors['name'] = "Ім'я користувача має бути не більше 20 символів.";
    }

    if (strlen($password) < 6) {
        $errors['password'] = "Пароль користувача має бути не менше 6 символів.";
    }

    if (empty($email)) {
        $errors['email'] = "Поле email не може бути порожнім.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Некоректний email.";
    } else {
        try {
            $stmt = $db->prepare("SELECT COUNT(*) FROM users WHERE email = :email");
            $stmt->bindParam(':email', $email);

            $stmt->execute();
            $count = $stmt->fetchColumn();

            if ($count > 0) {
                $errors['email'] = 'Цей Email вже зареєстрований.';
            }
        } catch (PDOException $e) {
            // Логувати $e->getMessage();
            $errors['email'] = 'Виникла помилка при перевірці Email. Спробуйте пізніше.';
        }
    }

    if (!empty($errors)) {
        echo json_encode([
            "success" => false,
            "errors" => $errors, // Відправляємо весь масив помилок
        ]);
        exit;
    }

    try {
        $newUserId = $user->register($name, $email, $password);
        if ($newUserId) {
            $_SESSION['user_id'] = $newUserId;
            $_SESSION['user_name'] = $name;
            echo json_encode([
                "success" => true,
            ]);
            exit;
        } else {
            echo json_encode([
                "success" => false,
            ]);
            exit;
        }
    } catch (PDOException $e) {
        echo json_encode([
            "success" => false,
            "error" => $e
        ]);
    }
}
