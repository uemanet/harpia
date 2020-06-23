<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldTrmTipoIntegracaoAcdTurmas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('acd_turmas', function (Blueprint $table) {
            $table->enum('trm_tipo_integracao', ['v1', 'v2'])->default('v1')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('acd_turmas', function (Blueprint $table) {
            $table->dropColumn('trm_tipo_integracao');
        });
    }
}
