<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIntAmbientesVirtuaisTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('int_ambientes_virtuais', function (Blueprint $table) {
            $table->increments('amb_id');
            $table->string('amb_nome', 45);
            $table->string('amb_versao', 20);
            $table->string('amb_url', 90);

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
        Schema::drop('int_ambientes_virtuais');
    }
}
