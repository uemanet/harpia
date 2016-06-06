<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSegCategoriasRecursosTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seg_categorias_recursos', function(Blueprint $table)
        {
            $table->increments('ctr_id');
            $table->integer('ctr_mod_id')->unsigned();
            $table->string('ctr_nome', 45);
            $table->string('ctr_icone', 45);
            $table->integer('ctr_ordem');
            $table->boolean('ctr_ativo')->default(1);
            $table->boolean('ctr_visivel')->default(1);
            $table->integer('ctr_referencia')->unsigned()->nullable();
            $table->timestamps();

            $table->foreign('ctr_referencia')->references('ctr_id')->on('seg_categorias_recursos');
            $table->foreign('ctr_mod_id')->references('mod_id')->on('seg_modulos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('seg_categorias_recursos');
    }

}