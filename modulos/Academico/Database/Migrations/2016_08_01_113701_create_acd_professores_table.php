<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAcdProfessoresTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('acd_professores', function (Blueprint $table) {
            $table->increments('prf_id');
            $table->integer('prf_pes_id')->unsigned();

            $table->timestamps();

            $table->foreign('prf_pes_id')->references('pes_id')->on('gra_pessoas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('acd_professores');
    }
}
