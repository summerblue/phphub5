<?php

namespace Phphub\Handler;

use App\Models\User;
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
            $message->subject('请激活您的账号');

            $message->getSwiftMessage()->setBody(new SendCloudTemplate('template_active', [
                'name' => $user->name,
                'url'  => url('verification', $user->verification_token).'?email='.urlencode($user->email),
            ]));
            $message->to($user->email);
        });
    }
}
