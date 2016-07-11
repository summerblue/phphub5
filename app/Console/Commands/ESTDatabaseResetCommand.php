<?php namespace App\Console\Commands;

class ESTDatabaseResetCommand extends BaseCommand
{
    protected $signature = 'est:dbreset {--force : enforce}';

    protected $description = "Delete all tables（use est:dbnuke ）, and run the 'migrate' and 'db:seed' commands";

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $this->productionCheckHint("Will delete all tables, and run the 'migrate' and 'db:seed' commands");

        $this->call('est:dbnuke', [
            '--force' => 'yes'
        ]);

        $this->call('migrate', [
            '--seed'  => 'yes',
            '--force' => 'yes'
        ]);
    }
}
