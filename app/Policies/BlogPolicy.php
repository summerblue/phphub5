<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

use App\Models\User;
use App\Models\Blog;

class BlogPolicy
{
    use HandlesAuthorization;

    public function update(User $user, Blog $blog)
    {
        return $user->id === $blog->user_id;
    }
}
