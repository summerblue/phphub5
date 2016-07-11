<?php

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Reply;
use App\Models\Topic;

class RepliesTableSeeder extends Seeder
{
    public function run()
    {
        $users  = User::lists('id')->toArray();
        $topics = Topic::lists('id')->toArray();
        $faker  = app(Faker\Generator::class);

        $replies = factory(Reply::class)->times(rand(300, 500))->make()->each(function ($reply) use ($faker, $users, $topics) {
            $reply->user_id  = $faker->randomElement($users);
            $reply->topic_id = $faker->randomElement($topics);
        });
        
        Reply::insert($replies->toArray());
    }
}
