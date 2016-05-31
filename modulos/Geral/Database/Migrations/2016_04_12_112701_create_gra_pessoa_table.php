<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGraPessoaTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gra_pessoa', function(Blueprint $table)
        {
            $table->increments('pes_id');
            $table->string('pes_nome', 150);
            $table->enum('pes_sexo', ['M', 'F']);
            $table->string('pes_email', 150)->unique();
            $table->string('pes_telefone', 20);
            $table->date('pes_nascimento');
            $table->string('pes_mae', 150);
            $table->string('pes_pai', 150)->nullable();
            $table->string('pes_estado_civil', 20);
            $table->string('pes_naturalidade', 45)->nullable();
            $table->string('pes_nacionalidade', 45)->nullable();
            $table->string('pes_raca', 45)->nullable();
            $table->string('pes_necessidade_especial', 150)->nullable();
            $table->boolean('pes_estrangeiro')->default(0);
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
        Schema::drop('gra_pessoa');
    }

}
