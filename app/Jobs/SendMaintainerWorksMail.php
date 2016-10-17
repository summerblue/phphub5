<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\User;

class SendMaintainerWorksMail extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $user;
    protected $timeFrame;
    protected $content;

    public function __construct(User $user, $timeFrame, $content)
    {
        $this->user = $user;
        $this->timeFrame = $timeFrame;
        $this->content = $content;
    }

    public function handle()
    {
        return app('Phphub\Handler\EmailHandler')->sendMaintainerWorksMail($this->user, $this->timeFrame, $this->content);
    }
}
