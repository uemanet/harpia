<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAcdConfiguracoesCursosTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('acd_configuracoes_cursos', function (Blueprint $table) {
            $table->increments('cfc_id');
            $table->integer('cfc_crs_id')->unsigned();
            $table->string('cfc_nome');
            $table->string('cfc_valor');
            $table->timestamps();

            $table->foreign('cfc_crs_id')->references('crs_id')->on('acd_cursos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('acd_configuracoes_cursos');
    }
}
