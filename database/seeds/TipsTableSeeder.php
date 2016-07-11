<?php

use Illuminate\Database\Seeder;

class TipsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('tips')->delete();
        
        \DB::table('tips')->insert(array (
            0 => 
            array (
                'id' => 1,
                'body' => '<a href="http://laracasts.com/" target="_blank">Laracasts</a> 上面有很不错的 Laravel 开发技巧的视频，通通看完你可以学到很多东西',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            1 => 
            array (
                'id' => 2,
                'body' => '<a href="http://packalyst.com/" target="_blank">Packalyst</a> 上可以了解更多 Laravel 的 package.',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            2 => 
            array (
                'id' => 3,
                'body' => '<a href="http://userscape.com/laracon/2014/" target="_blank">Laracon 2014</a> 这里是 Laracon NYC 2014 的现场录像',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            3 => 
            array (
                'id' => 4,
                'body' => '<a href="http://cheats.jesse-obrien.ca/" target="_blank">Laravel Cheat Sheet</a> 这里是 Laravel 的 Cheat Sheet.',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            4 => 
            array (
                'id' => 5,
                'body' => 'laravel.com/docs 没事多读读文档, 每一次都可以收获不少.',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            5 => 
            array (
                'id' => 6,
                'body' => 'Laravel 在 HHVM 运行单元测试 100% 通过.',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            6 => 
            array (
                'id' => 7,
                'body' => 'Learn something about everything and everything about something.',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            7 => 
            array (
                'id' => 8,
                'body' => 'Any fool can write code that a computer can understand. Good programmers write code that humans can understand.',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            8 => 
            array (
                'id' => 9,
                'body' => '<a href="http://laravel-china.github.io/php-the-right-way/" target="_blank">php-the-right-way</a> 上可以学习更新的 php 知识',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            9 => 
            array (
                'id' => 10,
                'body' => '<a href="http://www.laravelpodcast.com/" target="_blank">Laravel.io </a>',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '2015-12-02 11:35:06',
            ),
            10 => 
            array (
                'id' => 11,
            'body' => 'Model::remember(5)->get(); 可以缓存 Model 五分钟',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            11 => 
            array (
                'id' => 12,
                'body' => '使用 CoffeeScript 和 Sass 来写 JavaScript 和 CSS 提高开发效率',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
        ));
        
        
    }
}
