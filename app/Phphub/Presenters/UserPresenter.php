<?php namespace Phphub\Presenters;

use Laracasts\Presenter\Presenter;
use Route;
use App\Models\User;
use App\Models\Role;
use Cache;

class UserPresenter extends Presenter
{
    /**
     * Present a link to the user gravatar.
     */
    public function gravatar($size = 100)
    {
        if (config('app.url_static') && $this->avatar) {
            //Using Qiniu image processing service.
            $postfix = $size > 0 ? "?imageView2/1/w/{$size}/h/{$size}" : '';
            return cdn('uploads/avatars/'.$this->avatar) . $postfix;
        }

        $github_id = $this->github_id;
        $domainNumber = rand(0, 3);

        return "https://avatars{$domainNumber}.githubusercontent.com/u/{$github_id}?v=2&s={$size}";
    }

    public function loginQR($size = 80)
    {
        if (!$this->login_token) {
            $this->entity->login_token = str_random(20);
            $this->entity->save();
        }

        return \QrCode::format('png')
            ->size(200)
            ->errorCorrection('L')
            ->margin(0)
            ->generate($this->github_name . ',' . $this->login_token);
    }

    public function userinfoNavActive($anchor)
    {
        return Route::currentRouteName() == $anchor ? 'active' : '';
    }

    public function hasBadge()
    {
        $relations = Role::relationArrayWithCache();
        $user_ids = array_pluck($relations, 'user_id');
        return in_array($this->id, $user_ids);
    }

    public function badgeName()
    {
        $relations = Role::relationArrayWithCache();
        $relation = array_first($relations, function ($key, $value) {
            return $value->user_id == $this->id;
        });

        if (!$relation) {
            return;
        }

        $roles = Role::rolesArrayWithCache();

        $role = array_first($roles, function ($key, $value) use (&$relation) {
            return $value->id == $relation->role_id;
        });


        return $role->display_name;
    }

    public function followingUsersJson()
    {
        $users = \Auth::user()->followings()->lists('name');
        return json_encode($users);
    }

    public function lastActivedAt()
    {
        $show_key  = config('phphub.actived_time_data');
        $show_data = Cache::get($show_key);

        if (!isset($show_data[$this->id])) {
            $show_data[$this->id] = $this->last_actived_at;
            Cache::forever($show_key, $show_data);
        }

        return $show_data[$this->id];
    }
}
