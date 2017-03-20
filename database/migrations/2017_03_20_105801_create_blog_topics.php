<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBlogTopics extends Migration
{
     public function up()
    {
        Schema::create('blog_topics', function (Blueprint $table) {
            $table->integer('blog_id')->unsigned()->index();
            $table->integer('topic_id')->unsigned()->index();

            $table->foreign('blog_id')->references('id')->on('blogs')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('topic_id')->references('id')->on('topics')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::drop('blog_topics');
    }
}
