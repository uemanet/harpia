<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterAcdLancamentosTccsTable extends Migration
{

    public function __construct()
    {
        DB::getDoctrineSchemaManager()->getDatabasePlatform()->registerDoctrineTypeMapping('enum', 'string');
    }
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('acd_lancamentos_tccs', function (Blueprint $table) {
          $table->string('ltc_titulo', 400)->change();
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('acd_lancamentos_tccs', function (Blueprint $table) {
          $table->string('ltc_titulo', 200)->change();
      });
    }
}
