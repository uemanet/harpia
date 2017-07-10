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
        Schema::create('gra_tipos_documentos', function (Blueprint $table) {
            $table->increments('tpd_id');
            $table->string('tpd_nome', 45);

            $table->timestamps();
        });
    }
}
