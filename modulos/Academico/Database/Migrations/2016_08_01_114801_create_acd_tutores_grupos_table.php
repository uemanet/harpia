<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAcdTutoresGruposTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('acd_tutores_grupos', function (Blueprint $table) {
            $table->increments('ttg_id');
            $table->integer('ttg_tut_id')->unsigned();
            $table->integer('ttg_grp_id')->unsigned();
            $table->enum('ttg_tipo_tutoria', ['presencial', 'distancia']);
            $table->date('ttg_data_inicio');
            $table->date('ttg_data_fim')->nullable();;

            $table->timestamps();

            $table->foreign('ttg_tut_id')->references('tut_id')->on('acd_tutores');
            $table->foreign('ttg_grp_id')->references('grp_id')->on('acd_grupos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('acd_tutores_grupos');
    }
}
