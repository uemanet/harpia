<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AlterRehColaboradoresAddColFotoAnxId extends Migration
{
    public function up()
    {
        Schema::table('reh_colaboradores', function (Blueprint $table) {
            $table->integer('col_foto_anx_id')->unsigned()->nullable()->after('col_matricula_universidade');
            $table->foreign('col_foto_anx_id')->references('anx_id')->on('gra_anexos');
        });
    }

    public function down()
    {
        Schema::table('reh_colaboradores', function (Blueprint $table) {
            $table->dropForeign(['col_foto_anx_id']);
            $table->dropColumn('col_foto_anx_id');
        });
    }
}
