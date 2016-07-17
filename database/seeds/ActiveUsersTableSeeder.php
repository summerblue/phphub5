<?php

use Illuminate\Database\Seeder;

class ActiveUsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \Artisan::call('phphub:calculate-active-user', []);
    }
}
