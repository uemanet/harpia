<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAcdMatrizesCurricularesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('acd_matrizes_curriculares', function (Blueprint $table) {
            $table->increments('mtc_id');
            $table->integer('mtc_crs_id')->unsigned();
            $table->integer('mtc_anx_projeto_pedagogico')->unsigned();
            $table->string('mtc_descricao')->nullable();
            $table->date('mtc_data')->nullable();
            $table->smallInteger('mtc_creditos')->unsigned()->nullable();
            $table->smallInteger('mtc_horas')->unsigned()->nullable();
            $table->smallInteger('mtc_horas_praticas')->unsigned()->nullable();

            $table->timestamps();

            $table->foreign('mtc_crs_id')->references('crs_id')->on('acd_cursos');
            $table->foreign('mtc_anx_projeto_pedagogico')->references('anx_id')->on('gra_anexos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('acd_matrizes_curriculares');
    }
}
