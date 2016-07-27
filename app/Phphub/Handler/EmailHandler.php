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
        if (
            !isset($this->methodMap[$type])
            || $toUser->email_notify_enabled != 'yes'
            || $toUser->id == $fromUser->id
            || !$toUser->email || $toUser->verified != 1
        ) {
            return false;
        }

        $this->topic = $topic;
        $this->reply = $reply;
        $this->body = $body;
        $this->fromUser = $fromUser;
        $this->toUser = $toUser;

        $method = $this->methodMap[$type];
        $this->$method();
    }

    protected function sendNewReplyNotifyMail()
    {
        if (!$this->reply) {
            return false;
        }

        Mail::send('emails.fake', [], function (Message $message) {
            $message->subject(lang('Your topic have new reply'));

            $message->getSwiftMessage()->setBody(new SendCloudTemplate('notification_mail', [
                'name'     => "<a href='" . url(route('users.show', $this->fromUser->id)) . "' target='_blank'>{$this->fromUser->name}</a>",
                'action'   => " 回复了你的主题: <a href='" . url(route('topics.show', $this->reply->topic_id)) . "' target='_blank'>{$this->reply->topic->title}</a>
                              <br /><br />内容如下：<br />",
                'content'  => $this->reply->body,
            ]));
            $message->to($this->toUser->email);
        });
    }

    protected function sendAtNotifyMail(User $fromUser, User $toUser, Reply $reply = null)
    {
        if (!$this->reply) {
            return false;
        }

        Mail::send('emails.fake', [], function (Message $message) {
            $message->subject('有用户在主题中提及你');

            $message->getSwiftMessage()->setBody(new SendCloudTemplate('notification_mail', [
                'name'     => "<a href='" . url(route('users.show', $this->fromUser->id)) . "' target='_blank'>{$this->fromUser->name}</a>",
                'action'   => " 在主题中提及你: <a href='" . url(route('topics.show', $this->reply->topic_id)) . "' target='_blank'>{$this->reply->topic->title}</a>
                              <br /><br />内容如下：<br />",
                'content'  => $this->reply->body,
            ]));

            $message->to($this->toUser->email);
        });
    }

    protected function sendTopicAttentNotifyMail()
    {
        if (!$this->topic) {
            return false;
        }

        Mail::send('emails.fake', [], function (Message $message) {
            $message->subject('有用户关注了你的主题');

            $message->getSwiftMessage()->setBody(new SendCloudTemplate('notification_mail', [
                'name'    => "<a href='" . url(route('users.show', $this->fromUser->id)) . "' target='_blank'>{$this->fromUser->name}</a>",
                'action'  => " 关注了你的主题: <a href='" . url(route('topics.show', $this->topic->id)) . "' target='_blank'>{$this->topic->title}</a>",
                'content' => '',
            ]));

            $message->to($this->toUser->email);
        });
    }

    protected function sendAttentionNotifyMail()
    {
    }

    protected function sendAttentionAppendNotifyMail()
    {
    }

    protected function sendCommentAppendNotifyMail()
    {
    }

    protected function sendFollowNotifyMail()
    {
        Mail::send('emails.fake', [], function (Message $message) {
            $message->subject('有用户关注了你');

            $message->getSwiftMessage()->setBody(new SendCloudTemplate('notification_mail', [
                'name'     => "<a href='" . url(route('users.show', $this->fromUser->id)) . "' target='_blank'>{$this->fromUser->name}</a>",
                'action'   => " 关注了你",
                'content'  => "",
            ]));

            $message->to($this->toUser->email);
        });
    }

    protected function sendReplyUpvoteNotifyMail()
    {
        if (!$this->reply) {
            return false;
        }

        Mail::send('emails.fake', [], function (Message $message) {
            $message->subject('有用户赞了你的回复');

            $message->getSwiftMessage()->setBody(new SendCloudTemplate('notification_mail', [
                'name'     => "<a href='" . url(route('users.show', $this->fromUser->id)) . "' target='_blank'>{$this->fromUser->name}</a>",
                'action'   => " 赞了你的回复: <a href='" . url(route('topics.show', $this->reply->topic_id)) . "' target='_blank'>{$this->reply->topic->title}</a>
                              <br /><br />你的回复内容如下：<br />",
                'content'  => $this->reply->body,
            ]));

            $message->to($this->toUser->email);
        });
    }

    protected function sendTopicFavoriteNotifyMail()
    {
        if (!$this->topic) {
            return false;
        }

        Mail::send('emails.fake', [], function (Message $message) {
            $message->subject('有用户收藏了你的主题');

            $message->getSwiftMessage()->setBody(new SendCloudTemplate('notification_mail', [
                'name'    => "<a href='" . url(route('users.show', $this->fromUser->id)) . "' target='_blank'>{$this->fromUser->name}</a>",
                'action'  => " 收藏了你的主题: <a href='" . url(route('topics.show', $this->topic->id)) . "' target='_blank'>{$this->topic->title}</a>",
                'content' => '',
            ]));

            $message->to($this->toUser->email);
        });
    }

    protected function sendTopicMarkExcellentNotifyMail()
    {
        if (!$this->topic) {
            return false;
        }

        Mail::send('emails.fake', [], function (Message $message) {
            $message->subject('管理员推荐了你的主题');

            $message->getSwiftMessage()->setBody(new SendCloudTemplate('notification_mail', [
                'name'    => "<a href='" . url(route('users.show', $this->fromUser->id)) . "' target='_blank'>{$this->fromUser->name}</a>",
                'action'  => " 推荐了你的主题: <a href='" . url(route('topics.show', $this->topic->id)) . "' target='_blank'>{$this->topic->title}</a>",
                'content' => '',
            ]));

            $message->to($this->toUser->email);
        });
    }

    protected function sendTopicUpvoteNotifyMail()
    {
        if (!$this->topic) {
            return false;
        }

        Mail::send('emails.fake', [], function (Message $message) {
            $message->subject('有用户赞了你的主题');

            $message->getSwiftMessage()->setBody(new SendCloudTemplate('notification_mail', [
                'name'    => "<a href='" . url(route('users.show', $this->fromUser->id)) . "' target='_blank'>{$this->fromUser->name}</a>",
                'action'  => " 赞了你的主题: <a href='" . url(route('topics.show', $this->topic->id)) . "' target='_blank'>{$this->topic->title}</a>",
                'content' => '',
            ]));

            $message->to($this->toUser->email);
        });
    }
}
