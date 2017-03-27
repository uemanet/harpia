<?php

namespace Modulos\Academico\Database\Seeds;

use Illuminate\Database\Seeder;
use Modulos\Academico\Models\LancamentoTcc;
use Modulos\Academico\Models\Matricula;
use Modulos\Academico\Models\MatriculaOfertaDisciplina;
use DB;

class MatriculasConcluidasSeeder extends Seeder
{
    public function run()
    {
        // Concluir 100 matriculas

        $matriculas = Matricula::where('mat_situacao', 'cursando')->inRandomOrder()->take(100)->get();

        foreach ($matriculas as $matricula) {
            $disciplinasCursadas = MatriculaOfertaDisciplina::where('mof_mat_id', $matricula->mat_id)->get();

            if ($disciplinasCursadas->count()) {
                foreach ($disciplinasCursadas as $disciplina) {
                    $disciplina->mof_nota1 = 7;
                    $disciplina->mof_nota2 = 7;
                    $disciplina->mof_nota3 = 7;
                    $disciplina->mof_mediafinal = 7;
                    $disciplina->mof_situacao_matricula = 'aprovado_media';

                    $disciplina->save();
                }

                $lancamentoTcc = new LancamentoTcc();
                $lancamentoTcc->ltc_prf_id = DB::table('acd_professores')->inRandomOrder()->first()->prf_id;
                $lancamentoTcc->ltc_titulo = 'Monografia';
                $lancamentoTcc->ltc_tipo = 'monografia';
                $lancamentoTcc->ltc_data_apresentacao = date('d/m/Y');

                $lancamentoTcc->save();

                $matricula->mat_ltc_id = $lancamentoTcc->ltc_id;
                $matricula->mat_situacao = 'concluido';
                $matricula->mat_data_conclusao = date('d/m/Y');

                $matricula->save();
            }
        }
    }
}
