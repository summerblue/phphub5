<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Topic;
use App\Models\Reply;
use App\Models\ActiveUser;
use Carbon\Carbon;
use DB;

class CalculateActiveUser extends Command
{

    protected $signature = 'phphub:calculate-active-user';
    protected $description = 'Calculate active user';

    const POST_TOPIC_WEIGHT = 4;
    const POST_REPLY_WEIGHT = 1;
    const PASS_DAYS = 7;

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        ActiveUser::query()->delete();

        $this->calculateTopicUsers();
        $this->calculateReplyUsers();
        $this->calculateWeight();
    }

    protected function calculateTopicUsers()
    {
        $topic_users = Topic::query()->select(DB::raw('user_id, count(*) as topic_count'))
                                     ->where('created_at', '>=', Carbon::now()->subDays(self::PASS_DAYS))
                                     ->groupBy('user_id')
                                     ->get();

        foreach ($topic_users as $value) {
            $data = [];
            $data['user_id'] = $value->user_id;
            $data['topic_count'] = $value->topic_count;

            ActiveUser::updateOrCreate(['user_id' => $value->user_id], $data);
        }
    }

    protected function calculateReplyUsers()
    {
        $reply_users = Reply::query()->select(DB::raw('user_id, count(*) as reply_count'))
                                     ->where('created_at', '>=', Carbon::now()->subDays(self::PASS_DAYS))
                                     ->groupBy('user_id')
                                     ->get();

        foreach ($reply_users as $value) {
            $data = [];
            $data['user_id'] = $value->user_id;
            $data['reply_count'] = $value->reply_count;

            ActiveUser::updateOrCreate(['user_id' => $value->user_id], $data);
        }
    }

    protected function calculateWeight()
    {
        $active_users = ActiveUser::all();
        foreach ($active_users as $active_user) {
            $active_user->weight = $active_user->topic_count * self::POST_TOPIC_WEIGHT
                                 + $active_user->reply_count * self::POST_REPLY_WEIGHT;
            $active_user->save();
        }
    }
}
