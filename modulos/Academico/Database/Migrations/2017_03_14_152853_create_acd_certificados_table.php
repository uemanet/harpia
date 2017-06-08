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
            $table->increments('dip_id');

            $table->integer('dip_reg_id')->unsigned();
            $table->integer('dip_mat_id')->unsigned();

            $table->string('dip_processo');
            $table->string('dip_codigo_autenticidade_externo');

            $table->foreign('dip_liv_id')->references('liv_id')->on('acd_livros');
            $table->foreign('dip_usr_id')->references('usr_id')->on('seg_usuarios');

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
