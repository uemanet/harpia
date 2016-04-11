<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSegUsuariosTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seg_usuarios', function(Blueprint $table)
        {
            $table->increments('usr_id');
            $table->string('usr_nome', 150);
            $table->string('usr_email', 150);
            $table->string('usr_telefone', 15)->nullable();
            $table->string('usr_usuario', 45)->unique();
            $table->string('usr_senha', 100);
            $table->boolean('usr_ativo')->default(1);
            $table->rememberToken();
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
        Schema::drop('seg_usuarios');
    }

}
