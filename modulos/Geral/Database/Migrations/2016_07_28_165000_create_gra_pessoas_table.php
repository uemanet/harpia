<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGraPessoasTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gra_pessoas', function (Blueprint $table) {
            $table->increments('pes_id');
            $table->string('pes_nome', 150);
            $table->enum('pes_sexo', ['M', 'F']);
            $table->string('pes_email', 150)->unique();
            $table->string('pes_telefone', 20);
            $table->date('pes_nascimento');
            $table->string('pes_mae', 150);
            $table->string('pes_pai', 150)->nullable();
            $table->enum('pes_estado_civil', ['solteiro', 'casado', 'divorciado', 'viuvo(a)', 'uniao_estavel']);
            $table->string('pes_naturalidade', 45)->nullable();
            $table->string('pes_nacionalidade', 45)->nullable();
            $table->string('pes_raca', 45)->nullable();
            $table->string('pes_necessidade_especial', 150)->nullable();
            $table->boolean('pes_estrangeiro')->default(0);
            $table->string('pes_endereco');
            $table->string('pes_numero', 45);
            $table->string('pes_complemento', 150)->nullable();
            $table->string('pes_cep', 10);
            $table->string('pes_cidade', 150);
            $table->string('pes_bairro', 150);
            $table->char('pes_estado', 2);
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
        Schema::drop('gra_pessoas');
    }
}
