<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAcdListasSemturTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('acd_listas_semtur', function (Blueprint $table) {
            $table->increments('lst_id');
            $table->string('lst_nome');
            $table->string('lst_descricao');
            $table->date('lst_data_bloqueio');
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
        Schema::dropIfExists('acd_listas_semtur');
    }
}
