<?php

use Illuminate\Database\Seeder;

class HotTopicsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \Artisan::call('phphub:calculate-hot-topic', []);
    }
}
