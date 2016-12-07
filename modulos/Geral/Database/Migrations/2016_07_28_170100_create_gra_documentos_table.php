<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGraDocumentosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gra_documentos', function (Blueprint $table) {
            $table->increments('doc_id');
            $table->integer('doc_pes_id')->unsigned();
            $table->integer('doc_tpd_id')->unsigned();
            $table->string('doc_conteudo', 150);
            $table->date('doc_data_expedicao')->nullable();
            $table->string('doc_orgao', 255)->nullable();
            $table->string('doc_observacao', 255)->nullable();

            $table->timestamps();

            $table->foreign('doc_pes_id')->references('pes_id')->on('gra_pessoas');
            $table->foreign('doc_tpd_id')->references('tpd_id')->on('gra_tipos_documentos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('gra_documentos');
    }
}
