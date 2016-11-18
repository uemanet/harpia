<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIntAmbientesTurmasTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('int_ambientes_turmas', function (Blueprint $table) {

            $table->increments('atr_id');
            $table->integer('atr_amb_id')->unsigned();
            $table->integer('atr_trm_id')->unsigned();

            $table->timestamps();

            $table->foreign('atr_amb_id')->references('amb_id')->on('int_ambientes_virtuais');
            $table->foreign('atr_trm_id')->references('trm_id')->on('acd_turmas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('int_ambientes_turmas');
    }
}
