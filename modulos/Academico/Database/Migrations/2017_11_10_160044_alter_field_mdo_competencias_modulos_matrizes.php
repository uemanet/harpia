<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterFieldMdoCompetenciasModulosMatrizes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('acd_modulos_matrizes', function (Blueprint $table) {
            $table->text('mdo_competencias')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('acd_modulos_matrizes', function (Blueprint $table) {
            $table->dropColumn('mdo_competencias');
        });
    }
}
