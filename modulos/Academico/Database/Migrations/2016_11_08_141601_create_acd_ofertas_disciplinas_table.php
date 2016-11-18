<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAcdOfertasDisciplinasTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('acd_ofertas_disciplinas', function (Blueprint $table) {
            $table->increments('ofd_id');
            $table->integer('ofd_mdc_id')->unsigned();
            $table->integer('ofd_trm_id')->unsigned();
            $table->integer('ofd_per_id')->unsigned();
            $table->integer('ofd_prf_id')->unsigned()->nulllable();
            $table->smallInteger('ofd_qtd_vagas')->unsigned();

            $table->timestamps();

            $table->foreign('ofd_mdc_id')->references('mdc_id')->on('acd_modulos_disciplinas');
            $table->foreign('ofd_trm_id')->references('trm_id')->on('acd_turmas');
            $table->foreign('ofd_per_id')->references('per_id')->on('acd_periodos_letivos');
            $table->foreign('ofd_prf_id')->references('prf_id')->on('acd_professores');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('acd_ofertas_disciplinas');
    }
}
