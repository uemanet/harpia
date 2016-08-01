<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAcdCursosTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('acd_cursos', function (Blueprint $table) {
            $table->increments('crs_id');
            $table->integer('crs_dep_id')->unsigned();
            $table->integer('crs_nvc_id')->unsigned();
            $table->integer('crs_prf_diretor')->unsigned();
            $table->string('crs_nome', 45);
            $table->string('crs_sigla', 10);
            $table->string('crs_descricao')->nullable();
            $table->string('crs_resolucao')->nullable();
            $table->string('crs_autorizacao')->nullable();
            $table->date('crs_data_autorizacao')->nullable();
            $table->string('crs_eixo', 150)->nullable();
            $table->string('crs_habilitacao', 150)->nullable();


            $table->foreign('crs_dep_id')->references('dep_id')->on('acd_departamentos');
            $table->foreign('crs_nvc_id')->references('nvc_id')->on('acd_niveis_cursos');
            $table->foreign('crs_prf_diretor')->references('prf_id')->on('acd_professores');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('acd_cursos');
    }
}
