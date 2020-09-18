<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRehContasColaboradoresTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reh_contas_colaboradores', function (Blueprint $table) {
            $table->increments('ccb_id');
            $table->integer('ccb_col_id')->unsigned();
            $table->integer('ccb_ban_id')->unsigned();

            $table->string('ccb_agencia', 10);
            $table->string('ccb_conta', 20);
            $table->string('ccb_variacao', 10);


            $table->timestamps();

            $table->foreign('ccb_col_id')->references('col_id')->on('reh_colaboradores');
            $table->foreign('ccb_ban_id')->references('ban_id')->on('reh_bancos');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('reh_contas_colaboradores');
    }
}
