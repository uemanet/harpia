<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterIntMapeamentoItensNotaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('int_mapeamento_itens_nota', function (Blueprint $table) {
            $table->integer('min_id_aproveitamento')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('int_mapeamento_itens_nota', function (Blueprint $table) {
            $table->dropColumn('min_id_aproveitamento');
        });
    }
}
