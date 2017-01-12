<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBlogsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('blogs', function(Blueprint $table) {
            $table->increments('id');
            $table->string('name')->index();
            $table->string('slug')->index();
            $table->text('description');
            $table->string('cover');
            $table->integer('user_id')->unsigned()->default(0)->index();
            $table->integer('article_count')->unsigned()->default(0);
            $table->integer('subscriber_count')->unsigned()->default(0);
            $table->tinyInteger('is_recommended')->unsigned()->default(0);
            $table->tinyInteger('is_blocked')->unsigned()->default(0);
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
		Schema::drop('blogs');
	}

}
