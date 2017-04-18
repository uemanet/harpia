<?php

namespace Modulos\Academico\Database\Seeds\Development;

use Illuminate\Database\Seeder;
use Modulos\Academico\Models\MatriculaOfertaDisciplina;
use Modulos\Academico\Models\OfertaDisciplina;
use Modulos\Academico\Models\Turma;

class MatriculaOfertaDisciplinaTableSeeder extends Seeder
{
    public function run()
    {
        $turmas = Turma::all();

        foreach ($turmas as $turma) {
            $matriculas = $turma->matriculas;

            $ofertas = OfertaDisciplina::where('ofd_trm_id', $turma->trm_id)->get();

            foreach ($matriculas as $matricula) {
                foreach ($ofertas as $oferta) {
                    $matriculaOfertaDisciplina = new MatriculaOfertaDisciplina();

                    $matriculaOfertaDisciplina->mof_mat_id = $matricula->mat_id;
                    $matriculaOfertaDisciplina->mof_ofd_id = $oferta->ofd_id;
                    $matriculaOfertaDisciplina->mof_tipo_matricula = 'matriculacomum';
                    $matriculaOfertaDisciplina->mof_situacao_matricula = 'cursando';

                    $matriculaOfertaDisciplina->save();
                }
            }
        }
    }
}
