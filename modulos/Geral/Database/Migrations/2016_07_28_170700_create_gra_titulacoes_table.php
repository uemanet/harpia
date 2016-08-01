<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGraTitulacoesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gra_titulacoes', function (Blueprint $table) {
            $table->increments('tit_id');
            $table->string('tit_nome', 60);
            $table->string('tit_descricao', 150)->nullable();
            $table->smallInteger('tit_peso');

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
        Schema::drop('gra_titulacoes');
    }
}
