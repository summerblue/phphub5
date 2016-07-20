<?php

namespace Phphub\Handler;

use App\Models\User;
use App\Models\Reply;
use Illuminate\Mail\Message;
use Mail;
use Naux\Mail\SendCloudTemplate;
use Jrean\UserVerification\Facades\UserVerification;

class EmailHandler
{
    public static function sendActivateMail(User $user)
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

    public static function sendReplyNotifyMail(Reply $reply)
    {
        Mail::send('emails.fake', [], function (Message $message) use ($reply) {
            $message->subject(lang('Your topic have new reply'));

            $message->getSwiftMessage()->setBody(new SendCloudTemplate('new_reply', [
                'name' => "<a href='" . url(route('users.show', $reply->user_id)) . "' target='_blank'>{$reply->user->name}</a>",
                'title' => "<a href='" . url(route('topics.show', $reply->topic_id)) . "' target='_blank'>{$reply->topic->title}</a>",
                'content'  => $reply->body,
            ]));

            $message->to($reply->topic->user->email);
        });
    }
}
