<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterAcdDiplomasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('acd_diplomas', function (Blueprint $table) {
            $table->string('dip_processo')->nullable()->change();
            $table->string('dip_codigo_autenticidade_externo')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('acd_diplomas', function (Blueprint $table) {
            $table->dropColumn('dip_processo');
            $table->dropColumn('dip_codigo_autenticidade_externo');
        });
    }
}
