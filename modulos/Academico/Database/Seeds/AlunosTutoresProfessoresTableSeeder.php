<?php

namespace Modulos\Academico\Database\Seeds;

use Illuminate\Database\Seeder;
use Modulos\Academico\Models\Aluno;
use Modulos\Academico\Models\Professor;
use Modulos\Academico\Models\Tutor;

class AlunosTutoresProfessoresTableSeeder extends Seeder
{
    public function run()
    {
        // cadastra 400 alunos
        for ($i=1;$i<=400;$i++) {
            $aluno = new Aluno();

            $aluno->alu_pes_id = $i;

            $aluno->save();
        }

        // cadastra 50 professores
        for ($i+=1;$i<=450;$i++) {
            $professor = new Professor();

            $professor->prf_pes_id = $i;

            $professor->save();
        }

        // cadastra 50 tutores
        for ($i+=1;$i<=500;$i++) {
            $tutor = new Tutor();

            $tutor->tut_pes_id = $i;

            $tutor->save();
        }
    }
}
