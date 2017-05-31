<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAcdLancamentosTccsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('acd_lancamentos_tccs', function (Blueprint $table) {
            $table->increments('ltc_id');
            $table->integer('ltc_mof_id')->unsigned();
            $table->integer('ltc_prf_id')->unsigned();
            $table->integer('ltc_anx_tcc')->unsigned()->nullable();
            $table->string('ltc_titulo', 45);
            $table->enum('ltc_tipo', [
                'artigo',
                'monografia',
                'estudo_de_caso',
                'revisao_de_bibliografia',
                'pesquisa_de_recepcao',
                'projeto_arquitetonico_urbanistico',
                'plano_de_negocio'
            ]);
            $table->date('ltc_data_apresentacao');
            $table->string('ltc_observacao', 150)->nullable();

            $table->timestamps();

            $table->foreign('ltc_mof_id')->references('mof_id')->on('acd_matriculas_ofertas_disciplinas');
            $table->foreign('ltc_prf_id')->references('prf_id')->on('acd_professores');
            $table->foreign('ltc_anx_tcc')->references('anx_id')->on('gra_anexos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('acd_lancamentos_tccs');
    }
}
