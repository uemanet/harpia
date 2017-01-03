<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGraAnexosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gra_anexos', function (Blueprint $table) {
            $table->increments('anx_id');
            $table->integer('anx_tax_id')->unsigned();
            $table->string('anx_nome', 45);
            $table->string('anx_mime', 45);
            $table->string('anx_extensao', 20);
            $table->string('anx_localizacao', 255);

            $table->timestamps();

            $table->foreign('anx_tax_id')->references('tax_id')->on('gra_tipos_anexos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('gra_anexos');
    }
}
