<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAcdAlunosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('acd_alunos', function (Blueprint $table) {
            $table->increments('alu_id');
            $table->integer('alu_pes_id')->unsigned();

            $table->timestamps();

            $table->foreign('alu_pes_id')->references('pes_id')->on('gra_pessoas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('acd_alunos');
    }
}