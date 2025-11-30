<?php
session_start();
header('Content-Type: application/json');
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Not authenticated']);
    exit;
}
require_once __DIR__ . '/../../vendor/autoload.php';

use App\Core\Database;
use App\Models\Tweet;




$raw = file_get_contents('php://input');
$data = json_decode($raw, true);
if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid JSON: ' . json_last_error_msg()]);
    exit;
}

$tweet_id = $data['tweet_id'] ?? null;
if ($tweet_id === null || !is_numeric($tweet_id)) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing or invalid tweet_id']);
    exit;
}

$tweet_id = (int)$tweet_id;
$user_id = $_SESSION['user_id'];


try {
    $database = new Database();
    $db = $database->getConnection();
    $tweet = new Tweet($db);
    if ($tweet->deleteTweet($tweet_id, $user_id)) {
        echo json_encode([
            "status" => "success",
            "message" => "Tweet deleted"
        ]);
        exit;
    } else {
        http_response_code(403); // 403 = Forbidden (Заборонено)
        echo json_encode([
            "status" => "error",
            'error' => 'Cannot delete this tweet. Not the owner?'
        ]);
        exit;
    }
} catch (Exception $e) {
    http_response_code(500); // Internal Server Error
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    exit;
}
