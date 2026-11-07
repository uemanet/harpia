<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRehMapeamentoDispositivoTable extends Migration
{
    public function up(): void
    {
        Schema::create('reh_mapeamento_dispositivo', function (Blueprint $table) {
            $table->increments('map_id');
            $table->integer('map_dis_id')->unsigned();
            $table->integer('map_col_id')->unsigned()->nullable();
            $table->string('map_user_id', 20);
            $table->string('map_registration', 50);
            $table->string('map_nome_dispositivo')->nullable();
            $table->boolean('map_ativo')->default(true);
            $table->timestamps();

            $table->foreign('map_dis_id')->references('dis_id')->on('reh_dispositivos_acesso');
            $table->foreign('map_col_id')->references('col_id')->on('reh_colaboradores');
            $table->unique(['map_dis_id', 'map_user_id']);
            $table->unique(['map_dis_id', 'map_col_id']);
            $table->index('map_registration');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reh_mapeamento_dispositivo');
    }
}
