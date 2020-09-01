<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSeletivosUsersTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seletivos_users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nome', 150);
            $table->string('email')->unique();
            $table->string('rg', 20);
            $table->string('cpf', 14)->unique();
            $table->boolean('estrangeiro')->default(0);
            $table->date('nascimento');
            $table->enum('sexo', ['M', 'F']);
            $table->enum('estado_civil', ['solteiro', 'casado', 'divorciado', 'viuvo', 'uniao_estavel']);
            $table->string('mae', 150);
            $table->string('pai', 150)->nullable();

            $table->string('cep', 10);
            $table->char('estado', 2);
            $table->string('cidade', 150);
            $table->string('bairro', 150);
            $table->string('endereco');
            $table->string('numero', 45)->nullable();
            $table->string('complemento')->nullable();
            $table->string('celular', 20);
            $table->string('telefone')->nullable();

            $table->string('password');
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
        Schema::drop('seletivos_users');
    }
}
