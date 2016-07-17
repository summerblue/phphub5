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
                'user_id' => 1,
                'follow_id' => 23,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            22 => 
            array (
                'id' => 26,
                'user_id' => 1,
                'follow_id' => 24,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            23 => 
            array (
                'id' => 27,
                'user_id' => 1,
                'follow_id' => 25,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            24 => 
            array (
                'id' => 28,
                'user_id' => 1,
                'follow_id' => 26,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            25 => 
            array (
                'id' => 29,
                'user_id' => 1,
                'follow_id' => 27,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            26 => 
            array (
                'id' => 30,
                'user_id' => 1,
                'follow_id' => 28,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            27 => 
            array (
                'id' => 31,
                'user_id' => 1,
                'follow_id' => 29,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            28 => 
            array (
                'id' => 32,
                'user_id' => 1,
                'follow_id' => 30,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            29 => 
            array (
                'id' => 33,
                'user_id' => 1,
                'follow_id' => 31,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            30 => 
            array (
                'id' => 34,
                'user_id' => 1,
                'follow_id' => 32,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            31 => 
            array (
                'id' => 35,
                'user_id' => 1,
                'follow_id' => 33,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            32 => 
            array (
                'id' => 36,
                'user_id' => 1,
                'follow_id' => 34,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            33 => 
            array (
                'id' => 37,
                'user_id' => 1,
                'follow_id' => 35,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            34 => 
            array (
                'id' => 38,
                'user_id' => 1,
                'follow_id' => 36,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            35 => 
            array (
                'id' => 39,
                'user_id' => 1,
                'follow_id' => 37,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            36 => 
            array (
                'id' => 40,
                'user_id' => 1,
                'follow_id' => 38,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            37 => 
            array (
                'id' => 41,
                'user_id' => 1,
                'follow_id' => 39,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            38 => 
            array (
                'id' => 42,
                'user_id' => 1,
                'follow_id' => 40,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            39 => 
            array (
                'id' => 43,
                'user_id' => 1,
                'follow_id' => 41,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            40 => 
            array (
                'id' => 44,
                'user_id' => 1,
                'follow_id' => 42,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            41 => 
            array (
                'id' => 45,
                'user_id' => 1,
                'follow_id' => 43,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            42 => 
            array (
                'id' => 46,
                'user_id' => 1,
                'follow_id' => 44,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            43 => 
            array (
                'id' => 47,
                'user_id' => 1,
                'follow_id' => 45,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            44 => 
            array (
                'id' => 48,
                'user_id' => 1,
                'follow_id' => 46,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            45 => 
            array (
                'id' => 49,
                'user_id' => 1,
                'follow_id' => 47,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            46 => 
            array (
                'id' => 50,
                'user_id' => 1,
                'follow_id' => 48,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            47 => 
            array (
                'id' => 51,
                'user_id' => 1,
                'follow_id' => 49,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            48 => 
            array (
                'id' => 52,
                'user_id' => 1,
                'follow_id' => 50,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            49 => 
            array (
                'id' => 53,
                'user_id' => 1,
                'follow_id' => 51,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            50 => 
            array (
                'id' => 54,
                'user_id' => 1,
                'follow_id' => 52,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            51 => 
            array (
                'id' => 55,
                'user_id' => 1,
                'follow_id' => 53,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            52 => 
            array (
                'id' => 56,
                'user_id' => 1,
                'follow_id' => 54,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            53 => 
            array (
                'id' => 57,
                'user_id' => 1,
                'follow_id' => 55,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            54 => 
            array (
                'id' => 58,
                'user_id' => 1,
                'follow_id' => 56,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            55 => 
            array (
                'id' => 59,
                'user_id' => 1,
                'follow_id' => 57,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            56 => 
            array (
                'id' => 60,
                'user_id' => 1,
                'follow_id' => 58,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            57 => 
            array (
                'id' => 61,
                'user_id' => 1,
                'follow_id' => 59,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            58 => 
            array (
                'id' => 62,
                'user_id' => 1,
                'follow_id' => 60,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            59 => 
            array (
                'id' => 63,
                'user_id' => 1,
                'follow_id' => 61,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            60 => 
            array (
                'id' => 64,
                'user_id' => 1,
                'follow_id' => 62,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            61 => 
            array (
                'id' => 65,
                'user_id' => 1,
                'follow_id' => 63,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            62 => 
            array (
                'id' => 66,
                'user_id' => 1,
                'follow_id' => 64,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            63 => 
            array (
                'id' => 67,
                'user_id' => 1,
                'follow_id' => 65,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            64 => 
            array (
                'id' => 68,
                'user_id' => 1,
                'follow_id' => 66,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            65 => 
            array (
                'id' => 69,
                'user_id' => 1,
                'follow_id' => 67,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            66 => 
            array (
                'id' => 70,
                'user_id' => 1,
                'follow_id' => 68,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            67 => 
            array (
                'id' => 71,
                'user_id' => 1,
                'follow_id' => 69,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            68 => 
            array (
                'id' => 72,
                'user_id' => 1,
                'follow_id' => 70,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            69 => 
            array (
                'id' => 73,
                'user_id' => 1,
                'follow_id' => 71,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            70 => 
            array (
                'id' => 74,
                'user_id' => 1,
                'follow_id' => 72,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            71 => 
            array (
                'id' => 75,
                'user_id' => 1,
                'follow_id' => 73,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            72 => 
            array (
                'id' => 76,
                'user_id' => 1,
                'follow_id' => 74,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            73 => 
            array (
                'id' => 77,
                'user_id' => 1,
                'follow_id' => 75,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            74 => 
            array (
                'id' => 78,
                'user_id' => 1,
                'follow_id' => 76,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            75 => 
            array (
                'id' => 79,
                'user_id' => 1,
                'follow_id' => 77,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            76 => 
            array (
                'id' => 80,
                'user_id' => 1,
                'follow_id' => 78,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            77 => 
            array (
                'id' => 81,
                'user_id' => 1,
                'follow_id' => 79,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            78 => 
            array (
                'id' => 82,
                'user_id' => 1,
                'follow_id' => 80,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            79 => 
            array (
                'id' => 83,
                'user_id' => 1,
                'follow_id' => 81,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            80 => 
            array (
                'id' => 84,
                'user_id' => 1,
                'follow_id' => 82,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            81 => 
            array (
                'id' => 85,
                'user_id' => 1,
                'follow_id' => 83,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            82 => 
            array (
                'id' => 86,
                'user_id' => 1,
                'follow_id' => 84,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            83 => 
            array (
                'id' => 87,
                'user_id' => 1,
                'follow_id' => 85,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            84 => 
            array (
                'id' => 88,
                'user_id' => 1,
                'follow_id' => 86,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            85 => 
            array (
                'id' => 89,
                'user_id' => 1,
                'follow_id' => 87,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            86 => 
            array (
                'id' => 90,
                'user_id' => 1,
                'follow_id' => 88,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            87 => 
            array (
                'id' => 91,
                'user_id' => 1,
                'follow_id' => 89,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            88 => 
            array (
                'id' => 92,
                'user_id' => 1,
                'follow_id' => 90,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            89 => 
            array (
                'id' => 93,
                'user_id' => 1,
                'follow_id' => 91,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            90 => 
            array (
                'id' => 94,
                'user_id' => 1,
                'follow_id' => 92,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            91 => 
            array (
                'id' => 95,
                'user_id' => 1,
                'follow_id' => 93,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            92 => 
            array (
                'id' => 96,
                'user_id' => 1,
                'follow_id' => 94,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            93 => 
            array (
                'id' => 97,
                'user_id' => 1,
                'follow_id' => 95,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            94 => 
            array (
                'id' => 98,
                'user_id' => 1,
                'follow_id' => 96,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            95 => 
            array (
                'id' => 99,
                'user_id' => 1,
                'follow_id' => 97,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            96 => 
            array (
                'id' => 100,
                'user_id' => 1,
                'follow_id' => 98,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            97 => 
            array (
                'id' => 101,
                'user_id' => 1,
                'follow_id' => 99,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            98 => 
            array (
                'id' => 102,
                'user_id' => 1,
                'follow_id' => 100,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            99 => 
            array (
                'id' => 103,
                'user_id' => 1,
                'follow_id' => 101,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ),
        ));
        
        
    }
}
