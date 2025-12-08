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
    $tweet_model = new Tweet($db);

    if ($tweet_model->isLikedByUser($user_id, $tweet_id)) {

        $tweet_model->unlikeTweet($user_id, $tweet_id);
        $action = 'unliked';
    } else {

        $tweet_model->likeTweet($user_id, $tweet_id);
        $action = 'liked';
    }


    $newLikeCount = $tweet_model->getLikeCount($tweet_id);

    echo json_encode([
        'status' => $action,
        'new_count' => $newLikeCount
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
