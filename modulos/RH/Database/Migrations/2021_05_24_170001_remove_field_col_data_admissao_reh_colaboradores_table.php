<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveFieldColDataAdmissaoRehColaboradoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('reh_colaboradores', function (Blueprint $table) {
            $table->dropColumn('col_data_admissao');
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
            $table->date('col_data_admissao');
        });
    }
}
