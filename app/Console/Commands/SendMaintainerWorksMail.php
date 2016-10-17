<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Role;
use App\Models\MaintainerLog;

class SendMaintainerWorksMail extends Command
{

    protected $signature = 'phphub:send-maintainer-works-mail {start_time} {end_time}';
    protected $description = 'Send maintainer works mail';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $start_time = $this->argument('start_time');
        $end_time = $this->argument('end_time');
        $logs = MaintainerLog::where('start_time', $start_time)
                             ->where('end_time', $end_time)
                             ->get();
        if (!$logs) {
            return 'No data';
        }

        $content = $this->generateContent($logs);
        $timeFrame = "{$start_time} 至 {$end_time}";

        $founders = User::byRolesName(Role::find(1)->name);
        $maintainer = User::byRolesName(Role::find(2)->name);
        $users = $founders->merge($maintainer);

        foreach ($users as $user) {
            dispatch(new \App\Jobs\SendMaintainerWorksMail($user, $timeFrame, $content));
        }

        $this->info('Done');
    }

    protected function generateContent($logs)
    {
        $content = "<table style=\"box-sizing: border-box; border-collapse: collapse; border-spacing: 0px; margin-top: 0px; margin-bottom: 16px; display: block; width: 637px; overflow: auto; word-break: keep-all; font-family: 'Helvetica Neue', Helvetica, 'Segoe UI', Arial, freesans, sans-serif; font-size: 16px;\">
                    <thead style=\"box-sizing: border-box;\">
                        <tr style=\"box-sizing: border-box; border-top-width: 1px; border-top-style: solid; border-top-color: rgb(204, 204, 204);\">
                            <th style=\"box-sizing: border-box; padding: 6px 13px; border: 1px solid rgb(221, 221, 221);\">用户</th>
                            <th style=\"box-sizing: border-box; padding: 6px 13px; border: 1px solid rgb(221, 221, 221); text-align: center;\">发帖数</th>
                            <th style=\"box-sizing: border-box; padding: 6px 13px; border: 1px solid rgb(221, 221, 221); text-align: right;\">回复数</th>
                        </tr>
                    </thead>
                    <tbody style=\"box-sizing: border-box;\">";
        foreach ($logs as $index => $log) {
            $bgColor = $index % 2 != 0 ? 'background-color: rgb(248, 248, 248);': '';
            $content .= "<tr style=\"box-sizing: border-box; border-top-width: 1px; border-top-style: solid; border-top-color: rgb(204, 204, 204);" . $bgColor . "\">";

            $tdStyle = 'box-sizing: border-box; padding: 6px 13px; border: 1px solid rgb(221, 221, 221); text-align: center;';
            $content .= "<td style=\"{$tdStyle}\"><a href=\"".route('users.show', $log->user_id)."\" target=\"_blank\">{$log->user->name}</a></td>";
            $content .= "<td style=\"{$tdStyle}\">{$log->topics_count}</td>";
            $content .= "<td style=\"{$tdStyle}\">{$log->replies_count}</a></td>";

            $content .= "</tr>";
        }
        $content .= '</tbody></table>';

        return $content;
    }
}
