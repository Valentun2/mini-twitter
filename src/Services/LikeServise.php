<?php

namespace App\Services;

use App\Models\Like;

class LikeServise
{

    public  function __construct(
        private Like $like
    ) {}

    public function toggle($user_id, $tweet_id)
    {
        if ($this->like->isLikedByUser($user_id, $tweet_id)) {

            $this->like->remove($user_id, $tweet_id);
            return false;
        } else {

            $this->like->add($user_id, $tweet_id);
            return true;
        }
    }


    public function isLikedByUser($user_id, $tweet_id)
    {
        return $this->like->isLikedByUser($user_id, $tweet_id);
    }


    public function getLikeCount($tweet_id)
    {
        return $this->like->getLikeCount($tweet_id);
    }

    public function likedTweetIdsByUser($user_id)
    {
        return $this->like->likedTweetIdsByUser($user_id);
    }
}
