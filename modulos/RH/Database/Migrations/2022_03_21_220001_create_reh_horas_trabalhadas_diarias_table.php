<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRehHorasTrabalhadasDiariasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reh_horas_trabalhadas_diarias', function (Blueprint $table) {
            $table->increments('htd_id');
            $table->integer('htd_col_id')->unsigned();
            $table->time('htd_horas');
            $table->date('htd_data');
            $table->timestamps();
            $table->foreign('htd_col_id')->references('col_id')->on('reh_colaboradores');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('reh_horas_trabalhadas_diarias');
    }
}
