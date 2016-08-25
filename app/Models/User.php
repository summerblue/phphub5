<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Zizaco\Entrust\Traits\EntrustUserTrait;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Laracasts\Presenter\PresentableTrait;
use Venturecraft\Revisionable\RevisionableTrait;
use Smartisan\Follow\FollowTrait;
use App\Jobs\SendActivateMail;
use App\Models\Traits\UserRememberTokenHelper;
use App\Models\Traits\UserSocialiteHelper;
use App\Models\Traits\UserAvatarHelper;
use Carbon\Carbon;
use Cache;

class User extends Model implements AuthenticatableContract,
                                    AuthorizableContract
{
    use UserRememberTokenHelper,UserSocialiteHelper,UserAvatarHelper;
    use PresentableTrait;
    public $presenter = 'Phphub\Presenters\UserPresenter';

    // For admin log
    use RevisionableTrait;
    protected $keepRevisionOf = [
        'is_banned'
    ];

    use EntrustUserTrait {
        restore as private restoreEntrust;
        EntrustUserTrait::can as may;
    }
    use SoftDeletes { restore as private restoreSoftDelete; }
    use FollowTrait;
    protected $dates = ['deleted_at'];

    protected $table   = 'users';
    protected $guarded = ['id', 'is_banned'];

    public static function boot()
    {
        parent::boot();

        static::created(function ($user) {
            $driver = $user['github_id'] ? 'github' : 'wechat';
            SiteStatus::newUser($driver);

            dispatch(new SendActivateMail($user));
        });

        static::deleted(function ($user) {
            \Artisan::call('phphub:clear-user-data', ['user_id' => $user->id]);
        });
    }

    public function scopeIsRole($query, $role)
    {
        return $query->whereHas('roles', function ($query) use ($role) {
                $query->where('name', $role);
            }
        );
    }
    public static function hallOfFamesUsers()
    {
        $data = Cache::remember('phphub_hall_of_fames', 60, function(){
            return User::isRole('HallOfFame')->orderBy('last_actived_at', 'desc')->get();
        });
        return $data;
    }

    /**
     * For EntrustUserTrait and SoftDeletes conflict
     */
    public function restore()
    {
        $this->restoreEntrust();
        $this->restoreSoftDelete();
    }

    public function votedTopics()
    {
        return $this->morphedByMany(Topic::class, 'votable', 'votes')->withPivot('created_at');
    }

    public function topics()
    {
        return $this->hasMany(Topic::class);
    }

    public function replies()
    {
        return $this->hasMany(Reply::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class)->recent()->with('topic', 'fromUser')->paginate(20);
    }

    public function scopeRecent($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    public function getIntroductionAttribute($value)
    {
        return str_limit($value, 68);
    }

    public function getPersonalWebsiteAttribute($value)
    {
        return str_replace(['https://', 'http://'], '', $value);
    }

    /**
     * ----------------------------------------
     * UserInterface
     * ----------------------------------------
     */

    public function getAuthIdentifier()
    {
        return $this->getKey();
    }

    public function getAuthPassword()
    {
        return $this->password;
    }

    public function recordLastActivedAt()
    {
        $now = Carbon::now()->toDateTimeString();

        $update_key = config('phphub.actived_time_for_update');
        $update_data = Cache::get($update_key);
        $update_data[$this->id] = $now;
        Cache::forever($update_key, $update_data);

        $show_key = config('phphub.actived_time_data');
        $show_data = Cache::get($show_key);
        $show_data[$this->id] = $now;
        Cache::forever($show_key, $show_data);
    }
}
