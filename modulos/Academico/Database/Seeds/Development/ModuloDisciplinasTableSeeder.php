<?php

namespace Modulos\Academico\Database\Seeds\Development;

use Illuminate\Database\Seeder;
use Modulos\Academico\Models\Disciplina;
use Modulos\Academico\Models\MatrizCurricular;
use Modulos\Academico\Models\ModuloDisciplina;
use Modulos\Academico\Models\ModuloMatriz;

class ModuloDisciplinasTableSeeder extends Seeder
{
    public function run()
    {
        $matrizesCurriculares = MatrizCurricular::all();

        foreach ($matrizesCurriculares as $matriz) {
            $nivelCurso = $matriz->curso->crs_nvc_id;

            $modulos = $matriz->modulos;

            $m = 1;
            $skip = 0;
            foreach ($modulos as $modulo) {

                // pega 4 disciplinas desse nivel de curso
                $disciplinas = Disciplina::where('dis_nvc_id', $nivelCurso)->skip($skip)->take(4)->get();

                $d = 1;
                foreach ($disciplinas as $disciplina) {
                    $moduloDisciplina = new ModuloDisciplina();

                    $moduloDisciplina->mdc_dis_id = $disciplina->dis_id;
                    $moduloDisciplina->mdc_mdo_id = $modulo->mdo_id;
                    $moduloDisciplina->mdc_tipo_avaliacao = 'numerica';
                    $moduloDisciplina->mdc_tipo_disciplina = 'obrigatoria';

                    if (($m == $modulos->count()) && ($d == 4)) {
                        $moduloDisciplina->mdc_tipo_disciplina = 'tcc';
                    }

                    $moduloDisciplina->mdc_pre_requisitos = null;

                    $moduloDisciplina->save();

                    $d += 1;
                }

                $skip += 4;
                $m += 1;
            }
        }
    }
}
