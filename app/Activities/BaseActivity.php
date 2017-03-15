<?php

namespace App\Activities;

use App\Models\Activity;
use App\Models\User;
use App\Models\Topic;
use Carbon\Carbon;

class BaseActivity
{
    public function removeBy($causer, $indentifier)
    {
        Activity::where('causer', $causer)
                ->where('indentifier', $indentifier)
                ->where('type', class_basename(get_class($this)))
                ->delete();
    }

    public function addActivity(User $user, Topic $topic, $extra_data = [])
    {
        $type = class_basename(get_class($this));

        $activities[] = [
            'causer'   => 'u' . $user->id,
            'user_id'     => $user->id,
            'type'        => $type,
            'indentifier' => 't' . $topic->id,
            'data'        => serialize(array_merge([
                'topic_type' => $topic->isArticle() ? 'article' : 'topic',
                'topic_link' => $topic->link(),
                'topic_title' => $topic->title,
                'topic_category_id' => $topic->category->id,
                'topic_category_name' => $topic->category->name,
            ], $extra_data)),

            'created_at' => Carbon::now()->toDateTimeString(),
            'updated_at' => Carbon::now()->toDateTimeString(),
        ];

        Activity::insert($activities);

        // 标签下有新文章
        // if ($type == 'user_published_item') {
        //     $tags = $topic->tags()->get();
        //     foreach ($tags as $tag) {
        //         $activities[] = [
        //             'causer' => 't' . $tag->id,
        //             'user_id'   => $user->id,
        //             'type'      => 'tag_has_new_item',
        //             'indentifier' => 'i' . $topic->id,
        //             'data'      => serialize([
        //                 'topic_link' => $topic->link(),
        //                 'topic_title' => $topic->title,
        //                 'topic_tags' => static::itemTags($topic->tags()->get()),
        //                 'tag_name'  => $tag->name,
        //                 'tag_cover' => $tag->cover,
        //                 'tag_link'  => $tag->link(),
        //             ]),

        //             'created_at' => Carbon::now()->toDateTimeString(),
        //             'updated_at' => Carbon::now()->toDateTimeString(),
        //         ];
        //     }
        // }


    }
}
