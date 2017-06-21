<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAcdCertificadosTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('acd_certificados', function (Blueprint $table) {
            $table->increments('crt_id');

            $table->integer('crt_reg_id')->unsigned();
            $table->integer('crt_mat_id')->unsigned();
            $table->integer('crt_mdo_id')->unsigned();

            $table->foreign('crt_reg_id')->references('reg_id')->on('acd_registros');
            $table->foreign('crt_mat_id')->references('mat_id')->on('acd_matriculas');
            $table->foreign('crt_mdo_id')->references('mdo_id')->on('acd_modulos_matrizes');

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
        Schema::drop('acd_certificados');
    }
}
