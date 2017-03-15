<?php

namespace App\Activities;

use App\Models\Activity;

class UserFollowedUser extends BaseActivity
{
    public function generate($user, $topic)
    {
        $this->addActivity($user, $topic);
    }

    public function remove($user, $topic)
    {
        $this->removeBy("u$user->id", "t$topic->id");
    }
}
