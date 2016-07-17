<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Reply;
use App\Models\HotTopic;
use App\Models\Favorite;
use App\Models\Attention;
use App\Models\Vote;
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

        $this->calculateVoteTopics();
        $this->calculateReplyTopics();
        $this->calculateWeight();
    }

    public function calculateVoteTopics()
    {
        $topics = Vote::query()->select(DB::raw('votable_id, count(*) as vote_count'))
                               ->where('votable_type', 'Topic')
                               ->where('is', 'upvote')
                               ->where('created_at', '>=', Carbon::now()->subDays(self::PASS_DAYS))
                               ->groupBy('votable_id')
                               ->get();

        foreach ($topics as $value) {
            $data = [];
            $data['topic_id'] = $value->votable_id;
            $data['vote_count'] = $value->vote_count;

            HotTopic::updateOrCreate(['topic_id' => $value->topic_id], $data);
        }
    }

    public function calculateReplyTopics()
    {
        $topics = Reply::query()->select(DB::raw('topic_id, count(*) as reply_count'))
                                     ->where('created_at', '>=', Carbon::now()->subDays(self::PASS_DAYS))
                                     ->groupBy('topic_id')
                                     ->get();

        foreach ($topics as $value) {
            $data = [];
            $data['topic_id'] = $value->topic_id;
            $data['reply_count'] = $value->reply_count;

            HotTopic::updateOrCreate(['topic_id' => $value->topic_id], $data);
        }
    }

    public function calculateWeight()
    {
        $hot_topics = HotTopic::all();
        foreach ($hot_topics as $hot_topic) {
            $hot_topic->weight = $hot_topic->vote_count * self::VOTE_TOPIC_WEIGHT
                                 + $hot_topic->reply_count * self::REPLY_TOPIC_WEIGHT;
            $hot_topic->save();
        }
    }
}
