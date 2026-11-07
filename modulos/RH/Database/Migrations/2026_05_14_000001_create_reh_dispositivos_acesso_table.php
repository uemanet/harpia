<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRehDispositivosAcessoTable extends Migration
{
    public function up(): void
    {
        Schema::create('reh_dispositivos_acesso', function (Blueprint $table) {
            $table->increments('dis_id');
            $table->string('dis_nome');
            $table->string('dis_identificador')->unique();
            $table->enum('dis_tipo', ['entrada', 'saida']);
            $table->string('dis_ip', 45)->nullable();
            $table->string('dis_modelo')->nullable();
            $table->string('dis_token_api', 64)->unique();
            $table->enum('dis_status', ['ativo', 'inativo'])->default('ativo');
            $table->unsignedBigInteger('dis_ultimo_access_log_id')->nullable();
            $table->dateTime('dis_ultima_coleta_em')->nullable();
            $table->text('dis_observacao')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reh_dispositivos_acesso');
    }
}
