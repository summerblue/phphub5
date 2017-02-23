<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTopicsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('topics', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->index();
            $table->string('source')->nullable()->index(); // 来源关注：iOS，Android
            $table->text('body');
            $table->integer('user_id')->unsigned()->default(0)->index();
            $table->integer('category_id')->unsigned()->default(0)->index();
            $table->integer('reply_count')->default(0)->index();
            $table->integer('view_count')->unsigned()->default(0)->index();
            $table->integer('vote_count')->default(0)->index();
            $table->integer('last_reply_user_id')->unsigned()->default(0)->index();
            $table->integer('order')->default(0)->index();
            $table->enum('is_excellent', ['yes',  'no'])->default('no')->index();
            $table->enum('is_blocked', ['yes',  'no'])->default('no')->index();
            $table->text('body_original')->nullable();
            $table->text('excerpt')->nullable();
            $table->enum('is_tagged', ['yes',  'no'])->default('no')->index();
            $table->softDeletes();
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
        Schema::drop('topics');
    }
}
