<?php
session_start();
header('Content-Type: application/json');


require_once __DIR__ . '/../../vendor/autoload.php';

use App\Core\Database;
use App\Models\User;
use App\Services\AuthService;
use App\Services\PasswordService;

$database = new Database();
$db = $database->getConnection();
$user_repository = new User($db);
$password_service = new PasswordService();

$auth_service = new AuthService(
    $user_repository,
    $password_service
);

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
}

if (!empty($errors)) {

    echo json_encode([
        "status" => "error",
        "errors" => $errors,
    ]);
    exit;
}

try {
    $new_user_id = $auth_service->register($name, $email, $password);
    $_SESSION['user_id'] = $new_user_id;
    $_SESSION['user_name'] = $name;
    echo json_encode([
        "status" => "success",
    ]);
    exit;
} catch (DomainException $e) {
    // http_response_code(422);

    echo json_encode([
        "status" => "error",
        "errors" => [
            "email" => $e->getMessage()
        ],
        JSON_UNESCAPED_UNICODE
    ]);
    exit;
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
