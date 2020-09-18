<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRehFontesPagadorasTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reh_fontes_pagadoras', function (Blueprint $table) {
            $table->increments('fpg_id');
            $table->string('fpg_razao_social', 150);
            $table->string('fpg_nome_fantasia', 150);
            $table->string('fpg_cnpj', 19);
            $table->string('fpg_cep', 10)->nullable();
            $table->string('fpg_endereco')->nullable();
            $table->string('fpg_bairro', 150)->nullable();
            $table->string('fpg_numero', 45)->nullable();
            $table->string('fpg_complemento', 150)->nullable();
            $table->string('fpg_cidade', 150)->nullable();
            $table->char('fpg_uf', 2)->nullable();
            $table->string('fpg_email', 150)->unique()->nullable();
            $table->string('fpg_telefone', 15)->nullable();
            $table->string('fpg_celular', 15)->nullable();
            $table->longText('fpg_observacao')->nullable();

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
        Schema::drop('reh_fontes_pagadoras');
    }
}
