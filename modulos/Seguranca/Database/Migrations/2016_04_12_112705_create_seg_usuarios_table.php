<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSegUsuariosTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seg_usuarios', function (Blueprint $table) {
            $table->increments('usr_id');
            $table->integer('usr_pes_id')->unsigned();
            $table->string('usr_usuario', 60)->unique();
            $table->string('usr_senha', 100);
            $table->boolean('usr_ativo')->default(1);
            $table->rememberToken();
            $table->timestamps();

            $table->foreign('usr_pes_id')->references('pes_id')->on('gra_pessoas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('seg_usuarios');
    }
}
