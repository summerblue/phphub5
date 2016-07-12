<?php

use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        \DB::table('categories')->delete();

        \DB::table('categories')->insert(array(
            0 =>
            array(
                'id'          => 1,
                'parent_id'   => 0,
                'post_count'  => 0,
                'weight'      => 100,
                'name'        => '招聘',
                'slug'        => 'zhao-pin',
                'description' => '这里有高质量的 PHPer, 记得认真填写你的需求, 薪资待遇是必须写的哦.',
                'created_at'  => '2016-07-03 10:00:00',
                'updated_at'  => '2016-07-03 10:00:00',
                'deleted_at'  => null,
            ),
            1 =>
            array(
                'id'          => 3,
                'parent_id'   => 0,
                'post_count'  => 0,
                'weight'      => 97,
                'name'        => '公告',
                'slug'        => 'gong-gao',
                'description' => null,
                'created_at'  => '2016-07-03 10:00:00',
                'updated_at'  => '2016-07-03 10:00:00',
                'deleted_at'  => null,
            ),
            2 =>
            array(
                'id'          => 4,
                'parent_id'   => 0,
                'post_count'  => 0,
                'weight'      => 99,
                'name'        => '问答',
                'slug'        => 'wen-da',
                'description' => null,
                'created_at'  => '2016-07-03 10:00:00',
                'updated_at'  => '2016-07-03 10:00:00',
                'deleted_at'  => null,
            ),
            3 =>
            array(
                'id'          => 5,
                'parent_id'   => 0,
                'post_count'  => 0,
                'weight'      => 98,
                'name'        => '分享',
                'slug'        => 'fen-xiang',
                'description' => null,
                'created_at'  => '2016-07-03 10:00:00',
                'updated_at'  => '2016-07-03 10:00:00',
                'deleted_at'  => null,
            ),
        ));
    }
}
