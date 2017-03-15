<?php

namespace App\Activities;

use App\Models\Activity;

class UserUpvotedReply extends BaseActivity
{
    public function generate($user, $reply)
    {
        $this->addTopicActivity($user, $reply->topic, [
            'body' => $reply->body,
            'reply_id' => $reply->id,
        ], "r$reply->id");
    }

    public function remove($user, $reply)
    {
        $this->removeBy("u$user->id", "r$reply->id");
    }
}
