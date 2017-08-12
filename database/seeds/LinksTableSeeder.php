<?php

use Illuminate\Database\Seeder;

class LinksTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('links')->delete();

        \DB::table('links')->insert(array (
            0 =>
            array (
                'id' => 1,
                'title' => 'Ruby China',
                'link' => 'https://ruby-china.org',
                'cover' => 'https://dn-phphub.qbox.me/assets/images/friends/ruby-china.png',
                'created_at' => '2014-10-12 08:29:15',
                'updated_at' => '2014-10-31 06:01:20',
            ),
            1 =>
            array (
                'id' => 2,
                'title' => 'Golang 中国',
                'link' => 'http://golangtc.com/',
                'cover' => 'https://dn-phphub.qbox.me/assets/images/friends/golangcn.png',
                'created_at' => '2014-10-12 08:29:15',
                'updated_at' => '2014-10-31 06:04:39',
            ),
            2 =>
            array (
                'id' => 3,
                'title' => 'CNode：Node.js 中文社区',
                'link' => 'http://cnodejs.org/',
                'cover' => 'https://dn-phphub.qbox.me/assets/images/friends/cnodejs.png',
                'created_at' => '2014-10-12 08:29:15',
                'updated_at' => '2014-10-31 06:05:03',
            ),
            3 =>
            array (
                'id' => 4,
            'title' => 'ElixirChina (ElixirCN) ',
                'link' => 'http://elixir-cn.com/',
                'cover' => 'https://dn-phphub.qbox.me/f65fb5a10d3392a1db841c85716dd8f6.png',
                'created_at' => '2014-10-12 08:29:15',
                'updated_at' => '2015-01-15 00:07:38',
            ),
            4 =>
            array (
                'id' => 5,
                'title' => 'Ionic China',
                'link' => 'http://ionichina.com/',
                'cover' => 'https://dn-phphub.qbox.me/assets/images/friends/ionic.png',
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            5 =>
            array (
                'id' => 6,
                'title' => 'Tester Home',
                'link' => 'https://testerhome.com',
                'cover' => 'https://dn-phphub.qbox.me/testerhome-logo.png',
                'created_at' => '2015-05-17 11:37:40',
                'updated_at' => '2015-05-17 11:37:40',
            ),
        ));


    }
}
