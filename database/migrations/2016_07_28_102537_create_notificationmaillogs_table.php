<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotificationMailLogsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notification_mail_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('from_user_id')->unsigned()->default(0)->index();
            $table->integer('user_id')->unsigned()->default(0)->index();
            $table->integer('topic_id')->unsigned()->default(0)->index();
            $table->integer('reply_id')->unsigned()->default(0)->index();
            $table->string('type')->index();
            $table->text('body')->nullable();
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
        Schema::drop('notification_mail_logs');
    }
}
