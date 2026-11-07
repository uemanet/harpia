<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRehAprovacoesPontoTable extends Migration
{
    public function up(): void
    {
        Schema::create('reh_aprovacoes_ponto', function (Blueprint $table) {
            $table->increments('apr_id');
            $table->integer('apr_eva_id')->unsigned();
            $table->integer('apr_aprovador_col_id')->unsigned();
            $table->dateTime('apr_data_aprovacao');
            $table->enum('apr_status', ['aprovado', 'reprovado']);
            $table->text('apr_motivo')->nullable();
            $table->dateTime('apr_hora_ajustada')->nullable();
            $table->timestamps();

            $table->foreign('apr_eva_id')->references('eva_id')->on('reh_eventos_acesso');
            $table->foreign('apr_aprovador_col_id')->references('col_id')->on('reh_colaboradores');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reh_aprovacoes_ponto');
    }
}
