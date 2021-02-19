<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterRehColaboradoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('reh_colaboradores', function (Blueprint $table) {
            if (DB::getDriverName() != 'sqlite') {
                $table->dropForeign(['col_set_id']);
                $table->dropForeign(['col_fun_id']);
                $table->dropColumn(['col_set_id']);
                $table->dropColumn(['col_fun_id']);
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('reh_colaboradores', function (Blueprint $table) {
            if (DB::getDriverName() != 'sqlite') {
                $table->integer('col_set_id')->unsigned();
                $table->integer('col_fun_id')->unsigned();

                $table->foreign('col_set_id')->references('set_id')->on('reh_setores');
                $table->foreign('col_fun_id')->references('fun_id')->on('reh_funcoes');
            }
        });
    }
}
