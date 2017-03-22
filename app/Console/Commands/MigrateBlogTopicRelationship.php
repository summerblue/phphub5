<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Topic;

class MigrateBlogTopicRelationship extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'topics:blog_topics';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '建立文章博客多对多连接！';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        Topic::where('category_id', config('phphub.blog_category_id'))->chunk(200, function ($topics) {
            foreach ($topics as $topic) {
                $blog = $topic->user->blogs()->first();

                if ( ! $blog->topics()->where('topic_id', $topic->id)->exists()) {
                    $blog->topics()->attach($topic->id);

                    if ( ! $blog->authors()->where('user_id', $topic->user_id)->exists()) {
                        $blog->authors()->attach($topic->user_id);
                    }
                }

                $this->info("处理完成：$topic->id");
            }
        });
    }
}
