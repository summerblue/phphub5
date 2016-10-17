<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMaintainerLogs extends Migration
{

    public function up()
    {
        Schema::create('maintainer_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamp('start_time');
            $table->timestamp('end_time');
            $table->integer('user_id')->unsigned()->index();
            $table->integer('topics_count')->unsigned();
            $table->integer('replies_count')->unsigned();
            $table->integer('excellent_count')->unsigned();
            $table->integer('sink_count')->unsigned();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::drop('maintainer_logs');
    }
}
