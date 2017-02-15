<?php

namespace Modulos\Academico\Database\Seeds;


use Illuminate\Database\Seeder;
use Modulos\Academico\Models\Turma;

class TurmaTableSeeder extends Seeder
{
    public function run()
    {
        $turma = new Turma();

        $turma->trm_ofc_id = 1;
        $turma->trm_per_id = 1;
        $turma->trm_nome = 'Turma A';
        $turma->trm_qtd_vagas = 70;
        $turma->trm_integrada = 0;

        $turma->save();

        $turma = new Turma();

        $turma->trm_ofc_id = 1;
        $turma->trm_per_id = 1;
        $turma->trm_nome = 'Turma B';
        $turma->trm_qtd_vagas = 70;
        $turma->trm_integrada = 0;

        $turma->save();

        $turma = new Turma();

        $turma->trm_ofc_id = 1;
        $turma->trm_per_id = 2;
        $turma->trm_nome = 'Turma C';
        $turma->trm_qtd_vagas = 70;
        $turma->trm_integrada = 0;

        $turma->save();
    }
}