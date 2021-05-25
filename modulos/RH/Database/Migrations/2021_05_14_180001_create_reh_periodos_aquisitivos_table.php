<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRehPeriodosAquisitivosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reh_periodos_aquisitivos', function (Blueprint $table) {
            $table->increments('paq_id');
            $table->integer('paq_col_id')->unsigned();
            $table->integer('paq_mtc_id')->unsigned();
            $table->date('paq_data_inicio');
            $table->date('paq_data_fim');
            $table->string('paq_observacao', 255);
            $table->boolean('paq_ferias_gozadas')->default(false);

            $table->timestamps();

            $table->foreign('paq_col_id')->references('col_id')->on('reh_colaboradores');
            $table->foreign('paq_mtc_id')->references('mtc_id')->on('reh_matriculas');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('reh_periodos_aquisitivos');
    }
}
