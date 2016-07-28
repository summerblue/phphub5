<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\Inspire::class,

        Commands\ESTReinstallCommand::class,
        Commands\ESTInstallCommand::class,
        Commands\ESTDatabaseResetCommand::class,
        Commands\ESTDatabaseNukeCommand::class,
        Commands\ESTInitRBAC::class,

        Commands\CalculateActiveUser::class,
        Commands\CalculateHotTopic::class,
        Commands\ClearUserData::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('inspire')
                 ->hourly();

        $schedule->command('backup:run --only-db')->cron('0 */4 * * * *');
        $schedule->command('backup:clean')->daily()->at('00:10');

        $schedule->command('phphub:calculate-active-user')->everyTenMinutes();
        $schedule->command('phphub:calculate-hot-topic')->everyTenMinutes();
    }
}
