<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAcdSituacoesMatriculaDisciplinaTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('acd_situacoes_matricula_disciplina', function (Blueprint $table) {
            $table->increments('stm_id');
            $table->string('stm_nome');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('acd_situacoes_matricula_disciplina');
    }
}
