<?php

namespace Phphub\Handler;

use App\Models\User;
use App\Models\Reply;
use App\Models\Topic;
use App\Models\Mention;
use App\Models\Append;
use Illuminate\Mail\Message;
use Mail;
use Naux\Mail\SendCloudTemplate;
use Jrean\UserVerification\Facades\UserVerification;

class EmailHandler
{
    protected $methodMap = [
        'at'                   => 'sendAtNotifyMail',
        'attention'            => 'sendAttentionNotifyMail',
        'attention_append'     => 'sendAttentionAppendNotifyMail',
        'comment_append'       => 'sendCommentAppendNotifyMail',
        'follow'               => 'sendFollowNotifyMail',
        'new_reply'            => 'sendNewReplyNotifyMail',
        'reply_upvote'         => 'sendReplyUpvoteNotifyMail',
        'topic_attent'         => 'sendTopicAttentNotifyMail',
        'topic_favorite'       => 'sendTopicFavoriteNotifyMail',
        'topic_mark_excellent' => 'sendTopicMarkExcellentNotifyMail',
        'topic_upvote'         => 'sendTopicUpvoteNotifyMail',
    ];

    protected $type;
    protected $fromUser;
    protected $toUser;
    protected $topic;
    protected $reply;
    protected $body;

    public function sendActivateMail(User $user)
    {
        UserVerification::generate($user);
        $token = $user->verification_token;
        Mail::send('emails.fake', [], function (Message $message) use ($user, $token) {
            $message->subject(lang('Please verify your email address'));

            $message->getSwiftMessage()->setBody(new SendCloudTemplate('template_active', [
                'name' => $user->name,
                'url'  => url('verification', $user->verification_token).'?email='.urlencode($user->email),
            ]));
            $message->to($user->email);
        });
    }

    public function sendNotifyMail($type, User $fromUser, User $toUser, Topic $topic = null, Reply $reply = null, $body = null)
    {
        if (!isset($this->methodMap[$type])) {
            return false;
        }

        $this->topic = $topic;
        $this->reply = $reply;
        $this->body = $body;

        $method = $this->methodMap[$type];
        $this->$method($fromUser, $toUser);
    }

    public function sendNewReplyNotifyMail(User $fromUser, User $toUser, Reply $reply = null)
    {
        $this->reply = $reply ? $reply : $this->reply;
        if (!$this->reply || $toUser->email_notify_enabled != 'yes' || $toUser->id == $fromUser->id) {
            return false;
        }

        Mail::send('emails.fake', [], function (Message $message) use ($fromUser, $toUser) {
            $message->subject(lang('Your topic have new reply'));

            $message->getSwiftMessage()->setBody(new SendCloudTemplate('notification_mail', [
                'name'     => "<a href='" . url(route('users.show', $fromUser->id)) . "' target='_blank'>{$fromUser->name}</a>",
                'action'   => " 回复了你的主题: <a href='" . url(route('topics.show', $this->reply->topic_id)) . "' target='_blank'>{$this->reply->topic->title}</a>
                              <br /><br />内容如下：<br />",
                'content'  => $this->reply->body,
            ]));
            $message->to($toUser->email);
        });
    }

    public function sendAtNotifyMail(Mention $mentionParser = null, Topic $topic = null, Reply $reply = null)
    {
    }

    public function sendAttentionNotifyMail()
    {
    }

    public function sendAttentionAppendNotifyMail()
    {
    }

    public function sendCommentAppendNotifyMail()
    {
    }

    public function sendFollowNotifyMail()
    {
    }

    public function sendReplyUpvoteNotifyMail()
    {
    }

    public function sendTopicAttentNotifyMail()
    {
    }

    public function sendTopicFavoriteNotifyMail()
    {
    }

    public function sendTopicMarkExcellentNotifyMail()
    {
    }

    public function sendTopicUpvoteNotifyMail()
    {
    }
}
