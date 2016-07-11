<?php namespace App\Console\Commands;

use DB;

class ESTDatabaseNukeCommand extends BaseCommand
{
    protected $signature = 'est:dbnuke {--force : enforce}';

    protected $description = 'Delete all tables';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $this->productionCheckHint();

        $colname = 'Tables_in_' . env('DB_DATABASE');

        $tables = DB::select('SHOW TABLES');

        foreach ($tables as $table) {
            $droplist[] = $table->$colname;
            $this->info('Will delete table - ' . $table->$colname);
        }
        if (!isset($droplist)) {
            $this->error('No table');
            return;
        }
        $droplist = implode(',', $droplist);

        DB::beginTransaction();
        //turn off referential integrity
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        DB::statement("DROP TABLE $droplist");
        //turn referential integrity back on
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
        DB::commit();

        $this->comment("All the tables have been deleted".PHP_EOL);
    }
}
