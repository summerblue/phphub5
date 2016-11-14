<?php

use App\Models\User;
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

        $followers = [
            [
                'id'         => 4,
                'user_id'    => 1,
                'follow_id'  => 2,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ],
            [
                'id'         => 5,
                'user_id'    => 1,
                'follow_id'  => 3,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ],
            [
                'id'         => 6,
                'user_id'    => 1,
                'follow_id'  => 4,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ],
            [
                'id'         => 7,
                'user_id'    => 1,
                'follow_id'  => 5,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ],
            [
                'id'         => 8,
                'user_id'    => 1,
                'follow_id'  => 6,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ],
            [
                'id'         => 9,
                'user_id'    => 1,
                'follow_id'  => 7,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ],
            [
                'id'         => 10,
                'user_id'    => 1,
                'follow_id'  => 8,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ],
            [
                'id'         => 11,
                'user_id'    => 1,
                'follow_id'  => 9,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ],
            [
                'id'         => 12,
                'user_id'    => 1,
                'follow_id'  => 10,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ],
            [
                'id'         => 13,
                'user_id'    => 1,
                'follow_id'  => 11,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ],
            [
                'id'         => 14,
                'user_id'    => 1,
                'follow_id'  => 12,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ],
            [
                'id'         => 15,
                'user_id'    => 1,
                'follow_id'  => 13,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ],
            [
                'id'         => 16,
                'user_id'    => 1,
                'follow_id'  => 14,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ],
            [
                'id'         => 17,
                'user_id'    => 1,
                'follow_id'  => 15,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ],
            [
                'id'         => 18,
                'user_id'    => 1,
                'follow_id'  => 16,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ],
            [
                'id'         => 19,
                'user_id'    => 1,
                'follow_id'  => 17,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ],
            [
                'id'         => 20,
                'user_id'    => 1,
                'follow_id'  => 18,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ],
            [
                'id'         => 21,
                'user_id'    => 1,
                'follow_id'  => 19,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ],
            [
                'id'         => 22,
                'user_id'    => 1,
                'follow_id'  => 20,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ],
            [
                'id'         => 23,
                'user_id'    => 1,
                'follow_id'  => 21,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ],
            [
                'id'         => 24,
                'user_id'    => 1,
                'follow_id'  => 22,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ],
            [
                'id'         => 25,
                'user_id'    => 23,
                'follow_id'  => 1,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ],
            [
                'id'         => 26,
                'user_id'    => 24,
                'follow_id'  => 1,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ],
            [
                'id'         => 27,
                'user_id'    => 25,
                'follow_id'  => 1,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ],
            [
                'id'         => 28,
                'user_id'    => 26,
                'follow_id'  => 1,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ],
            [
                'id'         => 29,
                'user_id'    => 27,
                'follow_id'  => 1,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ],
            [
                'id'         => 30,
                'user_id'    => 28,
                'follow_id'  => 1,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ],
            [
                'id'         => 31,
                'user_id'    => 29,
                'follow_id'  => 1,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ],
            [
                'id'         => 32,
                'user_id'    => 30,
                'follow_id'  => 1,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ],
            [
                'id'         => 33,
                'user_id'    => 31,
                'follow_id'  => 1,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ],
            [
                'id'         => 34,
                'user_id'    => 32,
                'follow_id'  => 1,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ],
            [
                'id'         => 35,
                'user_id'    => 33,
                'follow_id'  => 1,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ],
            [
                'id'         => 36,
                'user_id'    => 34,
                'follow_id'  => 1,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ],
            [
                'id'         => 37,
                'user_id'    => 35,
                'follow_id'  => 1,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ],
            [
                'id'         => 38,
                'user_id'    => 36,
                'follow_id'  => 1,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ],
            [
                'id'         => 39,
                'user_id'    => 37,
                'follow_id'  => 1,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ],
            [
                'id'         => 40,
                'user_id'    => 38,
                'follow_id'  => 1,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ],
            [
                'id'         => 41,
                'user_id'    => 39,
                'follow_id'  => 1,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ],
            [
                'id'         => 42,
                'user_id'    => 40,
                'follow_id'  => 1,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ],
            [
                'id'         => 43,
                'user_id'    => 41,
                'follow_id'  => 1,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ],
            [
                'id'         => 44,
                'user_id'    => 42,
                'follow_id'  => 1,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ],
            [
                'id'         => 45,
                'user_id'    => 43,
                'follow_id'  => 1,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ],
            [
                'id'         => 46,
                'user_id'    => 44,
                'follow_id'  => 1,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ],
            [
                'id'         => 47,
                'user_id'    => 45,
                'follow_id'  => 1,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ],
            [
                'id'         => 48,
                'user_id'    => 46,
                'follow_id'  => 1,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ],
            [
                'id'         => 49,
                'user_id'    => 47,
                'follow_id'  => 1,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ],
            [
                'id'         => 50,
                'user_id'    => 48,
                'follow_id'  => 1,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ],
            [
                'id'         => 51,
                'user_id'    => 49,
                'follow_id'  => 1,
                'created_at' => '0000-00-00 00:00:00',
                'updated_at' => '0000-00-00 00:00:00',
            ],

        ];
        \DB::table('followers')->insert($followers);

        $follow_ids = array_unique(array_column($followers, 'follow_id'));
        foreach ($follow_ids as $follow_id) {
            /** @var User $user */
            $user = User::find($follow_id);
            $user->update(['follower_count' => $user->followers()->count()]);
        }
    }
}
