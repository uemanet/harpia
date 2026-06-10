<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSymTentativasToIntSyncMoodle extends Migration
{
    public function up()
    {
        Schema::table('int_sync_moodle', function (Blueprint $table) {
            $table->integer('sym_tentativas')->default(0)->after('sym_version');
        });
    }

    public function down()
    {
        Schema::table('int_sync_moodle', function (Blueprint $table) {
            $table->dropColumn('sym_tentativas');
        });
    }
}
