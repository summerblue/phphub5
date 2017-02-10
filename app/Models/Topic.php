<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laracasts\Presenter\PresentableTrait;
use Naux\AutoCorrect;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use Illuminate\Pagination\Paginator;
use Venturecraft\Revisionable\RevisionableTrait;
use Cache;
use Nicolaslopezj\Searchable\SearchableTrait;

class Topic extends Model
{
    use Traits\TopicFilterable;
    use Traits\TopicApiHelper;

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

    use SearchableTrait;
    protected $searchable = [
        'columns' => [
            'topics.title' => 10,
            'topics.body' => 5,
        ]
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
        'is_draft',
        'source',
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

    public function voteby()
    {
        $user_ids = Vote::where('votable_type', 'Topic')
                        ->where('votable_id', $this->id)
                        ->where('is', 'upvote')
                        ->lists('user_id')
                        ->toArray();
        return User::whereIn('id', $user_ids)->get();
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

    public function getSameCategoryTopics()
    {
        $data = Cache::remember('phphub_hot_topics', 30, function(){
            return Topic::where('category_id', '=', $this->category_id)
                            ->recent()
                            ->with('user')
                            ->take(3)
                            ->get();
        });
        return $data;
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

    public function scopeByWhom($query, $user_id)
    {
        return $query->where('user_id', '=', $user_id);
    }

    public function scopeDraft($query)
    {
        return $query->where('is_draft', '=', 'yes');
    }

    public function scopeWithoutDraft($query)
    {
        return $query->where('is_draft', '=', 'no');
    }

    public function scopeRecent($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    public function getRandomExcellent()
    {
        $data = Cache::remember('phphub_random_topics', 10, function(){
            $topic = new Topic;
            return $topic->getTopicsWithFilter('random-excellent', 5);
        });
        return $data;
    }

    public function isArticle()
    {
        return $this->category->id == config('phphub.blog_category_id');
    }
}
