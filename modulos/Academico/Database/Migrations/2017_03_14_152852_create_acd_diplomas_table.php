<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAcdDiplomasTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('acd_diplomas', function (Blueprint $table) {
            $table->increments('dip_id');

            $table->integer('dip_reg_id')->unsigned();
            $table->integer('dip_mat_id')->unsigned();

            $table->string('dip_processo');
            $table->string('dip_codigo_autenticidade_externo');

            $table->foreign('dip_reg_id')->references('reg_id')->on('acd_registros');
            $table->foreign('dip_mat_id')->references('mat_id')->on('acd_matriculas');

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
        Schema::drop('acd_diplomas');
    }
}
