<?php

namespace Modulos\Academico\Database\Seeds;

use Illuminate\Database\Seeder;
use Modulos\Academico\Models\SituacaoMatriculaDisciplina;

class SituacoesMatriculaDisciplinaTableSeeder extends Seeder
{
    public function run()
    {
        $situacao = new SituacaoMatriculaDisciplina();
        $situacao->stm_nome = 'Aprovado por Media';
        $situacao->save();

        $situacao = new SituacaoMatriculaDisciplina();
        $situacao->stm_nome = 'Aprovado por Final';
        $situacao->save();

        $situacao = new SituacaoMatriculaDisciplina();
        $situacao->stm_nome = 'Reprovado por Media';
        $situacao->save();

        $situacao = new SituacaoMatriculaDisciplina();
        $situacao->stm_nome = 'Reprovado por Final';
        $situacao->save();
    }
}