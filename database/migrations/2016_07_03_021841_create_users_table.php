<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('github_id')->index();
            $table->string('github_url');
            $table->string('email')->nullable()->index();
            $table->string('name')->nullable()->index();
            $table->string('login_token')->nullable();
            $table->string('remember_token')->nullable();
            $table->enum('is_banned', ['yes',  'no'])->default('no')->index();
            $table->string('image_url')->nullable();
            $table->integer('topic_count')->default(0)->index();
            $table->integer('reply_count')->default(0)->index();
            $table->string('city')->nullable();
            $table->string('company')->nullable();
            $table->string('twitter_account')->nullable();
            $table->string('personal_website')->nullable();
            $table->string('introduction')->nullable();
            $table->integer('notification_count')->default(0);
            $table->string('github_name')->index();
            $table->string('real_name')->nullable();
            $table->string('avatar');
            $table->string('login_qr');
            $table->string('wechat_id')->nullable();
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
        Schema::drop('users');
    }
}
