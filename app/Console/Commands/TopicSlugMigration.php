<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Topic;

class TopicSlugMigration extends Command
{
    protected $signature = 'topics:slug_migration';

    protected $description = '翻译所有的标题为 slug';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        Topic::chunk(200, function ($topics) {
            foreach ($topics as $topic) {
                $topic->slug = slug_trans($topic->title);
                $topic->save();
                $this->info("处理完成：$topic->id");
            }
        });

    }
}
