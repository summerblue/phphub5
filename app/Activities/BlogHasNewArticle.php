<?php

namespace App\Activities;

use App\Models\Activity;

class BlogHasNewArticle extends BaseActivity
{
    public function generate($user, $topic, $blog)
    {
        $this->addTopicActivity($user, $topic, [
            'blog_link' => $blog->link(),
            'blog_name' => $blog->name,
            'blog_cover' => $blog->cover,
        ]);
    }

    public function remove($user, $topic)
    {
        $this->removeBy("u$user->id", "t$topic->id");
    }
}
