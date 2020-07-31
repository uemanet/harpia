<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRehBancosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reh_bancos', function (Blueprint $table) {
            $table->increments('ban_id');
            $table->string('ban_nome', 80);
            $table->string('ban_codigo', 10);
            $table->string('ban_sigla', 25);
            
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
        Schema::drop('reh_bancos');
    }
}
