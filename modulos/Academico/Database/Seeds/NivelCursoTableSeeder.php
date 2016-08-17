<?php

namespace Modulos\Academico\Database\Seeds;

use Illuminate\Database\Seeder;
use Modulos\Academico\Models\NivelCurso;

class NivelCursoTableSeeder extends Seeder
{
    public function run()
    {
        $nivel = new NivelCurso();
        $nivel->nvc_nome = 'Técnico';
        $nivel->save();

        $nivel = new NivelCurso();
        $nivel->nvc_nome = 'Graduação';
        $nivel->save();

        $nivel = new NivelCurso();
        $nivel->nvc_nome = 'Pós-graduação';
        $nivel->save();
    }
}
