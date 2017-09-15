<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOptionsModoEntradaTableAcdMatriculas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('acd_matriculas', function (Blueprint $table) {
            DB::statement("ALTER TABLE acd_matriculas CHANGE COLUMN mat_modo_entrada mat_modo_entrada ENUM('vestibular', 'transferencia_externa',
                          'transferencia_interna_de', 'transferencia_interna_para', 'transferencia_obrigatoria', 'transferencia_ex_oficio', 
                          'graduando_interno', 'graduando_externo')");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('acd_matriculas', function (Blueprint $table) {
            DB::statement("ALTER TABLE acd_matriculas CHANGE COLUMN mat_modo_entrada mat_modo_entrada ENUM('vestibular', 'transferencia_externa',
                          'transferencia_interna_de', 'transferencia_interna_para')");
        });
    }
}
