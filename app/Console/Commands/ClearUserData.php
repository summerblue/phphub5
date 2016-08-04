<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Topic;
use App\Models\Reply;
use App\Models\Notification;
use App\Models\Vote;
use App\Models\ActiveUser;

class ClearUserData extends Command
{

    protected $signature = 'phphub:clear-user-data {user_id}';


    protected $description = 'Clear user data
                            {user_id : User ID }
                            ';


    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $user_id = $this->argument('user_id');

        Topic::where('user_id', $user_id)->delete();
        Reply::where('user_id', $user_id)->delete();
        Notification::where('user_id', $user_id)->delete();
        Vote::where('user_id', $user_id)->delete();
        ActiveUser::where('user_id', $user_id)->delete();
    }
}
