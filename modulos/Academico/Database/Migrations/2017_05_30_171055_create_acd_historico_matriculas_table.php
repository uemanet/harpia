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
            $table->string('hmt_mat_id');
            $table->date('hmt_data');
            $table->enum('hmt_tipo', ['mudanca_polo', 'mudanca_grupo', 'alteracao_status']);
            $table->string('hmt_observacao')->nullable();
            $table->timestamps();
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
