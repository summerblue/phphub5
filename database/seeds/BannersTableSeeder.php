<?php

use Illuminate\Database\Seeder;

class BannersTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        \DB::table('banners')->delete();

        \DB::table('banners')->insert(array (
            0 =>
            array (
                'id' => 1,
                'position' => 'website_top',
                'order' => 1,
                'image_url' => 'https://dn-phphub.qbox.me/uploads/banners/qCpz5a1iBETEfnNEAkGe.png',
                'title' => 'Laravel 5.1 中文文档',
                'link' => 'https://doc.laravel-china.org/docs/5.1',
                'target' => '_blank',
                'description' => 'Laravel China 社区维护的中文文档，涵盖 5.1, 5.2, 5.3 版本',
                'created_at' => '2016-07-12 11:31:36',
                'updated_at' => '2016-07-12 11:31:36',
            ),
            1 =>
            array (
                'id' => 3,
                'position' => 'website_top',
                'order' => 3,
                'image_url' => 'https://dn-phphub.qbox.me/uploads/banners/YCkIqPrz6v8MV0keu4pW.png',
                'title' => 'Laravel 速查表',
                'link' => 'https://cs.laravel-china.org/',
                'target' => '_blank',
                'description' => '速查表方便快速查询框架功能，支持手机访问，支持中英文版本',
                'created_at' => '2016-07-12 11:32:25',
                'updated_at' => '2016-07-12 11:32:25',
            ),
            2 =>
            array (
                'id' => 4,
                'position' => 'website_top',
                'order' => 2,
                'image_url' => 'https://dn-phphub.qbox.me/uploads/banners/0wgbAVabZB9GA2yaU8AY.png',
                'title' => '酷工作',
                'link' => 'categories/1',
                'target' => '_self',
                'description' => 'Laravel\PHP 相关的招聘、求职、外包、接包、远程工作...',
                'created_at' => '2016-07-12 11:33:05',
                'updated_at' => '2016-07-12 15:03:56',
            ),
            3 =>
            array (
                'id' => 5,
                'position' => 'website_top',
                'order' => 4,
                'image_url' => 'https://dn-phphub.qbox.me/uploads/banners/0pyH7UgXhF7PTBkLZRak.png',
                'title' => 'PSR PHP 标准规范',
                'link' => 'https://psr.phphub.org/',
                'target' => '_blank',
                'description' => 'PSR 由 PHP FIG 组织制定的 PHP 规范，是 PHP 开发的实践标准',
                'created_at' => '2016-07-12 11:33:40',
                'updated_at' => '2016-07-12 11:33:40',
            ),
            4 =>
            array (
                'id' => 6,
                'position' => 'website_top',
                'order' => 6,
                'image_url' => 'https://dn-phphub.qbox.me/uploads/banners/HCNo4rSRxIpK12yDL13U.png',
                'title' => '新手入门 PHP 之道',
                'link' => 'http://laravel-china.github.io/php-the-right-way/',
                'target' => '_blank',
                'description' => '正确的学习 PHP 的方式',
                'created_at' => '2016-07-12 11:34:07',
                'updated_at' => '2016-07-12 11:34:07',
            ),
            5 =>
            array (
                'id' => 7,
                'position' => 'website_top',
                'order' => 5,
                'image_url' => 'https://dn-phphub.qbox.me/uploads/banners/EptWCkT1qDDvtn5nV2id.png',
                'title' => 'Laravel API 文档',
                'link' => 'http://laravel-china.org/api/5.1/',
                'target' => '_blank',
                'description' => 'Laravel API 文档，涵盖 5.1, 5.2, 5.3 版本',
                'created_at' => '2016-07-12 11:34:36',
                'updated_at' => '2016-07-12 15:05:09',
            ),
            6 =>
            array (
                'id' => 8,
                'position' => 'sidebar-resources',
                'order' => 5,
                'image_url' => 'https://dn-phphub.qbox.me/uploads/banners/EptWCkT1qDDvtn5nV2id.png',
                'title' => 'Laravel API 文档',
                'link' => 'http://laravel-china.org/api/5.1/',
                'target' => '_blank',
                'description' => 'Laravel API 文档，涵盖 5.1, 5.2, 5.3 版本',
                'created_at' => '2016-07-12 11:34:36',
                'updated_at' => '2016-07-12 15:05:09',
            ),
            7 =>
            array (
                'id' => 9,
                'position' => 'sidebar-resources',
                'order' => 6,
                'image_url' => 'https://dn-phphub.qbox.me/uploads/banners/HCNo4rSRxIpK12yDL13U.png',
                'title' => '新手入门 PHP 之道',
                'link' => 'http://laravel-china.github.io/php-the-right-way/',
                'target' => '_blank',
                'description' => '正确的学习 PHP 的方式',
                'created_at' => '2016-07-12 11:34:07',
                'updated_at' => '2016-07-12 11:34:07',
            ),
        ));


    }
}
