<?php

namespace App\Activities;

use App\Models\Activity;

class UserRepliedTopic extends BaseActivity
{
    public function generate($user, $topic, $reply)
    {
        $this->addTopicActivity($user, $topic, [
            'body' => $reply->body,
            'reply_id' => $reply->id,
        ], "r$reply->id");
    }

    public function remove($user, $reply)
    {
        $this->removeBy("u$user->id", "r$reply->id");
    }
}
