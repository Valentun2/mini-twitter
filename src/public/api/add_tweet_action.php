<?php
session_start();
header('Content-Type: application/json');

require_once __DIR__ . '/../../vendor/autoload.php';

use App\Core\Database;
use App\Models\Tweet;


if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode([
        "status" => "error",
        "message" => "Not authenticated"
    ]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        "status" => "error",
        "message" => "Method Not Allowed"
    ]);
    exit;
}

$text = $_POST['text'] ?? null;

if ($text === null || trim($text) === "") {
    http_response_code(400);
    echo json_encode([
        "status" => "error",
        "message" => "Text is required"
    ]);
    exit;
}

$text = trim($text);
$user_id = (int)$_SESSION['user_id'];

try {
    $database = new Database();
    $db = $database->getConnection();

    $tweetModel = new Tweet($db);
    $newTweet = $tweetModel->addTweet($user_id, $text);
    $newTweet['created_at'] = date('c', strtotime($newTweet['created_at']));

    http_response_code(201);
    echo json_encode([
        "status" => "success",
        "message" => "Tweet created",
        "tweet" => $newTweet
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
