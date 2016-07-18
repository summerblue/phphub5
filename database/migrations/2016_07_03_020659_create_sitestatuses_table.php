<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSiteStatusesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('site_statuses', function (Blueprint $table) {
            $table->increments('id');
            $table->string('day')->index();
            $table->integer('register_count')->unsigned()->default(0);
            $table->integer('github_regitster_count')->unsigned()->default(0);
            $table->integer('wechat_registered_count')->unsigned()->default(0);
            $table->tinyInteger('topic_count')->unsigned()->default(0);
            $table->integer('reply_count')->unsigned()->default(0);
            $table->integer('image_count')->unsigned()->default(0);
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
        Schema::drop('site_statuses');
    }
}
