<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Reply;
use Phphub\Handler\EmailHandler;

class SendReplyNotifyMail extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $reply;

    public function __construct(Reply $reply)
    {
        $this->reply = $reply;
    }

    public function handle()
    {
        EmailHandler::sendReplyNotifyMail($this->reply);
    }
}
