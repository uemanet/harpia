<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIntAmbientesServicosTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('int_ambientes_servicos', function (Blueprint $table) {

            $table->increments('asr_id');
            $table->integer('asr_amb_id')->unsigned();
            $table->integer('asr_ser_id')->unsigned();
            $table->string('asr_token', 255);

            $table->timestamps();

            $table->foreign('asr_amb_id')->references('amb_id')->on('int_ambientes_virtuais');
            $table->foreign('asr_ser_id')->references('ser_id')->on('int_servicos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('int_ambientes_servicos');
    }
}
