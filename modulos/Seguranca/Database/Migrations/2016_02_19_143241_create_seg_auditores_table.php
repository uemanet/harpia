<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSegAuditoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seg_auditores', function (Blueprint $table) {
            $table->increments('log_id');
            $table->string('log_usr_nome');
            $table->string('log_model');
            $table->integer('log_model_id');
            $table->string('log_type');
            $table->longText('log_data');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       Schema::drop('seg_auditores');
    }
}
