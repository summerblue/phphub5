<?php

namespace App\Activities;

use App\Models\Activity;

class UserRepliedTopic extends BaseActivity
{
    public function generate($user, $topic, $reply)
    {
        $this->addActivity($user, $topic, [
            'body' => $reply->body
        ], "r$reply->id");
    }

    public function remove($user, $reply)
    {
        $this->removeBy("u$user->id", "r$reply->id");
    }
}
