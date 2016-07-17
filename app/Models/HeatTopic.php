<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HeatTopic extends Model
{
    protected $guarded = ['id'];

    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }

    public static function fetchAll($limit = 8)
    {
        return self::with('topic')
                   ->orderBy('weight', 'DESC')
                   ->limit($limit)
                   ->get()
                   ->pluck('topic');
    }
}
