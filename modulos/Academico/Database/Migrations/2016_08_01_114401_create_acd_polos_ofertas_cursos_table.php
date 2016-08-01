<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAcdPolosOfertasCursosTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('acd_polos_ofertas_cursos', function (Blueprint $table) {
            $table->integer('poc_pol_id')->unsigned();
            $table->integer('poc_ofc_id')->unsigned();

            $table->foreign('poc_pol_id')->references('pol_id')->on('acd_polos');
            $table->foreign('poc_ofc_id')->references('ofc_id')->on('acd_ofertas_cursos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('acd_polos_ofertas_cursos');
    }
}
