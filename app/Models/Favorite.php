<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    protected $fillable = [];

    public function post()
    {
        return $this->belongsTo('Post');
    }

    public function user()
    {
        return $this->belongsTo('User');
    }

    public static function isUserFavoritedTopic(User $user, Topic $topic)
    {
        return Favorite::where('user_id', $user->id)
                        ->where('topic_id', $topic->id)
                        ->first();
    }
}
