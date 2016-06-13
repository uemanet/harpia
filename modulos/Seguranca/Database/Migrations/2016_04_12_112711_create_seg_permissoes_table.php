<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSegPermissoesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seg_permissoes', function (Blueprint $table) {
            $table->increments('prm_id');
            $table->integer('prm_rcs_id')->unsigned();
            $table->string('prm_nome', 45);
            $table->string('prm_descricao', 300);
            $table->timestamps();
            
            $table->foreign('prm_rcs_id')->references('rcs_id')->on('seg_recursos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('seg_permissoes');
    }
}
