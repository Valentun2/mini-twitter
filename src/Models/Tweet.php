<?php

namespace App\Models;

use PDO;
use PDOException;

class Tweet
{
    private $conn;
    private $table_name = "tweets";
    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function addTweet($user_id, $text)
    {
        $query = "INSERT INTO " . $this->table_name . " (user_id, text)
                  VALUES (:user_id, :text)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':text', $text);
        if ($stmt->execute()) {
            $tweet_id = $this->conn->lastInsertId();

            $query2 = "SELECT tweets.*, users.name,

    (
        SELECT COUNT(*) 
        FROM likes 
        WHERE likes.tweet_id = tweets.id
    ) AS like_count
FROM tweets
JOIN users ON users.id = tweets.user_id
WHERE tweets.id = :id;
";

            $stmt2 = $this->conn->prepare($query2);
            $stmt2->bindParam(':id', $tweet_id);
            $stmt2->execute();

            return $stmt2->fetch(PDO::FETCH_ASSOC);
        }
        return false;
    }

    public function deleteTweet($tweet_id, $user_id)
    {
        $query = "DELETE FROM " . $this->table_name . " WHERE id=:tweet_id AND user_id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':tweet_id', $tweet_id);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();

        return $stmt->rowCount() > 0;
    }

    public function editTweet($tweet_id, $user_id, $new_text)
    {
        $query = "UPDATE " . $this->table_name . "
              SET
                text = :new_text
              WHERE
                id = :tweet_id
                AND user_id = :user_id";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':tweet_id', $tweet_id);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':new_text', $new_text);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    public function  allTweets()
    {
        $query = "
            SELECT
                tweets.*, 
                users.name,                 
                COUNT(likes.tweet_id) AS like_count
            FROM
                tweets
             LEFT JOIN
                 users ON tweets.user_id = users.id
            LEFT JOIN
                likes ON tweets.id = likes.tweet_id
            GROUP BY
                tweets.id,
                users.name
            ORDER BY
                tweets.created_at DESC
        ";
        $stmt = $this->conn->prepare($query);

        // 3. Виконуємо запит
        $stmt->execute();

        // 4. Отримуємо ВСІ рядки у вигляді масиву
        $tweets = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // 5. Повертаємо масив твітів
        return $tweets;
    }

    public function likeTweet($user_id, $tweet_id)
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

    /**
     * Знімає лайк (видаляє рядок)
     */
    public function unlikeTweet($user_id, $tweet_id)
    {
        $query = "DELETE FROM likes WHERE user_id = :user_id AND tweet_id = :tweet_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':tweet_id', $tweet_id);
        $stmt->execute();

        return $stmt->rowCount() > 0; // Поверне true, якщо щось видалило
    }

    /**
     * Перевіряє, чи лайкнув користувач цей твіт
     */
    public function isLikedByUser($user_id, $tweet_id)
    {
        $query = "SELECT 1 FROM likes WHERE user_id = :user_id AND tweet_id = :tweet_id LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':tweet_id', $tweet_id);
        $stmt->execute();

        // fetchColumn() поверне "1" (true) якщо знайшов, або false (якщо ні)
        return $stmt->fetchColumn();
    }

    public function getLikeCount($tweet_id)
    {
        $query = "SELECT COUNT(*) FROM likes WHERE tweet_id = :tweet_id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':tweet_id', $tweet_id);
        $stmt->execute();

        // fetchColumn() повертає лише одне значення (саме число)
        $count = $stmt->fetchColumn();

        return (int)$count; // Повертаємо як число (напр., 15)
    }
}
