<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAcdPessoasCursosTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('acd_pessoas_cursos', function (Blueprint $table) {
            $table->integer('apc_pes_id')->unsigned();
            $table->integer('apc_crs_id')->unsigned();

            $table->foreign('apc_pes_id')->references('pes_id')->on('gra_pessoas');
            $table->foreign('apc_crs_id')->references('crs_id')->on('acd_cursos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('acd_pessoas_cursos');
    }
}
