<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('seg_auditoria', function (Blueprint $table) {
            $table->index(['log_table', 'log_table_id'], 'idx_auditoria_tabela_registro');
            $table->index(['created_at', 'log_action'], 'idx_auditoria_data_acao');
            $table->index(['log_usr_id', 'created_at'], 'idx_auditoria_usuario_data');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('seg_auditoria', function (Blueprint $table) {
            $table->dropIndex('idx_auditoria_tabela_registro');
            $table->dropIndex('idx_auditoria_data_acao');
            $table->dropIndex('idx_auditoria_usuario_data');
        });
    }
};
