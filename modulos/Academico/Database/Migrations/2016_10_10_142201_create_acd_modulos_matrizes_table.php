<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAcdModulosMatrizesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('acd_modulos_matrizes', function (Blueprint $table) {
            $table->increments('mdo_id');
            $table->integer('mdo_mtc_id')->unsigned();
            $table->string('mdo_nome', 45);
            $table->string('mdo_descricao', 255);
            $table->string('mdo_qualificacao', 140);
            $table->integer('mdo_cargahoraria_min_eletivas')->nullable();
            $table->integer('mdo_creditos_min_eletivas')->nullable();
            $table->timestamps();

            $table->foreign('mdo_mtc_id')->references('mtc_id')->on('acd_matrizes_curriculares');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('acd_modulos_matrizes');
    }
}
