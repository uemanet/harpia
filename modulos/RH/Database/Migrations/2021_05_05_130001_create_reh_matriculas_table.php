<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRehMatriculasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reh_matriculas', function (Blueprint $table) {
            $table->increments('mtc_id');
            $table->integer('mtc_col_id')->unsigned();
            $table->date('mtc_data_inicio');
            $table->date('mtc_data_fim')->nullable();

            $table->timestamps();

            $table->foreign('mtc_col_id')->references('col_id')->on('reh_colaboradores');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('reh_matriculas');
    }
}
