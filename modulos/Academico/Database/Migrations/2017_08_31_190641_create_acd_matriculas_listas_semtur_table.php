<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAcdMatriculasListasSemturTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('acd_matriculas_listas_semtur', function (Blueprint $table) {
            $table->integer('mls_mat_id')->unsigned();
            $table->integer('mls_lst_id')->unsigned();

            $table->primary(['mls_mat_id', 'mls_lst_id']);
            $table->foreign('mls_mat_id')->references('mat_id')->on('acd_matriculas');
            $table->foreign('mls_lst_id')->references('lst_id')->on('acd_listas_semtur');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('acd_matriculas_listas_semtur');
    }
}
