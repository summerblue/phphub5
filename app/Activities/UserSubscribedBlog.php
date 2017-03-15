<?php

namespace App\Activities;

use App\Models\Activity;

class UserSubscribedBlog extends BaseActivity
{
    public function generate($user, $blog)
    {
        $causer      = 'u' . $user->id;
        $indentifier = 'b' . $blog->id;
        $data = array_merge([
            'blog_name' => $blog->name,
            'blog_link' => $blog->link(),
        ]);

        $this->addActivity($causer, $user, $indentifier, $data);
    }

    public function remove($user, $blog)
    {
        $this->removeBy("u$user->id", "b$blog->id");
    }
}
