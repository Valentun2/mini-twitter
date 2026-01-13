<?php

namespace App\Models;

use PDO;
use PDOException;

class Like
{
    private string $table = "like";
    public function __construct(private PDO $conn) {}

    public function add($user_id, $tweet_id)
    {
        $query = "INSERT INTO likes (user_id, tweet_id) VALUES (:user_id, :tweet_id)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':tweet_id', $tweet_id);


        try {
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }


    public function remove($user_id, $tweet_id)
    {
        $query = "DELETE FROM likes WHERE user_id = :user_id AND tweet_id = :tweet_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':tweet_id', $tweet_id);
        $stmt->execute();

        return $stmt->rowCount() > 0;
    }

    public function isLikedByUser($user_id, $tweet_id)
    {
        $query = "SELECT 1 FROM likes WHERE user_id = :user_id AND tweet_id = :tweet_id LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':tweet_id', $tweet_id);
        $stmt->execute();

        return $stmt->fetchColumn();
    }

    public function getLikeCount($tweet_id)
    {
        $query = "SELECT COUNT(*) FROM likes WHERE tweet_id = :tweet_id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':tweet_id', $tweet_id);
        $stmt->execute();

        $count = $stmt->fetchColumn();

        return (int)$count;
    }

    public function likedTweetIdsByUser($user_id)
    {
        $query = "SELECT tweet_id FROM likes WHERE user_id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $user_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
}
