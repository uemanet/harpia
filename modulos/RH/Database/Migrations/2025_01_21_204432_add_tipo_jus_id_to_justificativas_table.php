<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTipoJusIdToJustificativasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('reh_justificativas', function (Blueprint $table) {
            $table->integer('jus_tipo_id')->unsigned()->nullable()->after('jus_htr_id');
            $table->foreign('jus_tipo_id')->references('tipo_jus_id')->on('reh_tipo_justificativas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('reh_justificativas', function (Blueprint $table) {
            $table->dropForeign(['jus_tipo_id']);
            $table->dropColumn('jus_tipo_id');
        });
    }
}
