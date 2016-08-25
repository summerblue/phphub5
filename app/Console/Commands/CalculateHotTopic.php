<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Reply;
use App\Models\HotTopic;
use App\Models\Vote;
use App\Models\Topic;
use Carbon\Carbon;
use DB;

class CalculateHotTopic extends Command
{
    protected $signature = 'phphub:calculate-hot-topic';
    protected $description = 'Calculate hot topic';

    const VOTE_TOPIC_WEIGHT = 5;
    const REPLY_TOPIC_WEIGHT = 3;
    const PASS_DAYS = 7;

    public function __construct()
    {
        parent::__construct();
    }


    public function handle()
    {
        HotTopic::query()->delete();
        $this->calculateTopics();
    }

    public function calculateTopics()
    {
        $topics = Topic::where('created_at', '>=', Carbon::now()->subDays(self::PASS_DAYS))->get();
        foreach ($topics as $topic) {
            $data = [];
            $data['topic_id']    = $topic->id;
            $data['reply_count'] = Reply::where('topic_id', $topic->id)->count();
            $data['vote_count']  = Vote::where('votable_type', 'App\Models\Topic')
                                       ->where('votable_id', $topic->id)
                                       ->where('is', 'upvote')
                                       ->count();

            $data['weight'] = $data['vote_count'] * self::VOTE_TOPIC_WEIGHT
                            + $data['reply_count'] * self::REPLY_TOPIC_WEIGHT;

            HotTopic::updateOrCreate(['topic_id' => $topic->id], $data);
        }
    }
}
