<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAcdHistoricoMatriculasTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('acd_historico_matriculas', function (Blueprint $table) {
            $table->increments('hmt_id');
            $table->integer('hmt_mat_id')->unsigned();
            $table->date('hmt_data');
            $table->enum('hmt_tipo', ['mudanca_polo', 'mudanca_grupo', 'alteracao_status']);
            $table->string('hmt_observacao')->nullable();
            $table->timestamps();

            $table->foreign('hmt_mat_id')->references('mat_id')->on('acd_matriculas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('acd_historico_matriculas');
    }
}
