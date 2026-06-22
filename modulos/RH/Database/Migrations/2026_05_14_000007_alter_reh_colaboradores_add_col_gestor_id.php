<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterRehColaboradoresAddColGestorId extends Migration
{
    public function up(): void
    {
        Schema::table('reh_colaboradores', function (Blueprint $table) {
            $table->integer('col_gestor_id')->unsigned()->nullable()->after('col_status');
            $table->foreign('col_gestor_id')->references('col_id')->on('reh_colaboradores');
        });
    }

    public function down(): void
    {
        Schema::table('reh_colaboradores', function (Blueprint $table) {
            $table->dropForeign(['col_gestor_id']);
            $table->dropColumn('col_gestor_id');
        });
    }
}
