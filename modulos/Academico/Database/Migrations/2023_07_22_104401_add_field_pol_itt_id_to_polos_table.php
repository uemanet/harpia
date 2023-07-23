<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldPolIttIdToPolosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('acd_polos', function (Blueprint $table) {
            $table->integer('pol_itt_id')->unsigned()->nullable();
            $table->foreign('pol_itt_id')->references('itt_id')->on('acd_instituicoes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('acd_polos', function (Blueprint $table) {
            $table->dropColumn('pol_itt_id');
        });
    }
}
