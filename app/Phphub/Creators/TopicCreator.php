<?php namespace Phphub\Creators;

use Phphub\Core\CreatorListener;
use Phphub\Core\Robot;
use App\Models\Topic;
use App\Models\ShareLink;
use Phphub\Notification\Mention;
use Auth;
use Carbon\Carbon;
use Phphub\Markdown\Markdown;
use Illuminate\Support\MessageBag;
use App\Activities\UserPublishedNewTopic;
use App\Activities\BlogHasNewArticle;

class TopicCreator
{
    protected $mentionParser;

    public function __construct(Mention $mentionParser)
    {
        $this->mentionParser = $mentionParser;
    }

    public function create(CreatorListener $observer, $data, $blog = null)
    {
        // 检查是否重复发布
        if ($this->isDuplicate($data)) {
            return $observer->creatorFailed('请不要发布重复内容。');
        }

        $data['user_id'] = Auth::id();
        $data['created_at'] = Carbon::now()->toDateTimeString();
        $data['updated_at'] = Carbon::now()->toDateTimeString();

        // @ user
        $data['body'] = $this->mentionParser->parse($data['body']);

        if ($data['category_id'] == config('phphub.hunt_category_id')) {
            $data['body'] = '分享链接：' . $data['link'] . "\n" . $data['body'];
        }
        $markdown = new Markdown;
        $data['body_original'] = $data['body'];
        $data['body'] = $markdown->convertMarkdownToHtml($data['body']);
        $data['excerpt'] = Topic::makeExcerpt($data['body']);

        $data['source'] = get_platform();

        $data['slug'] = slug_trans($data['title']);

        $topic = Topic::create($data);
        if (! $topic) {
            return $observer->creatorFailed($topic->getErrors());
        }

        if ($blog) {
            $blog->topics()->attach($topic->id);
            // Co-authors
            if ( ! $blog->authors()->where('user_id', $topic->user_id)->exists()) {
                $blog->authors()->attach($topic->user_id);
            }
        }

        if ($topic->is_draft != 'yes' && $topic->category_id != config('phphub.admin_board_cid')) {
            app('Phphub\Notification\Notifier')->newTopicNotify(Auth::user(), $this->mentionParser, $topic);
            app(UserPublishedNewTopic::class)->generate(Auth::user(), $topic);
        }

        if ($topic->isArticle() && $topic->is_draft == 'yes') {
            Auth::user()->increment('draft_count', 1);
        } elseif ($topic->isArticle()) {
            Auth::user()->increment('article_count', 1);
            $blog->increment('article_count', 1);
            app(BlogHasNewArticle::class)->generate(Auth::user(), $topic, $topic->blogs()->first());
        } elseif ($topic->isShareLink()) {
            ShareLink::create([
                'topic_id' => $topic->id,
                'link' => $data['link'],
                'site' => domain_from_url($data['link']),
            ]);
        } else {
            Auth::user()->increment('topic_count', 1);
        }

        $topic->collectImages();

        return $observer->creatorSucceed($topic);
    }

    public function isDuplicate($data)
    {
        $last_topic = Topic::where('user_id', Auth::id())
                            ->orderBy('id', 'desc')
                            ->first();
        return count($last_topic) && strcmp($last_topic->title, $data['title']) === 0;
    }
}
