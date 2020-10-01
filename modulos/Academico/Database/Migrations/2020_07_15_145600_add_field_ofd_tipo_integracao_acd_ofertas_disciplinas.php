<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldOfdTipoIntegracaoAcdOfertasDisciplinas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('acd_ofertas_disciplinas', function (Blueprint $table) {
            $table->enum('ofd_tipo_integracao', ['v1', 'v2'])->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('acd_ofertas_disciplinas', function (Blueprint $table) {
            $table->dropColumn('ofd_tipo_integracao');
        });
    }
}
