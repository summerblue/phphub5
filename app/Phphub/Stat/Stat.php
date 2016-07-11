<?php namespace Phphub\Stat;

use App\Models\Topic;
use App\Models\Reply;
use App\Models\User;
use Cache;

class Stat
{
    const CACHE_KEY     = 'site_stat';
    const CACHE_MINUTES = 10;

    public function getSiteStat()
    {
        return Cache::remember(self::CACHE_KEY, self::CACHE_MINUTES, function () {
            $entity = new StatEntity();
            $entity->topic_count = Topic::count();
            $entity->reply_count = Reply::count();
            $entity->user_count  = User::count();
            return $entity;
        });
    }
}
