<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterAcdMatrizesCurricularesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('acd_matrizes_curriculares', function (Blueprint $table) {
            $table->integer('mtc_anx_projeto_pedagogico')->nullable()->unsigned()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('acd_matrizes_curriculares', function (Blueprint $table) {
            $table->dropColumn('mtc_anx_projeto_pedagogico');
        });
    }
}
