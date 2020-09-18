<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRehCalendariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reh_calendarios', function (Blueprint $table) {
            $table->increments('cld_id');
            $table->string('cld_nome', 80);
            $table->date('cld_data');
            $table->string('cld_observacao')->nullable();
            $table->enum('cld_tipo_evento', [
                'FN',
                'FE',
                'FM',
                'PF'
            ]);

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
        Schema::drop('reh_calendarios');
    }
}
