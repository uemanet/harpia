<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChamadasTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mat_chamadas', function (Blueprint $table) {
            $db = DB::connection('mysql2')->getDatabaseName();

            $table->increments('id');
            $table->integer('seletivo_id')->unsigned();
            $table->string('nome');
            $table->datetime('inicio_matricula');
            $table->datetime('fim_matricula');
            $table->integer('numero_chamada')->nullable();
            $table->enum('tipo_chamada', ['normal', 'excedente']);

            $table->timestamps();
            $table->foreign('seletivo_id')->references('id')->on($db . '.seletivos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('mat_chamadas');
    }
}
