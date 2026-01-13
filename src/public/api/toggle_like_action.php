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
use App\Models\Like;
use App\Services\LikeServise;

if (!isset($_POST['tweet_id'])) {
    http_response_code(400);
    echo json_encode([
        "status" => "error",

        'error' => 'Tweet ID is missing'
    ]);
    exit;
}

$tweet_id = $_POST['tweet_id'];
$user_id = $_SESSION['user_id'];

try {
    $database = new Database();
    $db = $database->getConnection();
    $like = new Like($db);
    $like_service = new LikeServise($like);

    $is_liked = $like_service->toggle($user_id, $tweet_id);
    $new_like_count = $like_service->getLikeCount($tweet_id);

    echo json_encode([
        'status' => 'success',
        'is_liked' => $is_liked,
        'new_count' => $new_like_count
    ]);
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
