<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGraTitulacoesInformacoesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gra_titulacoes_informacoes', function (Blueprint $table) {
            $table->increments('tin_id');
            $table->integer('tin_pes_id')->unsigned();
            $table->integer('tin_tit_id')->unsigned();
            $table->string('tin_titulo', 150);
            $table->string('tin_instituicao', 150);
            $table->string('tin_instituicao_sigla', 10);
            $table->string('tin_instituicao_sede', 45);
            $table->smallInteger('tin_anoinicio');
            $table->smallInteger('tin_anofim')->nullable();

            $table->timestamps();

            $table->foreign('tin_pes_id')->references('pes_id')->on('gra_pessoas');
            $table->foreign('tin_tit_id')->references('tit_id')->on('gra_titulacoes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('gra_titulacoes_informacoes');
    }
}
