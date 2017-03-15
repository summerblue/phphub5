<?php

namespace App\Activities;

use App\Models\Activity;

class UserFollowedUser extends BaseActivity
{
    public function generate($user, $following)
    {
        $causer      = 'u' . $user->id;
        $indentifier = 'u' . $following->id;
        $data = array_merge([
            'following_name' => $following->name,
            'following_link' => route('users.show', $following->id),
        ]);

        $this->addActivity($causer, $user, $indentifier, $data);
    }

    public function remove($user, $following)
    {
        $this->removeBy("u$user->id", "u$following->id");
    }
}
