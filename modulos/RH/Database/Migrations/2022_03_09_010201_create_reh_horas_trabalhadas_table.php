<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRehHorasTrabalhadasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reh_horas_trabalhadas', function (Blueprint $table) {
            $table->increments('htr_id');
            $table->integer('htr_col_id')->unsigned();
            $table->integer('htr_pel_id')->unsigned();
            $table->time('htr_horas_previstas');
            $table->time('htr_horas_trabalhadas');
            $table->time('htr_horas_justificadas');
            $table->time('htr_saldo');
            $table->timestamps();
            $table->foreign('htr_col_id')->references('col_id')->on('reh_colaboradores');
            $table->foreign('htr_pel_id')->references('pel_id')->on('reh_periodos_laborais');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('reh_horas_trabalhadas');
    }
}
