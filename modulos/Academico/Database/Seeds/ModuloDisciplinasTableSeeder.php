<?php

namespace Modulos\Academico\Database\Seeds;

use Illuminate\Database\Seeder;
use Modulos\Academico\Models\Disciplina;
use Modulos\Academico\Models\ModuloDisciplina;
use Modulos\Academico\Models\ModuloMatriz;

class ModuloDisciplinasTableSeeder extends Seeder
{
    public function run()
    {
        $modulosMatrizes = ModuloMatriz::all();

        $skip = 0;
        $lastMatriz = 0;
        foreach ($modulosMatrizes as $mod) {
            $nivelCurso = $mod->matriz->curso->crs_nvc_id;

            // pega 4 disciplinas desse nivel de curso
            $disciplinas = Disciplina::where('dis_nvc_id', $nivelCurso)->skip($skip)->take(4)->get();

            foreach ($disciplinas as $disciplina) {
                $moduloDisciplina = new ModuloDisciplina();

                $moduloDisciplina->mdc_dis_id = $disciplina->dis_id;
                $moduloDisciplina->mdc_mdo_id = $mod->mdo_id;
                $moduloDisciplina->mdc_tipo_avaliacao = 'numerica';
                $moduloDisciplina->mdc_tipo_disciplina = 'obrigatoria';
                $moduloDisciplina->mdc_pre_requisitos = null;

                $moduloDisciplina->save();
            }

            $skip += 4;
            if ($mod->mdo_mtc_id != $lastMatriz && $lastMatriz > 0) {
                $skip = 0;
            }

            $lastMatriz = $mod->mdo_mtc_id;
        }
    }
}
