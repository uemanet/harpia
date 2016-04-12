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
            $table->string('ctr_nome', 45);
            $table->string('ctr_icone', 45);
            $table->integer('ctr_ordem');
            $table->boolean('ctr_ativo')->default(1);
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
        Schema::drop('seg_categorias_recursos');
    }

}
