<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToSeletivosUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mat_seletivos_users', function (Blueprint $table) {
            $table->integer('pes_id')->after('id')->nullable();
            $table->integer('alu_id')->after('pes_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mat_seletivos_users', function (Blueprint $table) {
            $table->dropColumn('pes_id');
            $table->dropColumn('alu_id');

        });
    }
}
