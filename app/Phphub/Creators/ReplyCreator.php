<?php namespace Phphub\Creators;

use Phphub\Core\CreatorListener;
use Phphub\Core\Robot;
use Phphub\Notification\Mention;
use App\Models\Reply;
use Auth;
use App\Models\Topic;
use App\Models\Notification;
use Carbon\Carbon;
use App;
use Phphub\Markdown\Markdown;
use App\Jobs\SendReplyNotifyMail;
use Illuminate\Support\MessageBag;
use App\Activities\UserRepliedTopic;

class ReplyCreator
{
    protected $mentionParser;

    public function __construct(Mention $mentionParser)
    {
        $this->mentionParser = $mentionParser;
    }

    public function create(CreatorListener $observer, $data)
    {
        // 检查是否重复发布评论
        if ($this->isDuplicateReply($data)) {
            $errorMessages = new MessageBag;
            $errorMessages->add('duplicated', '请不要发布重复内容。');
            return $observer->creatorFailed($errorMessages);
        }

        $data['user_id'] = Auth::id();
        $data['body'] = $this->mentionParser->parse($data['body']);

        $markdown = new Markdown;
        $data['body_original'] = $data['body'];
        $data['body'] = $markdown->convertMarkdownToHtml($data['body']);

        $data['source'] = get_platform();

        $reply = Reply::create($data);
        if (! $reply) {
            return $observer->creatorFailed($reply->getErrors());
        }

        // Add the reply user
        $topic = Topic::find($data['topic_id']);
        $topic->last_reply_user_id = Auth::id();
        $topic->reply_count++;
        $topic->updated_at = Carbon::now()->toDateTimeString();
        $topic->save();

        Auth::user()->increment('reply_count', 1);

        app('Phphub\Notification\Notifier')->newReplyNotify(Auth::user(), $this->mentionParser, $topic, $reply);

        app(UserRepliedTopic::class)->generate(Auth::user(), $topic, $reply);

        return $observer->creatorSucceed($reply);
    }

    public function isDuplicateReply($data)
    {
        $last_reply = Reply::where('user_id', Auth::id())
                            ->where('topic_id', $data['topic_id'])
                            ->orderBy('id', 'desc')
                            ->first();
        return count($last_reply) && strcmp($last_reply->body_original, $data['body']) === 0;
    }
}
