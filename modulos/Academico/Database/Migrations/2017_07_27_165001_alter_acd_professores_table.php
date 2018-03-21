<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterAcdProfessoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('acd_professores', function (Blueprint $table) {
            $table->integer('prf_codigo')->after('prf_pes_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('acd_professores', function (Blueprint $table) {
            $table->dropColumn('prf_codigo');
        });
    }
}
