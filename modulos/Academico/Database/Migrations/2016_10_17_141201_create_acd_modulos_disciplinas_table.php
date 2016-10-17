<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAcdModulosDisciplinasTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('acd_modulos_disciplinas', function (Blueprint $table) {
            $table->integer('mdc_dis_id')->unsigned();
            $table->integer('mdc_mdo_id')->unsigned();

            $table->foreign('poc_pol_id')->references('pol_id')->on('acd_polos');
            $table->foreign('poc_ofc_id')->references('ofc_id')->on('acd_ofertas_cursos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('acd_modulos_disciplinas');
    }
}
