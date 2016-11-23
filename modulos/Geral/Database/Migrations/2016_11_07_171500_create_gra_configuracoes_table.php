<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGraConfiguracoesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gra_configuracoes', function (Blueprint $table) {
            $table->increments('cnf_id');
            $table->integer('cnf_mod_id')->unsigned();
            $table->string('cnf_nome', 45)->unique();
            $table->string('cnf_valor', 45);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('gra_configuracoes');
    }
}
