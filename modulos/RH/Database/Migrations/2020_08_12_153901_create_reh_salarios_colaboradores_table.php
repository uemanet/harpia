<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRehSalariosColaboradoresTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reh_salarios_colaboradores', function (Blueprint $table) {
            $table->increments('scb_id');
            $table->integer('scb_ccb_id')->unsigned();
            $table->integer('scb_vfp_id')->unsigned();

            $table->integer('scb_unidade')->nullable();
            $table->binary('scb_valor');
            $table->binary('scb_valor_liquido');
            $table->date('scb_data_inicio');
            $table->date('scb_data_fim')->nullable();
            $table->date('scb_data_cadastro');
            $table->binary('scb_tag');

            $table->timestamps();

            $table->foreign('scb_ccb_id')->references('ccb_id')->on('reh_contas_colaboradores');
            $table->foreign('scb_vfp_id')->references('vfp_id')->on('reh_vinculos_fontes_pagadoras');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('reh_salarios_colaboradores');
    }
}
