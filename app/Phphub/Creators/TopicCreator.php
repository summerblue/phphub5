<?php namespace Phphub\Creators;

use Phphub\Core\CreatorListener;
use Phphub\Core\Robot;
use App\Models\Topic;
use Auth;
use Carbon\Carbon;
use Phphub\Markdown\Markdown;
use Illuminate\Support\MessageBag;

class TopicCreator
{

    public function create(CreatorListener $observer, $data)
    {
        // 检查是否重复发布
        if ($this->isDuplicate($data)) {
            $errorMessages = new MessageBag;
            $errorMessages->add('duplicated', '请不要发布重复内容。');
            return $observer->creatorFailed($errorMessages);
        }

        $data['user_id'] = Auth::id();
        $data['created_at'] = Carbon::now()->toDateTimeString();
        $data['updated_at'] = Carbon::now()->toDateTimeString();

        $markdown = new Markdown;
        $data['body_original'] = $data['body'];
        $data['body'] = $markdown->convertMarkdownToHtml($data['body']);
        $data['excerpt'] = Topic::makeExcerpt($data['body']);

        $data['source'] = get_platform();

        $topic = Topic::create($data);
        if (! $topic) {
            return $observer->creatorFailed($topic->getErrors());
        }

        if ($topic->isArticle() && $topic->is_draft == 'yes') {
            Auth::user()->increment('draft_count', 1);
        } elseif ($topic->isArticle() && $topic->is_draft == 'no') {
            Auth::user()->increment('article_count', 1);
        } else {
            Auth::user()->increment('topic_count', 1);
        }

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
