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

            $disciplinas = Disciplina::where('dis_nvc_id', $nivelCurso)->get();

            $j = 0;
            for ($i = 0; $i < $modulos->count(); $i++) {
                $tipoDisciplina = 'obrigatoria';

                // pega 3 disciplinas do nivel do curso
                $take = 3;

                // se for o ultimo modulo, pega somente 1 disciplina
                if (($i+1) == $modulos->count()) {
                    $take = 1;
                    $tipoDisciplina = 'tcc';
                }

                $n = $j + $take;
                for (;$j < $n; $j++) {
                    $moduloDisciplina = new ModuloDisciplina();

                    $moduloDisciplina->mdc_dis_id = $disciplinas[$j]->dis_id;
                    $moduloDisciplina->mdc_mdo_id = $modulos[$i]->mdo_id;
                    $moduloDisciplina->mdc_tipo_disciplina = $tipoDisciplina;
                    $moduloDisciplina->mdc_pre_requisitos = null;

                    $moduloDisciplina->save();
                }
            }
        }
    }
}
