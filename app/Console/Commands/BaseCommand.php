<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use Ubench;
use App;

class BaseCommand extends Command
{
    public $bench;

    public function __construct()
    {
        parent::__construct();

        $this->bench  = new Ubench;
        $this->bench->start();

        $this->is_ask = false;
    }

    public function printBenchInfo()
    {
        // 统计结束，可以打印出信息了
        $this->bench->end();

        $this->info('-------');
        $this->info('-------');
        $this->info(sprintf("Command execution completed, time consuming: %s, memory usage: %s ",
            $this->bench->getTime(),
            $this->bench->getMemoryUsage()
        ));
        $this->info('-------');
        $this->info('-------');
    }

    public function execShellWithPrettyPrint($command)
    {
        $this->info('---');
        $this->info($command);
        $output = shell_exec($command);
        $this->info($output);
        $this->info('---');
    }

    public function productionCheckHint($message = '')
    {
        $message = $message ?: 'This is a "very dangerous" operation';
        if (App::environment('production')
            && !$this->option('force')
            && !$this->confirm('Your are in「Production」environment, '.$message.'! Are you sure you want to do this? [y|N]')
        ) {
            exit('Command termination');
        }
    }
}
