<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laracasts\Presenter\PresentableTrait;
use Naux\AutoCorrect;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use Illuminate\Pagination\Paginator;
use Venturecraft\Revisionable\RevisionableTrait;

class Topic extends Model
{
    // manually maintian
    public $timestamps = false;

    // For admin log
    use RevisionableTrait;
    protected $keepRevisionOf = [
        'deleted_at',
        'is_excellent',
        'is_blocked',
        'order',
    ];

    use PresentableTrait;
    protected $presenter = 'Phphub\Presenters\TopicPresenter';

    use SoftDeletes;
    protected $dates = ['deleted_at'];

    // Don't forget to fill this array
    protected $fillable = [
        'title',
        'body',
        'excerpt',
        'body_original',
        'user_id',
        'category_id',
        'created_at',
        'updated_at'
    ];

    public static function boot()
    {
        parent::boot();

        static::created(function ($topic) {
            SiteStatus::newTopic();
        });
    }

    public function votes()
    {
        return $this->morphMany(Vote::class, 'votable');
    }

    public function favoritedBy()
    {
        return $this->belongsToMany(User::class, 'favorites');
    }

    public function attentedBy()
    {
        return $this->belongsToMany(User::class, 'attentions');
    }

    public function Category()
    {
        return $this->belongsTo(Category::class);
    }

    public function Tag()
    {
        return $this->hasMany(Tag::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function lastReplyUser()
    {
        return $this->belongsTo(User::class, 'last_reply_user_id');
    }

    public function replies()
    {
        return $this->hasMany(Reply::class);
    }

    public function appends()
    {
        return $this->hasMany(Append::class);
    }

    public function getWikiList()
    {
        return $this->where('is_wiki', '=', 'yes')->orderBy('created_at', 'desc')->get();
    }

    public function generateLastReplyUserInfo()
    {
        $lastReply = $this->replies()->recent()->first();

        $this->last_reply_user_id = $lastReply ? $lastReply->user_id : 0;
        $this->save();
    }

    public function getRepliesWithLimit($limit = 30)
    {
        $pageName = 'page';

        // Default display the latest reply
        $latest_page = is_null(\Input::get($pageName)) ? ceil($this->reply_count / $limit) : 1;

        return $this->replies()
                    ->orderBy('created_at', 'asc')
                    ->with('user')
                    ->paginate($limit, ['*'], $pageName, $latest_page);
    }

    public function getTopicsWithFilter($filter, $limit = 20)
    {
        return $this->applyFilter($filter)
                    ->with('user', 'category', 'lastReplyUser')
                    ->paginate($limit);
    }

    public function getCategoryTopicsWithFilter($filter, $category_id, $limit = 20)
    {
        return $this->applyFilter($filter == 'default' ? 'category' : $filter)
                    ->where('category_id', '=', $category_id)
                    ->with('user', 'category', 'lastReplyUser')
                    ->paginate($limit);
    }

    public function applyFilter($filter)
    {
        switch ($filter) {
            case 'noreply':
                return $this->orderBy('reply_count', 'asc')->recent();
                break;
            case 'vote':
                return $this->orderBy('vote_count', 'desc')->recent();
                break;
            case 'excellent':
                return $this->excellent()->recent();
                break;
            case 'recent':
                return $this->recent();
                break;
            case 'category':
                return $this->recentReply();
                break;
            default:
                return $this->pinAndRecentReply();
                break;
        }
    }

    public function getSameCategoryTopics($limit = 8)
    {
        return Topic::where('category_id', '=', $this->category_id)
                        ->recent()
                        ->take($limit)
                        ->get();
    }

    public function scopeWhose($query, $user_id)
    {
        return $query->where('user_id', '=', $user_id)->with('category');
    }

    public function scopeRecent($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    public function scopePinAndRecentReply($query)
    {
        return $query->whereRaw("(`created_at` > '".Carbon::today()->subMonth()->toDateString()."' or (`order` > 0) )")
                     ->orderBy('order', 'desc')
                     ->orderBy('updated_at', 'desc');
    }

    public function scopeRecentReply($query)
    {
        return $query->orderBy('order', 'desc')
                     ->orderBy('updated_at', 'desc');
    }

    public function scopeExcellent($query)
    {
        return $query->where('is_excellent', '=', 'yes');
    }

    public static function makeExcerpt($body)
    {
        $html = $body;
        $excerpt = trim(preg_replace('/\s\s+/', ' ', strip_tags($html)));
        return str_limit($excerpt, 200);
    }

    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = (new AutoCorrect)->convert($value);
    }
}
