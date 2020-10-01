<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRehPeriodosLaboraisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reh_periodos_laborais', function (Blueprint $table) {
            $table->increments('pel_id');

            $table->date('pel_inicio');
            $table->date('pel_termino');
            $table->date('pel_encerramento')->nullable();

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
        Schema::drop('reh_periodos_laborais');
    }
}
