<?php

use Illuminate\Database\Seeder;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        $users = factory(User::class)->times(49)->make()->each(function ($user, $i) {
            if ($i == 0) {
                $user->name = 'admin';
                $user->email = 'admin@estgroupe.com';
            }

            $user->github_id = $i + 1;
        });

        User::insert($users->toArray());
    }
}
