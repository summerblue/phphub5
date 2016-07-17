<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHotTopics extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hot_topics', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('topic_id')->default(0)->index();
            $table->integer('vote_count')->default(0);
            $table->integer('reply_count')->default(0);
            $table->integer('weight')->default(0)->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('hot_topics');
    }
}
