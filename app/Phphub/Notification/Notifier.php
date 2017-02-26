<?php namespace Phphub\Notification;

use Phphub\Forms\ReplyCreationForm;
use Phphub\Core\CreatorListener;
use Phphub\Notification\Mention;
use App\Models\Reply;
use Auth;
use App\Models\Topic;
use App\Models\Notification;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Append;
use App\Jobs\SendReplyNotifyMail;

class Notifier
{
    public $notifiedUsers = [];

    public function newTopicNotify(User $fromUser,  Mention $mentionParser, Topic $topic)
    {
        // Notify mentioned users
        Notification::batchNotify(
                    'mentioned_in_topic',
                    $fromUser,
                    $this->removeDuplication($mentionParser->users),
                    $topic);

        // Notify user follower
        Notification::batchNotify(
                    'new_topic_from_following',
                    $fromUser,
                    $this->removeDuplication($fromUser->followers),
                    $topic);

        // Notify blog subscriber
        if (count($topic->user->blogs)) {
            Notification::batchNotify(
                        'new_topic_from_subscribe',
                        $fromUser,
                        $this->removeDuplication($topic->user->blogs->first()->subscribers),
                        $topic);
        }
    }

    public function newReplyNotify(User $fromUser, Mention $mentionParser, Topic $topic, Reply $reply)
    {
        // Notify the author
        Notification::batchNotify(
                    'new_reply',
                    $fromUser,
                    $this->removeDuplication([$topic->user]),
                    $topic,
                    $reply);

        // Notify attented users
        Notification::batchNotify(
                    'attention',
                    $fromUser,
                    $this->removeDuplication($topic->attentedUsers()),
                    $topic,
                    $reply);

        // Notify mentioned users
        Notification::batchNotify(
                    'at',
                    $fromUser,
                    $this->removeDuplication($mentionParser->users),
                    $topic,
                    $reply);
    }

    public function newAppendNotify(User $fromUser, Topic $topic, Append $append)
    {
        $users = $topic->replies()->with('user')->get()->lists('user');

        // Notify commented user
        Notification::batchNotify(
                    'comment_append',
                    $fromUser,
                    $this->removeDuplication($users),
                    $topic,
                    null,
                    $append->content);

        // Notify voted users
        Notification::batchNotify(
                    'vote_append',
                    $fromUser,
                    $this->removeDuplication($topic->votedUsers()),
                    $topic,
                    null,
                    $append->content);

        // Notify attented users
        Notification::batchNotify(
                    'attented_append',
                    $fromUser,
                    $this->removeDuplication($topic->attentedUsers()),
                    $topic,
                    null,
                    $append->content);
    }

    public function newFollowNotify(User $fromUser, User $toUser)
    {
        Notification::notify(
                    'follow',
                    $fromUser,
                    $toUser,
                    null,
                    null,
                    null);
    }

    // in case of a user get a lot of the same notification
    public function removeDuplication($users)
    {
        $notYetNotifyUsers = [];
        foreach ($users as $user) {
            if ( ! in_array($user->id, $this->notifiedUsers)) {
                $notYetNotifyUsers[] = $user;
                $this->notifiedUsers[] = $user->id;
            }
        }
        return $notYetNotifyUsers;
    }
}
