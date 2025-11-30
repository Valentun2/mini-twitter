<?php
session_start();
header('Content-Type: application/json');

require_once __DIR__ . '/../../vendor/autoload.php';


use App\Core\Database;
use App\Models\Tweet;

$database = new Database();



try {
    $database = new Database();
    $db = $database->getConnection();

    $tweet_model = new Tweet($db);

    $tweets = $tweet_model->allTweets();
    echo json_encode([
        "status" => 'succes',
        "tweets" => $tweets
    ]);
} catch (Exception $e) {

    echo "Помилка завантаження даних: " . $e->getMessage();
}
