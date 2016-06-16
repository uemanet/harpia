<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGraTitulacoesinformacoesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gra_titulacoesinformacoes', function (Blueprint $table) {
            $table->increments('tin_id');
            $table->integer('tin_tit_id')->unsigned();
            $table->integer('tit_codigo_externo');
            $table->string('tit_titulo',150);
            $table->string('tit_instituicao',150);
            $table->string('tit_instituicao_sigla',10);
            $table->string('tit_instituicao_sede',45);
            $table->integer('tit_anoinicio')->nullable();
            $table->integer('tit_anofim');

            $table->timestamps();

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
        Schema::drop('gra_titulacoesinformacoes');
    }
}
