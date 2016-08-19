<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\User;
use App\Models\Topic;

class TopicPolicy
{
    use HandlesAuthorization;

    public function update(User $user, Topic $topic)
    {
        return $user->may('manage_topics') || $topic->user_id == $user->id;
    }

    public function delete(User $user, Topic $topic)
    {
        // 不支持用户删帖
        // return $user->id === $topic->user_id;
        return $user->may('manage_topics');
    }

    public function recommend(User $user, Topic $topic)
    {
        return $user->may('manage_topics');
    }

    public function wiki(User $user, Topic $topic)
    {
        return $user->may('manage_topics');
    }

    public function pin(User $user, Topic $topic)
    {
        return $user->may('manage_topics');
    }

    public function sink(User $user, Topic $topic)
    {
        return $user->may('manage_topics');
    }

    public function append(User $user, Topic $topic)
    {
        return $user->may('manage_topics') || $topic->user_id == $user->id;
    }
}
