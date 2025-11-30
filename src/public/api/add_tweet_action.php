<?php
session_start();
header('Content-Type: application/json');

require_once __DIR__ . '/../../vendor/autoload.php';

use App\Core\Database;
use App\Models\Tweet;

$database = new Database();
$db = $database->getConnection();
$tweet = new Tweet($db);


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $text = $_POST['text'];
    try {
        $newTweet = $tweet->addTweet($user_id, $text);

        echo json_encode([
            "status" => "success",
            "message" => "Tweet updated",
            "newTweet" => $newTweet
        ]);
        exit;
    } catch (PDOException $e) {
        echo json_encode([
            "error" => $e
        ]);
    }
}
