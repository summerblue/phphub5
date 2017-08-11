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
use App\Activities\UserRepliedTopic;

class Topic extends Model
{
    use Traits\TopicFilterable;
    use Traits\TopicApiHelper;
    use Traits\TopicImageHelper;

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
            'topics.body'  => 5,
        ]
    ];

    use PresentableTrait;
    protected $presenter = 'Phphub\Presenters\TopicPresenter';

    use SoftDeletes;
    protected $dates = ['deleted_at'];

    // Don't forget to fill this array
    protected $fillable = [
        'title',
        'slug',
        'body',
        'excerpt',
        'is_draft',
        'source',
        'body_original',
        'user_id',
        'category_id',
        'created_at',
        'updated_at',
    ];

    public static function boot()
    {
        parent::boot();

        static::created(function ($topic) {
            SiteStatus::newTopic();
        });

        static::deleted(function ($topic) {
            foreach ($topic->replies as $reply) {
                app(UserRepliedTopic::class)->remove($reply->user, $reply);
            }
        });
    }

    public function share_link()
    {
        return $this->hasOne(ShareLink::class);
    }

    public function votes()
    {
        return $this->morphMany(Vote::class, 'votable');
    }

    public function attentedUsers()
    {
        return $this->belongsToMany(User::class, 'attentions')->get();
    }

    public function votedUsers()
    {
        $user_ids = Vote::where('votable_type', Topic::class)
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

    public function blogs()
    {
        return $this->belongsToMany(Blog::class, 'blog_topics');
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

    public function getRepliesWithLimit($limit = 30, $order = 'created_at')
    {
        $pageName = 'page';
        // Default display the latest reply
        $latest_page = is_null(\Input::get($pageName)) ? ceil($this->reply_count / $limit) : 1;
        $query = $this->replies()->with('user');

        $query = ($order == 'vote_count') ? $query->orderBy('vote_count', 'desc') : $query->orderBy('created_at', 'asc');

        return $query->paginate($limit, ['*'], $pageName, $latest_page);
    }

    public function getSameCategoryTopics()
    {
        $data = Cache::remember('phphub_hot_topics_' . $this->category_id, 30, function () {
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
        $data = Cache::remember('phphub_random_topics', 10, function () {
            $topic = new Topic;
            return $topic->getTopicsWithFilter('random-excellent', 5);
        });
        return $data;
    }

    public function isArticle()
    {
        return $this->category_id == config('phphub.blog_category_id');
    }

    public function isShareLink()
    {
        return $this->category_id == config('phphub.hunt_category_id');
    }

    public function link($params = [])
    {
        $params = array_merge([$this->id, $this->slug], $params);
        $name = $this->isArticle() ? 'articles.show' : 'topics.show';
        return str_replace(env('API_DOMAIN'), env('APP_DOMAIN'), route($name, $params));
    }
}
