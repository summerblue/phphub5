<?php

use Illuminate\Database\Seeder;

class OauthClientsTableSeeder extends Seeder
{
    public function run()
    {
        \DB::table('oauth_clients')->delete();

        \DB::table('oauth_clients')->insert([
            [
                'id' => '14n5UsWFzhrt8iPx82wz',
                'secret' => 'j48EpY29pF06i7cAhEx6dgSTLD7',
                'name' => 'Test Drive',
                'user_id' => '1',
                'created_at' => '2014-10-12 08:29:15',
                'updated_at' => '2014-10-31 06:01:20',
            ],
        ]);
    }
}
