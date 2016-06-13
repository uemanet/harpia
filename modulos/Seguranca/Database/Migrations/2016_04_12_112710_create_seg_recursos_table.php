<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSegRecursosTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seg_recursos', function (Blueprint $table) {
            $table->increments('rcs_id');
            $table->integer('rcs_ctr_id')->unsigned();
            $table->string('rcs_nome', 150);
            $table->string('rcs_descricao', 300);
            $table->string('rcs_icone', 45)->nullable();
            $table->boolean('rcs_ativo')->default(1);
            $table->integer('rcs_ordem');
            $table->timestamps();

            $table->foreign('rcs_ctr_id')->references('ctr_id')->on('seg_categorias_recursos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('seg_recursos');
    }
}
