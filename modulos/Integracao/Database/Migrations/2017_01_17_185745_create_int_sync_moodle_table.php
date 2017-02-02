<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIntSyncMoodleTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('int_sync_moodle', function (Blueprint $table) {
            $table->increments('sym_id');
            $table->text('sym_table');
            $table->integer('sym_table_id');
            $table->text('sym_action');
            $table->integer('sym_status');
            $table->text('sym_mensagem')->nullable();
            $table->dateTime('sym_data_envio')->nullable();
            $table->text('sym_extra')->nullable();

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
        Schema::drop('int_sync_moodle');
    }
}
