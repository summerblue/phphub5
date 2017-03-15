<?php

namespace App\Activities;

use App\Models\Activity;

class BlogHasNewArticle extends BaseActivity
{
    public function generate($user, $topic, $blog)
    {
        $this->addActivity($user, $topic, [
            'blog_link' => $blog->link(),
            'blog_name' => $blog->name,
        ]);
    }

    public function remove($user, $topic)
    {
        $this->removeBy("u$user->id", "t$topic->id");
    }
}
