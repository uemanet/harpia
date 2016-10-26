<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAcdOfertasCursosTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('acd_ofertas_cursos', function (Blueprint $table) {
            $table->increments('ofc_id');
            $table->integer('ofc_crs_id')->unsigned();
            $table->integer('ofc_mtc_id')->unsigned();
            $table->integer('ofc_mdl_id')->unsigned();
            $table->smallInteger('ofc_ano')->unsigned();

            $table->timestamps();

            $table->foreign('ofc_crs_id')->references('crs_id')->on('acd_cursos');
            $table->foreign('ofc_mtc_id')->references('mtc_id')->on('acd_matrizes_curriculares');
            $table->foreign('ofc_mdl_id')->references('mdl_id')->on('acd_modalidades');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('acd_ofertas_cursos');
    }
}
