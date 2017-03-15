<?php

namespace App\Activities;

use App\Models\Activity;

class UserAddedAppend extends BaseActivity
{
    public function generate($user, $topic, $append)
    {
        $this->addTopicActivity($user, $topic, [
            'body' => $append->content
        ]);
    }
}
