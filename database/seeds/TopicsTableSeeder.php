<?php

use Illuminate\Database\Seeder;
use App\Models\Topic;
use App\Models\User;
use App\Models\Category;

class TopicsTableSeeder extends Seeder
{

    public function run()
    {
        $users = User::lists('id')->toArray();
        $categories = Category::lists('id')->toArray();

        $faker = app(Faker\Generator::class);

        $topics = factory(Topic::class)->times(rand(100, 200))->make()->each(function ($topic) use ($faker, $users, $categories) {
            $topic->user_id      = $faker->randomElement($users);
            $topic->category_id  = $faker->randomElement($categories);
            $topic->is_excellent = rand(0, 1) ? 'yes' : 'no';
        });
        Topic::insert($topics->toArray());

        $admin_topics = factory(Topic::class)->times(rand(1, 100))->make()->each(function ($topic) use ($faker, $categories) {
            $topic->user_id     = 1;
            $topic->category_id = $faker->randomElement($categories);
        });
        Topic::insert($admin_topics->toArray());
    }
}
