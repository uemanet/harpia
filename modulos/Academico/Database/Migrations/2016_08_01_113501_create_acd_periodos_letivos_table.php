<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAcdPeriodosLetivosTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('acd_periodos_letivos', function (Blueprint $table) {
            $table->increments('per_id');
            $table->string('per_nome', 45);
            $table->date('per_inicio');
            $table->date('per_fim');

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
        Schema::drop('acd_periodos_letivos');
    }
}
