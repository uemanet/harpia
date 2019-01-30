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

        Schema::table('gra_anexos', function ($table) {
            $table->string('anx_mime', 255)->change();
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
            //
        });
    }
}
