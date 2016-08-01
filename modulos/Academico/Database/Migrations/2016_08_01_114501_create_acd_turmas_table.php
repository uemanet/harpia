<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAcdTurmasTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('acd_turmas', function (Blueprint $table) {
            $table->increments('trm_id');
            $table->integer('trm_ofc_id')->unsigned();
            $table->integer('trm_per_id')->unsigned();
            $table->string('trm_nome', 45);
            $table->smallInteger('trm_qtd_vagas')->unsigned();

            $table->timestamps();

            $table->foreign('trm_ofc_id')->references('ofc_id')->on('acd_ofertas_cursos');
            $table->foreign('trm_per_id')->references('per_id')->on('acd_periodos_letivos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('acd_turmas');
    }
}
