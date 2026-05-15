<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRehEventosAcessoTable extends Migration
{
    public function up(): void
    {
        Schema::create('reh_eventos_acesso', function (Blueprint $table) {
            $table->increments('eva_id');
            $table->integer('eva_col_id')->unsigned()->nullable();
            $table->integer('eva_dis_id')->unsigned()->nullable();
            $table->enum('eva_tipo', ['entrada', 'saida']);
            $table->dateTime('eva_data_hora');
            $table->enum('eva_origem', ['idface', 'home_office']);
            $table->enum('eva_status', [
                'bruto',
                'processado',
                'pendente',
                'aprovado',
                'reprovado',
                'erro',
                'duplicado',
            ])->default('bruto');
            $table->string('eva_status_mensagem')->nullable();
            $table->string('eva_hash', 64)->unique();
            $table->string('eva_ip_origem', 45)->nullable();
            $table->text('eva_user_agent')->nullable();
            $table->text('eva_observacao')->nullable();
            $table->timestamps();

            $table->foreign('eva_col_id')->references('col_id')->on('reh_colaboradores');
            $table->foreign('eva_dis_id')->references('dis_id')->on('reh_dispositivos_acesso');
            $table->index('eva_data_hora');
            $table->index(['eva_col_id', 'eva_data_hora']);
            $table->index('eva_status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reh_eventos_acesso');
    }
}
