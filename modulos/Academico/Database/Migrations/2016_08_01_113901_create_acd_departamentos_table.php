<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAcdDepartamentosTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('acd_departamentos', function (Blueprint $table) {
            $table->increments('dep_id');
            $table->integer('dep_cen_id')->unsigned();
            $table->integer('dep_prf_diretor')->unsigned();
            $table->string('dep_nome', 45);

            $table->foreign('dep_cen_id')->references('cen_id')->on('acd_centros');
            $table->foreign('dep_prf_diretor')->references('prf_id')->on('acd_professores');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('acd_departamentos');
    }
}
