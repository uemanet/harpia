<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreaterehVinculosFontesPagadorasTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reh_vinculos_fontes_pagadoras', function (Blueprint $table) {
            $table->increments('vfp_id');
            $table->integer('vfp_fpg_id')->unsigned();
            $table->integer('vfp_vin_id')->unsigned();

            $table->boolean('vfp_unidade')->nullable();
            $table->decimal('vfp_valor', 12, 2)->nullable();

            $table->timestamps();

            $table->foreign('vfp_fpg_id')->references('fpg_id')->on('reh_fontes_pagadoras');
            $table->foreign('vfp_vin_id')->references('vin_id')->on('reh_vinculos');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('reh_vinculos_fontes_pagadoras');
    }
}
