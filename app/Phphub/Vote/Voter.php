<?php namespace Phphub\Vote;

use App\Models\Reply;
use App\Models\Topic;
use App\Models\Vote;
use App\Models\User;
use App\Models\Notification;
use Carbon\Carbon;
use Auth;
use App\Activities\UserUpvotedTopic;
use App\Activities\UserUpvotedReply;

class Voter
{
    public $notifiedUsers = [];

    public function topicUpVote(Topic $topic)
    {
        if ($topic->votes()->ByWhom(Auth::id())->WithType('upvote')->count()) {
            // click twice for remove upvote
            $topic->votes()->ByWhom(Auth::id())->WithType('upvote')->delete();
            $topic->decrement('vote_count', 1);
            app(UserUpvotedTopic::class)->remove(Auth::user(), $topic);
        } elseif ($topic->votes()->ByWhom(Auth::id())->WithType('downvote')->count()) {
            // user already clicked downvote once
            $topic->votes()->ByWhom(Auth::id())->WithType('downvote')->delete();
            $topic->votes()->create(['user_id' => Auth::id(), 'is' => 'upvote']);
            $topic->increment('vote_count', 2);
            app(UserUpvotedTopic::class)->generate(Auth::user(), $topic);
        } else {
            // first time click
            $topic->votes()->create(['user_id' => Auth::id(), 'is' => 'upvote']);
            $topic->increment('vote_count', 1);
            app(UserUpvotedTopic::class)->generate(Auth::user(), $topic);
            Notification::notify('topic_upvote', Auth::user(), $topic->user, $topic);
        }

        if ($topic->isShareLink()) {
            $topic->updated_at = Carbon::now()->toDateTimeString();
            $topic->save();
        }

        return $topic;
    }

    public function topicDownVote(Topic $topic)
    {
        if ($topic->votes()->ByWhom(Auth::id())->WithType('downvote')->count()) {
            // click second time for remove downvote
            $topic->votes()->ByWhom(Auth::id())->WithType('downvote')->delete();
            $topic->increment('vote_count', 1);
        } elseif ($topic->votes()->ByWhom(Auth::id())->WithType('upvote')->count()) {
            // user already clicked upvote once
            $topic->votes()->ByWhom(Auth::id())->WithType('upvote')->delete();
            $topic->votes()->create(['user_id' => Auth::id(), 'is' => 'downvote']);
            $topic->decrement('vote_count', 2);
        } else {
            // click first time
            $topic->votes()->create(['user_id' => Auth::id(), 'is' => 'downvote']);
            $topic->decrement('vote_count', 1);
        }
    }

    public function replyUpVote(Reply $reply)
    {
        if (Auth::id() == $reply->user_id) {
            return \Flash::warning(lang('Can not vote your feedback'));
        }

        $return = [];
        if ($reply->votes()->ByWhom(Auth::id())->WithType('upvote')->count()) {
            // click twice for remove upvote
            $reply->votes()->ByWhom(Auth::id())->WithType('upvote')->delete();
            $reply->decrement('vote_count', 1);
            $return['action_type'] = 'sub';
            app(UserUpvotedReply::class)->remove(Auth::user(), $reply);
        } elseif ($reply->votes()->ByWhom(Auth::id())->WithType('downvote')->count()) {
            // user already clicked downvote once
            $reply->votes()->ByWhom(Auth::id())->WithType('downvote')->delete();
            $reply->votes()->create(['user_id' => Auth::id(), 'is' => 'upvote']);
            $reply->increment('vote_count', 2);
            $return['action_type'] = 'add';
            app(UserUpvotedReply::class)->generate(Auth::user(), $reply);
        } else {
            // first time click
            $reply->votes()->create(['user_id' => Auth::id(), 'is' => 'upvote']);
            $reply->increment('vote_count', 1);
            $return['action_type'] = 'add';

            Notification::notify('reply_upvote', Auth::user(), $reply->user, $reply->topic, $reply);
            app(UserUpvotedReply::class)->generate(Auth::user(), $reply);
        }
        return $return;
    }
}
