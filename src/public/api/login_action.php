<?php
session_start();
header('Content-Type: application/json');


require_once __DIR__ . '/../../vendor/autoload.php';

use App\Core\Database;
use App\Models\User;



$database = new Database();
$db = $database->getConnection();
$user = new User($db);





if ($_SERVER["REQUEST_METHOD"] == "POST") {

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
            "success" => false,
            "errors" => $errors, // Відправляємо весь масив помилок
        ]);
        exit();
    }

    try {
        $loggedInUser = $user->login($email, $password);

        if ($loggedInUser) {

            $_SESSION['user_id'] = $loggedInUser['id'];
            $_SESSION['user_name'] = $loggedInUser['name'];
            echo json_encode([
                "success" => true,
            ]);
            exit;
        } else {
            $errors['email'] = "Не вірний пароль або email.";

            $errors['password'] = "Не вірний пароль або email.";

            echo json_encode([
                "success" => false,
                "errors" => $errors
            ]);
        }
    } catch (\Throwable $th) {
        //throw $th;
    }
}
