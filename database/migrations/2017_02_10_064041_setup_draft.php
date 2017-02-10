<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SetupDraft extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('draft_count')->unsigned()->default(0);
        });
        Schema::table('topics', function (Blueprint $table) {
            $table->enum('is_draft', ['yes',  'no'])->default('no')->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('draft_count');
        });
        Schema::table('topics', function (Blueprint $table) {
            $table->dropColumn('is_draft');
        });

    }
}
