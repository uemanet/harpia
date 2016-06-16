<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGraCentrosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gra_centros', function (Blueprint $table) {
            $table->integer('cen_id')->unsigned();
            $table->string('cen_nome', 45);
            $table->string('cen_sigla', 10)->nullable();

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
        Schema::drop('gra_centros');
    }
}
