<?php

namespace Modulos\Academico\Database\Seeds\Development;

use Illuminate\Database\Seeder;
use Modulos\Academico\Models\Curso;
use Modulos\Academico\Models\Vinculo;

class VinculoTableSeeder extends Seeder
{
    public function run()
    {
        $cursos = Curso::all();

        foreach ($cursos as $curso) {
            $vinculo = new Vinculo();
            $vinculo->ucr_usr_id = 1;
            $vinculo->ucr_crs_id = $curso->crs_id;
            $vinculo->save();
        }
    }
}
