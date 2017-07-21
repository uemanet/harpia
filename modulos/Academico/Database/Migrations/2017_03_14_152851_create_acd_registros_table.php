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
            $table->integer('reg_usr_id')->unsigned();

            $table->integer('reg_folha');
            $table->integer('reg_registro');
            $table->string('reg_codigo_autenticidade', 255)->unique();

            $table->foreign('reg_liv_id')->references('liv_id')->on('acd_livros');
            $table->foreign('reg_usr_id')->references('usr_id')->on('seg_usuarios');

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
