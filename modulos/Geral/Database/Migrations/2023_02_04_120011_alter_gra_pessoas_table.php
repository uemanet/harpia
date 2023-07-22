<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterGraPessoasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('gra_pessoas', function (Blueprint $table) {
            $table->integer('pes_itt_id')->unsigned()->nullable();

            $table->foreign('pes_itt_id')->references('itt_id')->on('acd_instituicoes');
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
