<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSegModulosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seg_modulos', function (Blueprint $table) {
            $table->increments('mod_id');
            $table->string('mod_nome');
            $table->string('mod_slug');
            $table->string('mod_icone');
            $table->string('mod_descricao')->nullable();
            $table->string('mod_classes')->default('bg-aqua')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('seg_modulos');
    }
}
