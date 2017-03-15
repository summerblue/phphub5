<?php

namespace App\Activities;

use App\Models\Activity;

class UserAttendedTopic extends BaseActivity
{
    public function generate($user, $topic)
    {
        $this->addTopicActivity($user, $topic);
    }

    public function remove($user, $topic)
    {
        $this->removeBy("u$user->id", "t$topic->id");
    }
}
