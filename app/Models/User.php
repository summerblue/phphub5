<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Zizaco\Entrust\Traits\EntrustUserTrait;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use GuzzleHttp\Client;
use Laracasts\Presenter\PresentableTrait;
use Venturecraft\Revisionable\RevisionableTrait;
use Smartisan\Follow\FollowTrait;

class User extends Model implements AuthenticatableContract,
                                    AuthorizableContract
{
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

    protected $table      = 'users';
    protected $guarded    = ['id', 'is_banned'];

    public static function boot()
    {
        parent::boot();

        static::created(function ($topic) {
            SiteStatus::newUser();
        });
    }

    /**
     * For EntrustUserTrait and SoftDeletes conflict
     */
    public function restore()
    {
        $this->restoreEntrust();
        $this->restoreSoftDelete();
    }

    public function favoriteTopics()
    {
        return $this->belongsToMany(Topic::class, 'favorites')->withTimestamps();
    }

    public function attentTopics()
    {
        return $this->belongsToMany(Topic::class, 'attentions')->withTimestamps();
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

    /**
     * ----------------------------------------
     * For Oauth
     * ----------------------------------------
     */
    public static function getByDriver($driver, $id)
    {
        $functionMap = [
            'github' => 'getByGithubId',
            'weixin' => 'getByWechatId'
        ];
        $function = $functionMap[$driver];
        if (!$function) {
            return null;
        }

        return self::$function($id);
    }

    public static function getByGithubId($id)
    {
        return User::where('github_id', '=', $id)->first();
    }

    public static function getByWechatId($id)
    {
        return User::where('wechat_id', '=', $id)->first();
    }

    public function getIntroductionAttribute($value)
    {
        return str_limit($value, 68);
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

    /**
     * ----------------------------------------
     * RemindableInterface
     * ----------------------------------------
     */

    public function getReminderEmail()
    {
        return $this->email;
    }

    public function getRememberToken()
    {
        return $this->remember_token;
    }

    public function setRememberToken($value)
    {
        $this->remember_token = $value;
    }

    public function getRememberTokenName()
    {
        return 'remember_token';
    }

    /**
     * Cache github avatar to local
     * @author Xuan
     */
    public function cacheAvatar()
    {
        //Download Image
        $guzzle = new Client();
        $response = $guzzle->get($this->image_url);
        //Get ext
        $content_type = explode('/', $response->getHeader('Content-Type')[0]);
        $ext = array_pop($content_type);

        $avatar_name = $this->id . '_' . time() . '.' . $ext;
        $save_path = public_path('uploads/avatars/') . $avatar_name;

        //Save File
        $content = $response->getBody()->getContents();
        file_put_contents($save_path, $content);

        //Delete old file
        if ($this->avatar) {
            @unlink(public_path('uploads/avatars/') . $this->avatar);
        }

        //Save to database
        $this->avatar = $avatar_name;
        $this->save();
    }
}
