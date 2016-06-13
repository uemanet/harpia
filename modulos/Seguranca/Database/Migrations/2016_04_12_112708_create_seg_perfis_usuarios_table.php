<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSegPerfisUsuariosTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seg_perfis_usuarios', function (Blueprint $table) {
            $table->integer('pru_prf_id')->unsigned();
            $table->integer('pru_usr_id')->unsigned();

            $table->primary(['pru_prf_id', 'pru_usr_id']);

            $table->foreign('pru_usr_id')->references('usr_pes_id')->on('seg_usuarios');
            $table->foreign('pru_prf_id')->references('prf_id')->on('seg_perfis');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('seg_perfis_usuarios');
    }
}
