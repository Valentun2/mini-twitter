<?php
session_start();
header('Content-Type: application/json');


require_once __DIR__ . '/../../vendor/autoload.php';

use App\Services\AuthService;
use App\Core\Database;
use App\Models\User;
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

$error = [];

$email = $_POST['email'];
$password = $_POST['password'];
if (empty($email)) {
    $errors['email'] = "Поле email не може бути порожнім.";
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors['email'] = "Некоректний email.";
}

if (strlen($password) < 6) {
    $errors['password'] = "Пароль користувача має бути не менше 6 символів.";
}


if (!empty($errors)) {
    echo json_encode([
        "status" => "error",
        "errors" => $errors,
    ]);
    exit();
}

try {
    $loggedInUser = $auth_service->login($email, $password);
    if ($loggedInUser) {
        session_regenerate_id(true);
        $_SESSION['user_id'] = $loggedInUser['id'];
        $_SESSION['user_name'] = $loggedInUser['name'];
        echo json_encode([
            "status" => "success",
        ]);
        exit;
    } else {
        $errors['email'] = "Не вірний пароль або email.";

        $errors['password'] = "Не вірний пароль або email.";

        echo json_encode([
            "status" => "error",
            "errors" => $errors
        ]);
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
