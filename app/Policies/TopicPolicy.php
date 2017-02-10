<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\User;
use App\Models\Topic;

class TopicPolicy
{
    use HandlesAuthorization;

    public function show_draft(User $user, Topic $topic)
    {
        return $user->may('manage_topics') || $topic->user_id == $user->id;
    }

    public function update(User $user, Topic $topic)
    {
        return $user->may('manage_topics') || $topic->user_id == $user->id;
    }

    public function delete(User $user, Topic $topic)
    {
        return $user->may('manage_topics') || $topic->user_id == $user->id;
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
