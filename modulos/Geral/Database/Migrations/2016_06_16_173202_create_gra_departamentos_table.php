<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGraDepartamentosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gra_departamentos', function (Blueprint $table) {
            $table->increments('dep_id');
            $table->integer('dep_cen_id')->unsigned();
            $table->string('dep_nome', 45);
            $table->timestamps();

            $table->foreign('dep_cen_id')->references('cen_id')->on('gra_centros');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('gra_departamentos');
    }
}
