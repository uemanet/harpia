<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRehAtividadesExtrasColaboradoresTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reh_atividades_extras_colaboradores', function (Blueprint $table) {
            $table->increments('atc_id');
            $table->integer('atc_col_id')->unsigned();
            $table->string('atc_titulo', 150);
            $table->string('atc_descricao', 150)->nullable();
            $table->enum('atc_tipo', [
                'curso',
                'evento',
                'oficina'
            ]);
            $table->integer('atc_carga_horaria')->nullable();
            $table->date('atc_data_inicio')->nullable();
            $table->date('atc_data_fim')->nullable();

            $table->timestamps();

            $table->foreign('atc_col_id')->references('col_id')->on('reh_colaboradores');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('reh_atividades_extras_colaboradores');
    }
}
