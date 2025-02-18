<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterRehPeriodosAquisitivosAddGozo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('reh_periodos_aquisitivos', function (Blueprint $table) {
            $table->date('paq_gozo_inicio')->nullable()->after('paq_observacao');
            $table->date('paq_gozo_fim')->nullable()->after('paq_gozo_inicio');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('reh_periodos_aquisitivos', function (Blueprint $table) {
            $table->dropColumn(['paq_gozo_inicio', 'paq_gozo_fim']);
        });
    }
}
