<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterGraPessoasNullableFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('gra_pessoas', function (Blueprint $table) {

            $table->string('pes_mae')->nullable()->change();
            $table->string('pes_sexo')->nullable()->change();
            $table->date('pes_nascimento')->nullable()->change();
            $table->string('pes_naturalidade', 45)->nullable()->change();
            $table->string('pes_nacionalidade', 45)->nullable()->change();
            $table->string('pes_endereco')->nullable()->change();
            $table->string('pes_numero', 45)->nullable()->change();
            $table->string('pes_complemento', 150)->nullable()->change();
            $table->string('pes_cep', 10)->nullable()->change();
            $table->string('pes_cidade', 150)->nullable()->change();
            $table->string('pes_bairro', 150)->nullable()->change();
            $table->string('pes_estado', 2)->nullable()->change();

        });

        DB::statement("ALTER TABLE gra_pessoas ADD CONSTRAINT check_gra_pessoas_pes_sexo CHECK (pes_sexo IN ('M', 'F'))");

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
}
