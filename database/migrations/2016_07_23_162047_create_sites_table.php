<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatesitesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('sites', function(Blueprint $table) {
            $table->increments('id');
            $table->string('title')->index();
            $table->string('description')->nullable();
            $table->string('type')->index()->default('site');
            $table->string('link')->index();
            $table->string('favicon')->nullable();
            $table->integer('order')->default(0);
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
		Schema::drop('sites');
	}

}
