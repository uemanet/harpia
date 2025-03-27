<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterJustificativasAddDataFim extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('reh_justificativas', function (Blueprint $table) {
            $table->date('jus_data_fim')->after('jus_data');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('reh_justificativas', function (Blueprint $table) {
            $table->dropColumn('jus_data_fim');
        });
    }
}
