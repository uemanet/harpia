<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterAcdLancamentosTccsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

      DB::statement('ALTER TABLE `acd_lancamentos_tccs` CHANGE `ltc_titulo` `ltc_titulo` VARCHAR(400) NOT NULL');

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

      DB::statement('ALTER TABLE `acd_lancamentos_tccs` CHANGE `ltc_titulo` `ltc_titulo` VARCHAR(200) NOT NULL');

    }
}
