<?php

namespace Modulos\Academico\Database\Seeds\Development;

use Illuminate\Database\Seeder;
use Modulos\Academico\Models\ModuloDisciplina;
use Modulos\Academico\Models\ModuloMatriz;
use Modulos\Academico\Models\OfertaDisciplina;
use Modulos\Academico\Models\Turma;
use DB;

class OfertaDisciplinasTableSeeder extends Seeder
{
    public function run()
    {
        $turmas = Turma::all();

        foreach ($turmas as $turma) {
            $modulos = $turma->ofertacurso->matriz->modulos;

            for ($i = 0; $i < $modulos->count(); $i++) {
                $disciplinas = ModuloDisciplina::where('mdc_mdo_id', '=', $modulos[$i]->mdo_id)->get();

                foreach ($disciplinas as $disciplina) {
                    $professor = DB::table('acd_professores')->inRandomOrder()->first();
                    $ofertaDisciplina = new OfertaDisciplina();

                    $ofertaDisciplina->ofd_mdc_id = $disciplina->mdc_id;
                    $ofertaDisciplina->ofd_trm_id = $turma->trm_id;
                    $ofertaDisciplina->ofd_per_id = $i+1;
                    $ofertaDisciplina->ofd_prf_id = $professor->prf_id;
                    $ofertaDisciplina->ofd_tipo_avaliacao = 'numerica';
                    $ofertaDisciplina->ofd_qtd_vagas = 500;

                    $ofertaDisciplina->save();
                }
            }
        }
    }
}
