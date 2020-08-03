<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRehColaboradoresTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reh_colaboradores', function (Blueprint $table) {
            $table->increments('col_id');
//            $table->integer('col_set_id')->unsigned();
//            $table->integer('col_fun_id')->unsigned();
            $table->integer('col_pes_id')->unsigned();
            $table->integer('col_qtd_filho')->nullable();
            $table->date('col_data_admissao');
            $table->integer('col_ch_diaria');
            $table->string('col_codigo_catraca', 150)->nullable();
            $table->boolean('col_vinculo_universidade');
            $table->string('col_matricula_universidade', 150)->nullable();
            $table->longText('col_observacao')->nullable();
            $table->enum('col_status', [
                'ativo',
                'afastado',
                'desligado'
            ]);

            $table->timestamps();

            $table->foreign('col_pes_id')->references('pes_id')->on('gra_pessoas');
//            $table->foreign('col_set_id')->references('set_id')->on('rh_setores');
//            $table->foreign('col_fun_id')->references('fun_id')->on('rh_funcoes');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('reh_colaboradores');
    }
}
