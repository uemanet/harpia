<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAcdMatriculasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('acd_matriculas', function (Blueprint $table) {
            $table->increments('mat_id');
            $table->integer('mat_alu_id')->unsigned();
            $table->integer('mat_trm_id')->unsigned();
            $table->integer('mat_pol_id')->unsigned()->nullable();
            $table->integer('mat_grp_id')->unsigned()->nullable();
            $table->enum('mat_situacao', [
                'cursando',
                'concluido',
                'reprovado',
                'evadido',
                'trancado',
                'transferencia externa',
                'transferencia interna para',
                'transferencia interna de',
                'desistente'
            ]);
            $table->date('mat_data_conclusao')->nullable();

            $table->timestamps();

            $table->foreign('mat_alu_id')->references('alu_id')->on('acd_alunos');
            $table->foreign('mat_trm_id')->references('trm_id')->on('acd_turmas');
            $table->foreign('mat_pol_id')->references('pol_id')->on('acd_polos');
            $table->foreign('mat_grp_id')->references('grp_id')->on('acd_grupos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('acd_matriculas');
    }
}