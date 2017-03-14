<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAcdRegistrosTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('acd_registros', function (Blueprint $table) {
            $table->increments('reg_id');

            $table->integer('reg_liv_id')->unsigned();
            $table->integer('reg_mat_id')->unsigned();

            $table->integer('reg_folha');
            $table->integer('reg_registro');
            $table->string('reg_registro_externo')->nullable();
            $table->string('reg_processo')->nullable();
            $table->date('reg_data_expedicao');
            $table->text('reg_observacao')->nullable();
            $table->string('reg_usuario');
            $table->date('reg_data');
            $table->integer('reg_id_interno')->nullable();

            $table->foreign('reg_liv_id')->references('liv_id')->on('acd_livros');
            $table->foreign('reg_mat_id')->references('mat_id')->on('acd_matriculas');

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
        Schema::drop('acd_registros');
    }
}
