<?php

use Illuminate\Database\Seeder;
use App\Models\Site;

class SitesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sites = factory(Site::class)->times(300)->make();
        Site::insert($sites->toArray());
    }
}
