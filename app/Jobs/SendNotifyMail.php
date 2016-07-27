<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\User;
use App\Models\Reply;
use App\Models\Topic;
use App\Models\Mention;
use App\Models\Append;

class SendNotifyMail extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $type;
    protected $fromUser;
    protected $toUser;
    protected $body;
    protected $topic;
    protected $reply;


    public function __construct($type, User $fromUser, User $toUser, Topic $topic = null, Reply $reply = null, $body = null)
    {
        $this->type = $type;
        $this->fromUser = $fromUser;
        $this->toUser = $toUser;
        $this->topic = $topic;
        $this->reply = $reply;
        $this->body = $body;
    }

    public function handle()
    {
        app('Phphub\Handler\EmailHandler')->sendNotifyMail($this->type, $this->fromUser, $this->toUser, $this->topic, $this->reply, $this->body);
    }
}
