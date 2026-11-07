<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRehJornadasRemotasTable extends Migration
{
    public function up(): void
    {
        Schema::create('reh_jornadas_remotas', function (Blueprint $table) {
            $table->increments('jor_id');
            $table->integer('jor_col_id')->unsigned();
            $table->integer('jor_eva_entrada_id')->unsigned()->nullable()->unique();
            $table->integer('jor_eva_saida_id')->unsigned()->nullable()->unique();
            $table->date('jor_data_referencia');
            $table->dateTime('jor_entrada_em')->nullable();
            $table->dateTime('jor_saida_em')->nullable();
            $table->text('jor_atividades')->nullable();
            $table->time('jor_horas_calculadas')->nullable();
            $table->time('jor_horas_aprovadas')->nullable();
            $table->enum('jor_status', ['aberta', 'pendente', 'aprovado', 'parcial', 'reprovado', 'inconsistente'])
                ->default('aberta');
            $table->text('jor_motivo_aprovacao')->nullable();
            $table->integer('jor_aprovador_col_id')->unsigned()->nullable();
            $table->dateTime('jor_aprovado_em')->nullable();
            $table->timestamps();

            $table->foreign('jor_col_id')->references('col_id')->on('reh_colaboradores');
            $table->foreign('jor_eva_entrada_id')->references('eva_id')->on('reh_eventos_acesso');
            $table->foreign('jor_eva_saida_id')->references('eva_id')->on('reh_eventos_acesso');
            $table->foreign('jor_aprovador_col_id')->references('col_id')->on('reh_colaboradores');

            $table->index(['jor_col_id', 'jor_data_referencia'], 'reh_jornadas_remotas_col_data_idx');
            $table->index(['jor_col_id', 'jor_status'], 'reh_jornadas_remotas_col_status_idx');
            $table->index('jor_data_referencia');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reh_jornadas_remotas');
    }
}
