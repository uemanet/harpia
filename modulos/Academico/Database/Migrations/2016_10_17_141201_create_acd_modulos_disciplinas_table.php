<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAcdModulosDisciplinasTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('acd_modulos_disciplinas', function (Blueprint $table) {
            $table->increments('mdc_id');
            $table->integer('mdc_dis_id')->unsigned();
            $table->integer('mdc_mdo_id')->unsigned();
            $table->enum('mdc_tipo_avaliacao', ['numerica', 'conceitual']);
            $table->enum('mdc_tipo_disciplina', ['obrigatoria', 'eletiva', 'optativa', 'tcc']);
            $table->json('mdc_pre_requisitos')->nullable();

            $table->timestamps();

            $table->foreign('mdc_dis_id')->references('dis_id')->on('acd_disciplinas');
            $table->foreign('mdc_mdo_id')->references('mdo_id')->on('acd_modulos_matrizes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('acd_modulos_disciplinas');
    }
}
