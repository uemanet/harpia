<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAcdLivrosTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('acd_livros', function (Blueprint $table) {
            $table->increments('liv_id');
            $table->integer('liv_numero');
            $table->enum('liv_tipo_livro', ['CERTIFICADO', 'DIPLOMA']);
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
        Schema::drop('acd_livros');
    }
}
