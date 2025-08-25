<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldCrsRegulamentacaoAcdCursos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('acd_cursos', function (Blueprint $table) {
            $table->string('crs_regulamentacao')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('acd_cursos', function (Blueprint $table) {
            $table->dropColumn('crs_regulamentacao');
        });
    }
}
