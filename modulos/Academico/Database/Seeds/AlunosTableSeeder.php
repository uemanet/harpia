<?php

namespace Modulos\Academico\Database\Seeds;

use Illuminate\Database\Seeder;
use Modulos\Academico\Models\Aluno;

class AlunosTableSeeder extends Seeder
{
    public function run()
    {
        for ($i = 30; $i <= 200; $i++) {
            $aluno = new Aluno();

            $aluno->alu_pes_id = $i;

            $aluno->save();
        }
    }
}
