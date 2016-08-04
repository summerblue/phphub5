<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
use App\Models\Vote;
use App\Models\Topic;

class UpgrateVoteData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'phphub:upgrate-vote-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }


    public function handle()
    {
        $favorites = DB::table('favorites')->get();
        foreach ($favorites as $favorite) {
            $data = [];
            $data['votable_type'] = 'App\Models\Topic';
            $data['is'] = 'upvote';
            $data['user_id'] = $favorite->user_id;
            $data['votable_id'] = $favorite->topic_id;
            $data['created_at'] = $favorite->created_at;
            $data['updated_at'] = $favorite->updated_at;

            Vote::updateOrCreate([
                'votable_type' => $data['votable_type'],
                'votable_id'   => $data['votable_id'],
                'user_id'      => $data['user_id']
            ], $data);
        }

        $attentions = DB::table('attentions')->get();
        foreach ($attentions as $attention) {
            $data = [];
            $data['votable_type'] = 'App\Models\Topic';
            $data['is'] = 'upvote';
            $data['user_id'] = $attention->user_id;
            $data['votable_id'] = $attention->topic_id;
            $data['created_at'] = $attention->created_at;
            $data['updated_at'] = $attention->updated_at;

            Vote::updateOrCreate([
                'votable_type' => $data['votable_type'],
                'votable_id'   => $data['votable_id'],
                'user_id'      => $data['user_id']
            ], $data);
        }

        $topics = Topic::all();
        foreach ($topics as $topic) {
            $count = Vote::where('is', 'upvote')
                ->where('votable_id', $topic->id)
                ->where('votable_type', 'App\Models\Topic')
                ->get()
                ->count();
            $topic->vote_count = $count;
            $topic->save();
        }
    }
}
