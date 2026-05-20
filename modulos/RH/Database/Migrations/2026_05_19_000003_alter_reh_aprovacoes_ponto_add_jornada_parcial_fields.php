<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AlterRehAprovacoesPontoAddJornadaParcialFields extends Migration
{
    public function up(): void
    {
        Schema::table('reh_aprovacoes_ponto', function (Blueprint $table) {
            $table->integer('apr_jor_id')->unsigned()->nullable();
            $table->time('apr_horas_aceitas')->nullable();

            $table->foreign('apr_jor_id', 'reh_aprovacoes_ponto_apr_jor_id_foreign')
                ->references('jor_id')
                ->on('reh_jornadas_remotas');
        });

        if (DB::getDriverName() === 'mysql') {
            DB::statement(
                "ALTER TABLE reh_aprovacoes_ponto MODIFY apr_status ENUM('aprovado', 'parcial', 'reprovado') NOT NULL"
            );
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement(
                "ALTER TABLE reh_aprovacoes_ponto MODIFY apr_status ENUM('aprovado', 'reprovado') NOT NULL"
            );
        }

        Schema::table('reh_aprovacoes_ponto', function (Blueprint $table) {
            $table->dropForeign('reh_aprovacoes_ponto_apr_jor_id_foreign');
            $table->dropColumn(['apr_jor_id', 'apr_horas_aceitas']);
        });
    }
}
