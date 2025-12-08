<?php
session_start();
header('Content-Type: application/json');

require_once __DIR__ . '/../../vendor/autoload.php';


use App\Core\Database;
use App\Models\Tweet;

$database = new Database();

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(["status" => "error", "message" => "Not authenticated"]);
    exit;
}

$user_id = $_SESSION['user_id'];


try {



    $database = new Database();
    $db = $database->getConnection();

    $tweet_model = new Tweet($db);
    $tweets = $tweet_model->allTweets();
    $liked_ids = $tweet_model->likedTweetIdsByUser($user_id);


    $liked_map = array_flip($liked_ids);

    foreach ($tweets as &$tweet) {
        $tweet['is_liked'] = isset($liked_map[$tweet['id']]);
        $tweet['created_at'] = date('c', strtotime($tweet['created_at']));
    }



    echo json_encode([
        "status" => 'success',
        "tweets" => $tweets,
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
