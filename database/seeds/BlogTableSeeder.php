<?php

use Illuminate\Database\Seeder;

// composer require laracasts/testdummy
use Laracasts\TestDummy\Factory as TestDummy;

class BlogTableSeeder extends Seeder {

    public function run()
    {
        \DB::table('blogs')->delete();

        \DB::table('blogs')->insert(array (
            0 =>
            array (
                'id' => 1,
                'name' => '我的专栏',
                'slug' => 'myblog',
                'description' => '记录工作日志',
                'cover' => 'https://dn-phphub.qbox.me/uploads/images/201701/16/1/9Il9wyivOg.png',
                'user_id' => 1,
                'article_count' => 0,
                'subscriber_count' => 0,
                'is_recommended' => 0,
                'is_blocked' => 0,
                'created_at' => '2017-01-17 14:35:47',
                'updated_at' => '2017-01-17 14:35:47',
            ),
        ));

    }

}
