<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterAcdTurmasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('acd_turmas', function (Blueprint $table) {
            $table->integer('trm_itt_id')->unsigned()->nullable();

            $table->foreign('trm_itt_id')->references('itt_id')->on('acd_instituicoes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
}