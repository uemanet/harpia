<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRehColaboradoresFuncoesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reh_colaboradores_funcoes', function (Blueprint $table) {
            $table->increments('cfn_id');
            $table->integer('cfn_set_id')->unsigned();
            $table->integer('cfn_fun_id')->unsigned();
            $table->integer('cfn_col_id')->unsigned();
            $table->date('cfn_data_inicio')->nullable();
            $table->date('cfn_data_fim')->nullable();

            $table->timestamps();

            $table->foreign('cfn_set_id')->references('set_id')->on('reh_setores');
            $table->foreign('cfn_fun_id')->references('fun_id')->on('reh_funcoes');
            $table->foreign('cfn_col_id')->references('col_id')->on('reh_colaboradores');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('reh_colaboradores_funcoes');
    }
}
