<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRehGestoresSetorTable extends Migration
{
    public function up(): void
    {
        Schema::create('reh_gestores_setor', function (Blueprint $table) {
            $table->increments('gst_id');
            $table->integer('gst_col_id')->unsigned();
            $table->integer('gst_set_id')->unsigned();
            $table->boolean('gst_ativo')->default(true);
            $table->timestamps();

            $table->foreign('gst_col_id')->references('col_id')->on('reh_colaboradores');
            $table->foreign('gst_set_id')->references('set_id')->on('reh_setores');
            $table->unique(['gst_col_id', 'gst_set_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reh_gestores_setor');
    }
}
