<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAcdDisciplinasTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('acd_disciplinas', function (Blueprint $table) {
            $table->increments('dis_id');
            $table->integer('dis_nvc_id')->unsigned();
            $table->string('dis_nome', 90);
            $table->integer('dis_carga_horaria');
            $table->integer('dis_creditos');
            $table->text('dis_ementa')->nullable();
            $table->text('dis_bibliografia')->nullable();

            $table->timestamps();

            $table->foreign('dis_nvc_id')->references('nvc_id')->on('acd_niveis_cursos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('acd_disciplinas');
    }
}
