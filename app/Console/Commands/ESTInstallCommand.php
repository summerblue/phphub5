<?php namespace App\Console\Commands;

class ESTInstallCommand extends BaseCommand
{
    protected $signature = 'est:install {--force : enforce}';

    protected $description = 'Project Initialize Command';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $this->execShellWithPrettyPrint('php artisan key:generate');
        $this->execShellWithPrettyPrint('php artisan migrate --seed');
        $this->execShellWithPrettyPrint('php artisan est:init-rbac');
    }
}
