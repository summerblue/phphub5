<?php

use Illuminate\Database\Seeder;

class HeatTopicsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \Artisan::call('phphub:calculate-heat-topic', []);
    }
}
