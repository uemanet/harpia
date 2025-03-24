<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRehPeriodosGozoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reh_horas_periodos_gozo', function (Blueprint $table) {
            $table->increments('pgz_id');
            $table->integer('pgz_paq_id')->unsigned();
            $table->date('pgz_data_inicio');
            $table->date('pgz_data_fim');
            $table->string('pgz_observacao', 255);
            $table->boolean('pgz_ferias_gozadas')->default(false);

            $table->timestamps();

            $table->foreign('pgz_paq_id')->references('paq_id')->on('reh_periodos_aquisitivos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('reh_periodos_aquisitivos', function (Blueprint $table) {
            $table->dropColumn(['paq_gozo_inicio', 'paq_gozo_fim']);
        });
    }
}
