<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTagsTable extends Migration {

	public function up()
	{
		Schema::create(config('taggable.tags_table_name'), function(Blueprint $table) {
			$table->increments('id');
			$table->string('slug', 255)->unique();
			$table->string('name', 255)->unique();
			$table->text('description')->nullable();
			$table->boolean('suggest')->default(false);
			$table->integer('count')->unsigned()->default(0); // count of how many times this tag was used

			// For: Baum Nested Set
			// See: https://github.com/etrepat/baum#migration-configuration
			$table->integer('parent_id')->nullable();
			$table->integer('lft')->nullable();
			$table->integer('rgt')->nullable();
			$table->integer('depth')->nullable();
		});

		Schema::create(config('taggable.taggables_table_name'), function(Blueprint $table) {
			$table->increments('id');
			if(config('taggable.primary_keys_type') == 'string') {
				$table->string('taggable_id', 36)->index();
			} else {
				$table->integer('taggable_id')->unsigned()->index();
			}
			$table->string('taggable_type', 255)->index();
			$table->integer('tag_id')->unsigned()->index();

            $table->foreign('tag_id')
                ->references('id')->on(config('taggable.tags_table_name'))
                ->onDelete('cascade');
		});
	}

	public function down()
	{
		Schema::drop(config('taggable.tags_table_name'));
		Schema::drop(config('taggable.taggables_table_name'));
	}
}
