<?php

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        $password = bcrypt('secret');
        $users = factory(User::class)->times(49)->make()->each(function ($user, $i) use ($password)  {
            if ($i == 0) {
                $user->name = 'admin';
                $user->email = 'admin@estgroupe.com';
                $user->github_name = 'admin';
                $user->verified = 1;
            }
            $user->password = $password;
            $user->github_id = $i + 1;
        });

        User::insert($users->toArray());

        $hall_of_fame = Role::addRole('HallOfFame', '名人堂');
        $users = User::all();
        foreach ($users as $key => $user) {
            $user->attachRole($hall_of_fame);
        }

    }
}
