<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HotTopic extends Model
{
    protected $guarded = ['id'];

    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }

    public static function fetchAll($limit = 8)
    {
        $topic_ids = self::orderBy('weight', 'DESC')
                         ->limit($limit)
                         ->lists('topic_id')
                         ->toArray();
        return Topic::whereIn('id', $topic_ids)->with('user')->get();
    }
}
