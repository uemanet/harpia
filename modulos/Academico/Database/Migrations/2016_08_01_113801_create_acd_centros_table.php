<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAcdCentrosTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('acd_centros', function (Blueprint $table) {
            $table->increments('cen_id');
            $table->integer('cen_prf_diretor')->unsigned();
            $table->string('cen_nome', 45);
            $table->string('cen_sigla', 10)->nullable();

            $table->timestamps();

            $table->foreign('cen_prf_diretor')->references('prf_id')->on('acd_professores');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('acd_centros');
    }
}
