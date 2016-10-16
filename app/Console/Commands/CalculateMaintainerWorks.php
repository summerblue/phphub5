<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Models\Role;
use App\Models\User;
use App\Models\MaintainerLog;

class CalculateMaintainerWorks extends Command
{

    protected $signature = 'phphub:calculate-maintainer-works';
    protected $description = 'Calculate maintainer works';


    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $start_time = Carbon::now()->subDays(7)->toDateString();
        $end_time = Carbon::now()->toDateString();

        $role = Role::find(2);
        $maintainers = User::byRolesName($role->name);

        foreach ($maintainers as $user) {
            $data = [];

            $topics_count = $user->topics()
                                 ->whereBetween('created_at', [$start_time, $end_time])
                                 ->count();

            $replies_count = $user->replies()
                                  ->whereBetween('created_at', [$start_time, $end_time])
                                  ->count();

            $excellent_count = $user->revisions()
                                    ->where('key', 'is_excellent')
                                    ->whereBetween('created_at', [$start_time, $end_time])
                                    ->get();

            $sink_count = $user->revisions()
                               ->where('key', 'order')
                               ->whereBetween('created_at', [$start_time, $end_time])
                               ->count();

            $data = compact('topics_count', 'replies_count', 'excellent_count', 'sink_count');
            $data['user_id'] = $user->id;
            $data['start_time'] = $start_time;
            $data['end_time'] = $end_time;
            MaintainerLog::create($data);
        }

        $this->info('Done');
    }
}
