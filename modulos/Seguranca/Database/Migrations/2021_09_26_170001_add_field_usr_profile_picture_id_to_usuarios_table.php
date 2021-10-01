<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldUsrProfilePictureIdToUsuariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('seg_usuarios', function (Blueprint $table) {
            $table->integer('usr_profile_picture_id')->unsigned()->nullable();
            $table->foreign('usr_profile_picture_id')->references('anx_id')->on('gra_anexos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('seg_usuarios', function (Blueprint $table) {
            $table->dropColumn('usr_profile_picture_id');
        });
    }
}
