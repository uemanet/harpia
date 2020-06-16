<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldSymPluginIntSyncMoodle extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('int_sync_moodle', function (Blueprint $table) {
            $table->enum('sym_version', ['v1', 'v2']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('int_sync_moodle', function (Blueprint $table) {
            $table->dropColumn('sym_version');
        });
    }
}