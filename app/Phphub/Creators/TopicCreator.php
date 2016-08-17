<?php namespace Phphub\Creators;

use Phphub\Core\CreatorListener;
use Phphub\Core\Robot;
use App\Models\Topic;
use Auth;
use Carbon\Carbon;
use Phphub\Markdown\Markdown;

class TopicCreator
{

    public function create(CreatorListener $observer, $data)
    {
        $data['user_id'] = Auth::id();
        $data['created_at'] = Carbon::now()->toDateTimeString();
        $data['updated_at'] = Carbon::now()->toDateTimeString();

        $markdown = new Markdown;
        $data['body_original'] = $data['body'];
        $data['body'] = $markdown->convertMarkdownToHtml($data['body']);
        $data['excerpt'] = Topic::makeExcerpt($data['body']);

        $data['source'] = getPlatform();

        $topic = Topic::create($data);
        if (! $topic) {
            return $observer->creatorFailed($topic->getErrors());
        }

        Auth::user()->increment('topic_count', 1);

        return $observer->creatorSucceed($topic);
    }
}
