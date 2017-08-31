<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropGraTiposAnexosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('gra_tipos_anexos');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('gra_tipos_anexos', function (Blueprint $table) {
            $table->increments('tax_id');
            $table->string('tax_nome', 45);

            $table->timestamps();
        });
    }
}
