<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAcdNoticiasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('acd_noticias', function (Blueprint $table) {
            $table->increments('not_id');
            $table->integer('not_pes_id')->unsigned()->nullable();
            $table->text('not_titulo');
            $table->text('not_descricao');
            $table->text('not_corpo')->nullable();
            $table->text('not_link')->nullable();
            $table->timestamps();

            $table->foreign('not_pes_id')->references('pes_id')->on('gra_pessoas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('acd_noticias');
    }
}
