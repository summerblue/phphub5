<?php

namespace App\Models\Traits;

use App\Models\Activity;

trait UserActivityHelper
{
    public function activities()
    {
        return $this->activitiesByCausers(['u' . $this->id]);
    }

    public function subscribedActivityFeeds()
    {
        $causers = $this->interestedCausers();
        return $this->activitiesByCausers($causers);
    }

    public function interestedCausers()
    {
        $followings = $this->followings->map(function ($user, $key) {
            return 'u' . $user->id;
        })->toArray();

        $subscribed_blogs = $this->subscribes->map(function ($blog, $key) {
            return 'b' . $blog->id;
        })->toArray();

        return array_merge(['u' . $this->id], $followings, $subscribed_blogs);
    }

    public function activitiesByCausers($causers)
    {
        return Activity::whereIn('causer', $causers)
                    ->recent()
                    ->paginate(50);
    }
}
