<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSegModulosTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seg_modulos', function(Blueprint $table)
        {
            $table->increments('mod_id');
            $table->string('mod_nome', 150);
            $table->string('mod_descricao', 300);
            $table->string('mod_icone', 45);
            $table->boolean('mod_ativo')->default(1);
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
        Schema::drop('seg_modulos');
    }

}
