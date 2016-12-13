<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAcdMatriculasOfertasDisciplinasTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('acd_matriculas_ofertas_disciplinas', function (Blueprint $table) {
            $table->increments('mof_id');
            $table->integer('mof_mat_id')->unsigned();
            $table->integer('mof_ofd_id')->unsigned();
            $table->enum('mof_tipo_matricula', ['matriculacomum', 'aproveitamentointerno', 'aproveitamentoexterno']);
            $table->float('mof_nota1')->nullable();
            $table->float('mof_nota2')->nullable();
            $table->float('mof_nota3')->nullable();
            $table->string('mof_conceito')->nullable();
            $table->float('mof_recuperacao')->nullable();
            $table->float('mof_final')->nullable();
            $table->float('mof_mediafinal')->nullable();
            $table->integer('mof_situacaomatricula')->unsigned()->nullable();
            $table->enum('mof_status', [
               'cursando',
                'cancelado'
            ]);

            $table->timestamps();

            $table->foreign('mof_mat_id')->references('mat_id')->on('acd_matriculas');
            $table->foreign('mof_ofd_id')->references('ofd_id')->on('acd_ofertas_disciplinas');
            $table->foreign('mof_situacaomatricula')->references('stm_id')->on('acd_situacoes_matricula_disciplina');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('acd_matriculas_ofertas_disciplinas');
    }
}
