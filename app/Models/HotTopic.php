<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cache;

class HotTopic extends Model
{
    protected $guarded = ['id'];

    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }

    public static function fetchAll()
    {
        $data = Cache::remember('phphub_hot_topics', 30, function(){
            return self::orderBy('weight', 'DESC')
                             ->with('topic','topic.user')
                             ->limit(10)
                             ->get()
                             ->pluck('topic');
        });

        return $data;
    }
}
