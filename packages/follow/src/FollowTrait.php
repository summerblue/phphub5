<?php
namespace Smartisan\Follow;

use Illuminate\Database\Eloquent\ModelNotFoundException;

trait FollowTrait
{
    /**
     * Follow a user.
     *
     * @param int|array $user
     */
    public function follow($user)
    {
        if (!is_array($user)) {
            $user = compact('user');
        }

        $this->followings()->sync($user, false);
    }

    /**
     * Unfollow a user.
     *
     * @param $user
     */
    public function unfollow($user)
    {
        if (!is_array($user)) {
            $user = compact('user');
        }

        $this->followings()->detach($user);
    }
    
    /**
     * Check if user is following given user.
     *
     * @param $user
     * @return mixed
     */
    public function isFollowing($user)
    {
        return $this->followings->contains($user);
    }

    /**
     * Check if user is followed by given user.
     *
     * @param $user
     * @return mixed
     */
    public function isFollowedBy($user)
    {
        return $this->followers->contains($user);
    }

    /**
     * Followers relationship.
     *
     * @return mixed
     */
    public function followers()
    {
        $model = get_class($this);

        return $this->belongsToMany($model, 'followers', 'follow_id', 'user_id');
    }

    /**
     * Followings relationship.
     *
     * @return mixed
     */
    public function followings()
    {
        $model = get_class($this);

        return $this->belongsToMany($model, 'followers', 'user_id', 'follow_id');
    }
}