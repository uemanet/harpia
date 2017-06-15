<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSegAuditoriaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seg_auditoria', function (Blueprint $table) {
            $table->increments('log_id');
            $table->integer('log_usr_id')->unsigned();
            $table->enum('log_action', ['INSERT', 'UPDATE', 'DELETE']);
            $table->string('log_table');
            $table->integer('log_table_id');
            $table->longText('log_object')->nullable();
            $table->timestamps();

            $table->foreign('log_usr_id')->references('usr_id')->on('seg_usuarios');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('seg_auditoria');
    }
}
