<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterGraAnexosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('gra_anexos', function (Blueprint $table) {
            $table->dropForeign(['anx_tax_id']);
            $table->dropColumn(['anx_tax_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('gra_anexos', function (Blueprint $table) {
            $table->integer('anx_tax_id')->nullable()->unsigned();
            $table->foreign('anx_tax_id')->references('tax_id')->on('gra_tipos_anexos');
        });
    }
}
