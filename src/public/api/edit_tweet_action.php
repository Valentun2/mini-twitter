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
$new_text = $data['new_text'] ?? null;
if ($tweet_id === null || !is_numeric($tweet_id) || $new_text === null) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing or invalid tweet_id']);
    exit;
}


$tweet_id = (int)$tweet_id;
$user_id = $_SESSION['user_id'];

try {
    $db = new Database();
    $conn = $db->getConnection();
    $tweet = new Tweet($conn);
    if ($tweet->editTweet($tweet_id, $user_id, $new_text)) {
        echo json_encode([
            "status" => "success",
            "message" => "Tweet updated",
            "newText" => "$new_text"
        ]);
        exit;
    } else {
        http_response_code(403); // 403 = Forbidden (Заборонено)
        echo json_encode([
            "status" => "error",
            'error' => 'Cannot update this tweet. Not the owner?'
        ]);
        exit;
    }
} catch (Exception $e) {
    http_response_code(500); // Internal Server Error
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    exit;
}
