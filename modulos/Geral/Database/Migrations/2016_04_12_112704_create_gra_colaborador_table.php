<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGraColaboradorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gra_colaborador', function (Blueprint $table) {
            $table->integer('col_pes_id')->unsigned();
            $table->integer('col_fun_id')->unsigned();
            $table->integer('col_set_id')->unsigned();
            $table->string('col_matricula', 20)->nullable();
            $table->date('col_data_admissao');
            $table->timestamps();

            $table->primary('col_pes_id');
            $table->foreign('col_pes_id')->references('pes_id')->on('gra_pessoa');
            $table->foreign('col_fun_id')->references('fun_id')->on('gra_funcao');
            $table->foreign('col_set_id')->references('set_id')->on('gra_setor');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('gra_colaborador');
    }
}