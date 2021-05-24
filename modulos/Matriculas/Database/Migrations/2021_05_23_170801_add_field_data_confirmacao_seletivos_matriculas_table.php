<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldDataConfirmacaoSeletivosMatriculasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mat_seletivos_matriculas', function (Blueprint $table) {
            $table->timestamp('data_confirmacao')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mat_seletivos_matriculas', function (Blueprint $table) {
            $table->dropColumn('data_confirmacao');
        });
    }
}