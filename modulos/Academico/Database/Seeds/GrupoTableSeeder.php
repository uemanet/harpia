<?php

namespace Modulos\Academico\Database\Seeds;

use Illuminate\Database\Seeder;
use Modulos\Academico\Models\Grupo;
use Modulos\Academico\Models\Turma;

class GrupoTableSeeder extends Seeder
{
    public function run()
    {
        $turmas = Turma::all();

        foreach ($turmas as $turma) {

            // criar um grupo por polo da oferta do curso
            $polos = $turma->ofertacurso->polos;

            foreach ($polos as $polo) {
                Grupo::create([
                    'grp_trm_id' => $turma->trm_id,
                    'grp_pol_id' => $polo->pol_id,
                    'grp_nome' => 'Grupo '.$polo->pol_nome
                ]);
            }
        }
    }
}
