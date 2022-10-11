<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRehJustificativasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reh_justificativas', function (Blueprint $table) {
            $table->increments('jus_id');
            $table->integer('jus_anx_id')->unsigned()->nullable();
            $table->integer('jus_htr_id')->unsigned();
            $table->integer('jus_horas')->unsigned();
            $table->text('jus_descricao');
            $table->date('jus_data');
            $table->timestamps();
            $table->foreign('jus_htr_id')->references('htr_id')->on('reh_horas_trabalhadas');
            $table->foreign('jus_anx_id')->references('anx_id')->on('gra_anexos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('reh_justificativas');
    }
}
