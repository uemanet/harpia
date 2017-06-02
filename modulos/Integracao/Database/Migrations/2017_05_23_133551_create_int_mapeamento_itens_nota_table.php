<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIntMapeamentoItensNotaTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('int_mapeamento_itens_nota', function (Blueprint $table) {
            $table->increments('min_id');
            $table->integer('min_ofd_id')->unsigned();
            $table->integer('min_id_nota1')->nullable();
            $table->integer('min_id_nota2')->nullable();
            $table->integer('min_id_nota3')->nullable();
            $table->integer('min_id_recuperacao')->nullable();
            $table->integer('min_id_conceito')->nullable();
            $table->integer('min_id_final')->nullable();

            $table->timestamps();

            $table->foreign('min_ofd_id')->references('ofd_id')->on('acd_ofertas_disciplinas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('int_mapeamento_itens_nota');
    }
}
