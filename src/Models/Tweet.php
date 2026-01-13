<?php

namespace App\Models;

use PDO;

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
        $stmt->execute();
        return   (int)$this->conn->lastInsertId();

        //             $query2 = "SELECT tweets.*, users.name,

        //     (
        //         SELECT COUNT(*) 
        //         FROM likes 
        //         WHERE likes.tweet_id = tweets.id
        //     ) AS like_count
        // FROM tweets
        // JOIN users ON users.id = tweets.user_id
        // WHERE tweets.id = :id;
        // ";

        //             $stmt2 = $this->conn->prepare($query2);
        //             $stmt2->bindParam(':id', $tweet_id);
        //             $stmt2->execute();

        //             return $stmt2->fetch(PDO::FETCH_ASSOC);

        //         return false;
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

    // public function  allTweets()
    // {
    //     $query = "
    //         SELECT
    //             tweets.*, 
    //             users.name,                 
    //             COUNT(likes.tweet_id) AS like_count
    //         FROM
    //             tweets
    //          LEFT JOIN
    //              users ON tweets.user_id = users.id
    //         LEFT JOIN
    //             likes ON tweets.id = likes.tweet_id
    //         GROUP BY
    //             tweets.id,
    //             users.name
    //         ORDER BY
    //             tweets.created_at 
    //     ";
    //     $stmt = $this->conn->prepare($query);

    //     $stmt->execute();

    //     $tweets = $stmt->fetchAll(PDO::FETCH_ASSOC);

    //     return $tweets;
    // }
}
