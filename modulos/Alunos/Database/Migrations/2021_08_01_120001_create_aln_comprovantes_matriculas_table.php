<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAlnComprovantesMatriculasTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('aln_comprovantes_matriculas', function (Blueprint $table) {
            $table->increments('aln_id');
            $table->integer('aln_mat_id')->unsigned();
            $table->longText('aln_dados_matricula');
            $table->string('aln_codigo');

            $table->timestamps();

            $table->foreign('aln_mat_id')->references('mat_id')->on('acd_matriculas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('aln_comprovantes_matriculas');
    }
}
