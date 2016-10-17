<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAcdUsuariosCursosTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('acd_usuarios_cursos', function (Blueprint $table) {
            $table->increments('ucr_id');
            $table->integer('ucr_usr_id')->unsigned();
            $table->integer('ucr_crs_id')->unsigned();

            $table->timestamps();

            $table->foreign('ucr_usr_id')->references('usr_id')->on('seg_usuarios');
            $table->foreign('ucr_crs_id')->references('crs_id')->on('acd_cursos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('acd_usuarios_cursos');
    }
}
