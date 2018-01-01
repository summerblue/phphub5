<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class ClearDownloadDatas extends Command
{

    protected $signature = 'phphub:clear-download-data';


    protected $description = 'Clear user download datas';


    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        Storage::disk('local')->deleteDirectory('zip');
    }
}
