<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTrmIdFieldMatChamadasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mat_chamadas', function (Blueprint $table) {
            $table->integer('trm_id')->after('id')->unsigned()->nullable();
            $table->foreign('trm_id')->references('trm_id')->on('acd_turmas');

        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mat_chamadas', function (Blueprint $table) {
            $table->dropColumn('trm_id');
        });
    }
}
