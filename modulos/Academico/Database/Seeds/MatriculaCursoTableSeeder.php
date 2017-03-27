<?php

namespace Modulos\Academico\Database\Seeds;

use Illuminate\Database\Seeder;
use Modulos\Academico\Models\Matricula;
use Modulos\Academico\Models\Turma;
use DB;

class MatriculaCursoTableSeeder extends Seeder
{
    public function run()
    {
        $turmas = Turma::all();

        // matricula 50 alunos por turma
        $skip = 0;

        foreach ($turmas as $turma) {
            $alunos = DB::table('acd_alunos')->skip($skip)->take(50)->get();
            $polos = $turma->ofertacurso->polos;

            foreach ($alunos as $aluno) {
                $matricula = new Matricula();

                $matricula->mat_alu_id = $aluno->alu_id;
                $matricula->mat_trm_id = $turma->trm_id;

                $polo = $polos->random();

                $matricula->mat_pol_id = $polo->pol_id;
                $matricula->mat_grp_id = null;
                $matricula->mat_situacao = 'cursando';
                $matricula->mat_modo_entrada = 'vestibular';

                $matricula->save();
            }

            $skip += 50;
        }
    }
}
