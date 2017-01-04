<?php namespace App\Console\Commands;

class ESTReinstallCommand extends BaseCommand
{
    protected $signature = 'est:reinstall {--force : enforce}';

    protected $description = "Reset database, reset RABC. Only use in local environment";

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {

        $this->productionCheckHint('Reset database and reset RABC');

        // fixing db:seed class not found
        $this->execShellWithPrettyPrint('composer dump');

        $this->execShellWithPrettyPrint('php artisan est:dbreset --force');
        $this->execShellWithPrettyPrint('php artisan est:init-rbac');

        $this->execShellWithPrettyPrint('php artisan cache:clear');

        $this->printBenchInfo();
    }
}
