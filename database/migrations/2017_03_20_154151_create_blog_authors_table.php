<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBlogAuthorsTable extends Migration
{

    public function up()
    {
        Schema::create('blog_authors', function (Blueprint $table) {
            $table->integer('user_id')->unsigned()->index();
            $table->integer('blog_id')->unsigned()->index();

            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('blog_id')->references('id')->on('blogs')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::drop('blog_authors');
    }
}
