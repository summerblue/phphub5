<?php

namespace App\Models;

use Cmgmyr\Messenger\Models\Thread as MessengerThread;
use Auth;

class Thread extends MessengerThread
{
    public function participant()
    {
        return $this->participants()->where('user_id', '!=', Auth::id())->first()->user;
    }

    public static function participateBy($user_id)
    {
        $user_id = Auth::id();
        $thread_ids = array_unique(Participant::byWhom($user_id)->lists('thread_id')->toArray());

        return Thread::whereIn('id', $thread_ids)->orderBy('updated_at', 'desc')->paginate(15);
    }

    public function scopeRecent($query)
    {
        return $query->orderBy('created_at', 'desc');
    }
}
