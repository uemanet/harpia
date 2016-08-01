<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAcdTutoresTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('acd_tutores', function (Blueprint $table) {
            $table->increments('tut_id');
            $table->integer('tut_pes_id')->unsigned();

            $table->timestamps();

            $table->foreign('tut_pes_id')->references('pes_id')->on('gra_pessoas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('acd_tutores');
    }
}
