<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterAcdMatriculasOfertasDisciplinasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('acd_matriculas_ofertas_disciplinas', function (Blueprint $table) {
            $table->text('mof_observacao')->after('mof_situacao_matricula')->nullable();
        });

        DB::statement("ALTER TABLE acd_matriculas_ofertas_disciplinas CHANGE COLUMN mof_tipo_matricula  ENUM('matriculacomum', 'aproveitamento')  NOT NULL");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE acd_matriculas_ofertas_disciplinas CHANGE COLUMN mof_tipo_matricula  ENUM('matriculacomum', 'aproveitamentointerno', 'aproveitamentoexterno')  NOT NULL");
    }
}
