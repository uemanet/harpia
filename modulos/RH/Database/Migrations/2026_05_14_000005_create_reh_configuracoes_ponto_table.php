<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRehConfiguracoesPontoTable extends Migration
{
    public function up(): void
    {
        Schema::create('reh_configuracoes_ponto', function (Blueprint $table) {
            $table->increments('cop_id');
            $table->string('cop_chave')->unique();
            $table->text('cop_valor');
            $table->string('cop_descricao')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reh_configuracoes_ponto');
    }
}
