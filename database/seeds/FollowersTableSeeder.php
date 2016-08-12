<?php

use Illuminate\Database\Seeder;

class FollowersTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('followers')->delete();

        \DB::table('followers')->insert(array (
            0 =>
            array (
                'id' => 4,
                'user_id' => 1,
                'follow_id' => 2,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            1 =>
            array (
                'id' => 5,
                'user_id' => 1,
                'follow_id' => 3,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            2 =>
            array (
                'id' => 6,
                'user_id' => 1,
                'follow_id' => 4,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            3 =>
            array (
                'id' => 7,
                'user_id' => 1,
                'follow_id' => 5,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            4 =>
            array (
                'id' => 8,
                'user_id' => 1,
                'follow_id' => 6,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            5 =>
            array (
                'id' => 9,
                'user_id' => 1,
                'follow_id' => 7,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            6 =>
            array (
                'id' => 10,
                'user_id' => 1,
                'follow_id' => 8,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            7 =>
            array (
                'id' => 11,
                'user_id' => 1,
                'follow_id' => 9,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            8 =>
            array (
                'id' => 12,
                'user_id' => 1,
                'follow_id' => 10,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            9 =>
            array (
                'id' => 13,
                'user_id' => 1,
                'follow_id' => 11,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            10 =>
            array (
                'id' => 14,
                'user_id' => 1,
                'follow_id' => 12,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            11 =>
            array (
                'id' => 15,
                'user_id' => 1,
                'follow_id' => 13,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            12 =>
            array (
                'id' => 16,
                'user_id' => 1,
                'follow_id' => 14,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            13 =>
            array (
                'id' => 17,
                'user_id' => 1,
                'follow_id' => 15,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            14 =>
            array (
                'id' => 18,
                'user_id' => 1,
                'follow_id' => 16,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            15 =>
            array (
                'id' => 19,
                'user_id' => 1,
                'follow_id' => 17,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            16 =>
            array (
                'id' => 20,
                'user_id' => 1,
                'follow_id' => 18,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            17 =>
            array (
                'id' => 21,
                'user_id' => 1,
                'follow_id' => 19,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            18 =>
            array (
                'id' => 22,
                'user_id' => 1,
                'follow_id' => 20,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            19 =>
            array (
                'id' => 23,
                'user_id' => 1,
                'follow_id' => 21,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            20 =>
            array (
                'id' => 24,
                'user_id' => 1,
                'follow_id' => 22,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            21 =>
            array (
                'id' => 25,
                'user_id' => 23,
                'follow_id' => 1,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            22 =>
            array (
                'id' => 26,
                'user_id' => 24,
                'follow_id' => 1,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            23 =>
            array (
                'id' => 27,
                'user_id' => 25,
                'follow_id' => 1,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            24 =>
            array (
                'id' => 28,
                'user_id' => 26,
                'follow_id' => 1,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            25 =>
            array (
                'id' => 29,
                'user_id' => 27,
                'follow_id' => 1,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            26 =>
            array (
                'id' => 30,
                'user_id' => 28,
                'follow_id' => 1,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            27 =>
            array (
                'id' => 31,
                'user_id' => 29,
                'follow_id' => 1,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            28 =>
            array (
                'id' => 32,
                'user_id' => 30,
                'follow_id' => 1,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            29 =>
            array (
                'id' => 33,
                'user_id' => 31,
                'follow_id' => 1,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            30 =>
            array (
                'id' => 34,
                'user_id' => 32,
                'follow_id' => 1,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            31 =>
            array (
                'id' => 35,
                'user_id' => 33,
                'follow_id' => 1,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            32 =>
            array (
                'id' => 36,
                'user_id' => 34,
                'follow_id' => 1,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            33 =>
            array (
                'id' => 37,
                'user_id' => 35,
                'follow_id' => 1,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            34 =>
            array (
                'id' => 38,
                'user_id' => 36,
                'follow_id' => 1,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            35 =>
            array (
                'id' => 39,
                'user_id' => 37,
                'follow_id' => 1,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            36 =>
            array (
                'id' => 40,
                'user_id' => 38,
                'follow_id' => 1,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            37 =>
            array (
                'id' => 41,
                'user_id' => 39,
                'follow_id' => 1,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            38 =>
            array (
                'id' => 42,
                'user_id' => 40,
                'follow_id' => 1,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            39 =>
            array (
                'id' => 43,
                'user_id' => 41,
                'follow_id' => 1,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            40 =>
            array (
                'id' => 44,
                'user_id' => 42,
                'follow_id' => 1,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            41 =>
            array (
                'id' => 45,
                'user_id' => 43,
                'follow_id' => 1,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            42 =>
            array (
                'id' => 46,
                'user_id' => 44,
                'follow_id' => 1,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            43 =>
            array (
                'id' => 47,
                'user_id' => 45,
                'follow_id' => 1,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            44 =>
            array (
                'id' => 48,
                'user_id' => 46,
                'follow_id' => 1,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            45 =>
            array (
                'id' => 49,
                'user_id' => 47,
                'follow_id' => 1,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            46 =>
            array (
                'id' => 50,
                'user_id' => 48,
                'follow_id' => 1,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            47 =>
            array (
                'id' => 51,
                'user_id' => 49,
                'follow_id' => 1,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),

        ));


    }
}
