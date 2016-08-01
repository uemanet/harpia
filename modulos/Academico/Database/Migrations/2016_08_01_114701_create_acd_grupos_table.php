<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAcdGruposTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('acd_grupos', function (Blueprint $table) {
            $table->increments('grp_id');
            $table->integer('grp_trm_id')->unsigned();
            $table->integer('grp_pol_id')->unsigned();
            $table->string('grp_nome', 45);
            
            $table->foreign('grp_trm_id')->references('trm_id')->on('acd_turmas');
            $table->foreign('grp_pol_id')->references('pol_id')->on('acd_polos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('acd_grupos');
    }
}
