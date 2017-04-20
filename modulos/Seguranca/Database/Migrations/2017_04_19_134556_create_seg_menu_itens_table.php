<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSegMenuItensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seg_menu_itens', function (Blueprint $table) {
            $table->increments('mit_id');
            $table->integer('mit_mod_id')->unsigned();
            $table->integer('mit_item_pai')->unsigned()->nullable();
            $table->string('mit_nome');
            $table->string('mit_icone')->default('fa fa-circle-o');
            $table->integer('mit_visivel')->default(1);
            $table->string('mit_rota')->nullable();
            $table->string('mit_descricao')->nullable();
            $table->integer('mit_ordem')->default(1)->nullable();
            $table->timestamps();

            $table->foreign('mit_mod_id')->references('mod_id')->on('seg_modulos');
            $table->foreign('mit_item_pai')->references('mit_id')->on('seg_menu_itens');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('seg_menu_itens');
    }
}
