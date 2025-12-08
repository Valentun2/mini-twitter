<?php
session_start();
header('Content-Type: application/json');


require_once __DIR__ . '/../../vendor/autoload.php';

use App\Core\Database;
use App\Models\Tweet;


if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Method Not Allowed']);
    exit;
}

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['status' => 'error', 'message' => 'Not authenticated']);
    exit;
}
$raw = file_get_contents('php://input');
$data = json_decode($raw, true);
if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
    http_response_code(400);
    echo json_encode([
        "status" => "error",

        'message' => 'Invalid JSON: ' . json_last_error_msg()
    ]);
    exit;
}

$tweet_id = $data['tweet_id'] ?? null;
if (!$tweet_id || !filter_var($tweet_id, FILTER_VALIDATE_INT)) {
    http_response_code(400);
    echo json_encode([
        "status" => "error",
        'message' => 'Missing or invalid tweet_id'
    ]);
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
        http_response_code(403);
        echo json_encode([
            "status" => "error",
            'message' => 'Cannot delete this tweet. Not the owner?'
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
