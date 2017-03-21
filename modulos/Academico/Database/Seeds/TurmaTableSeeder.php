<?php

namespace Modulos\Academico\Database\Seeds;

use Illuminate\Database\Seeder;
use Modulos\Academico\Models\OfertaCurso;
use Modulos\Academico\Models\Turma;

class TurmaTableSeeder extends Seeder
{
    public function run()
    {
        $ofertas = OfertaCurso::all();

        foreach ($ofertas as $oferta) {
            $turma = new Turma();

            $turma->trm_ofc_id = $oferta->ofc_id;
            $turma->trm_per_id = 1;
            $turma->trm_nome = 'Turma A';
            $turma->trm_qtd_vagas = 100;
            $turma->trm_integrada = 0;

            $turma->save();
        }
    }
}
