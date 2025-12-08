<?php
session_start();
header('Content-Type: application/json');


require_once __DIR__ . '/../../vendor/autoload.php';

use App\Core\Database;
use App\Models\User;



$database = new Database();
$db = $database->getConnection();
$user = new User($db);



if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    echo json_encode([
        "status" => "error",
        "message" => "Method Not Allowed"
    ]);
    exit;
}

$errors = [];
$name = $_POST['name'] ?? "";
$email = $_POST['email'] ?? "";
$password = $_POST['password'] ?? "";
if (strlen($name) < 3) {
    $errors['name'] = "Ім'я користувача має бути не менше 3 символів.";
} elseif (strlen($name) > 20) {
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
        $errors['email'] = 'Виникла помилка при перевірці Email. Спробуйте пізніше.';
    }
}

if (!empty($errors)) {
    echo json_encode([
        "status" => "error",
        "errors" => $errors,
    ]);
    exit;
}

try {
    $newUserId = $user->register($name, $email, $password);
    if ($newUserId) {
        $_SESSION['user_id'] = $newUserId;
        $_SESSION['user_name'] = $name;
        echo json_encode([
            "status" => "success",
        ]);
        exit;
    } else {
        echo json_encode([
            "status" => "error",
        ]);
        exit;
    }
} catch (PDOException $e) {
    error_log("Database Error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "message" => "Internal Server Error"
    ]);
} catch (Exception $e) {
    error_log("General Error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "message" => "An unexpected error occurred"
    ]);
}
