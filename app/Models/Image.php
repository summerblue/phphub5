<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    public $fillable = ['topic_id', 'link'];

    public static function fromActivities($activities)
    {
        $images = [];
        foreach ($activities as $activity) {
            if (strpos($activity->indentifier, 't') !== false) {
                $images[$activity->indentifier] = static::where('topic_id', str_replace('t', '', $activity->indentifier))->get();
            }
        }
        return $images;
    }
}
