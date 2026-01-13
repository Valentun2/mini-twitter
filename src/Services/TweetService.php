<?php

namespace App\Services;

use App\Models\Like;
use App\Models\Tweet;
use PDO;

class TweetService
{

    public function __construct(
        private  Tweet $tweet,
        private Like $likes,
        private PDO $conn

    ) {}


    public function add($user_id, $text)
    {
        $tweet_id = $this->tweet->addTweet($user_id, $text);
        $stmt = $this->conn->prepare("
            SELECT tweets.*, users.name,
                (SELECT COUNT(*) FROM likes WHERE tweet_id = tweets.id) AS like_count
            FROM tweets
            JOIN users ON users.id = tweets.user_id
            WHERE tweets.id = :id
        ");

        $stmt->execute(['id' => $tweet_id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    public function delete($tweet_id, $user_id)
    {
        $this->tweet->deleteTweet($tweet_id, $user_id);
    }

    public function update($tweet_id, $user_id, $new_text)
    {
        $this->tweet->editTweet($tweet_id, $user_id, $new_text);
    }


    public function getAll(): array
    {
        $stmt = $this->conn->query("
        SELECT tweets.*, users.name, COUNT(likes.tweet_id) AS like_count
        FROM tweets
        LEFT JOIN users ON tweets.user_id = users.id
        LEFT JOIN likes ON tweets.id = likes.tweet_id
        GROUP BY tweets.id, users.name
        ORDER BY tweets.created_at
    ");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
