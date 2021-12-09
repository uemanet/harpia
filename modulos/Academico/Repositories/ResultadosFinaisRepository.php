<?php
declare(strict_types=1);

namespace Modulos\Academico\Repositories;

use Modulos\Academico\Models\Polo;
use Modulos\Academico\Models\Turma;
use Modulos\Academico\Models\Matricula;

class ResultadosFinaisRepository
{
    protected $matricula;

    public function __construct(Matricula $matricula)
    {
        $this->matricula = $matricula;
    }

    public function getResultadosFinais(Turma $turma, Polo $polo = null, string $situacao = "")
    {
        $resultados = [];

        $matriculas = $turma->matriculas;

//        // Retira alunos que ainda estao cursando na turma
//        $matriculas = $matriculas->filter(function ($value, $key) {
//            return $value->mat_situacao != "cursando";
//        });

        // Filtra por polo
        if ($polo) {
            $matriculas = $matriculas->filter(function ($value, $key) use ($polo) {
                return $value->mat_pol_id == $polo->pol_id;
            });
        }

        // Filtra por situacao
        if ($situacao) {
            $matriculas = $matriculas->filter(function ($value, $key) use ($situacao) {
                return $value->mat_situacao == $situacao;
            });
        }

        // Resultados individuais dos alunos
        foreach ($matriculas as $matricula) {
            $resultados[$matricula->polo->pol_nome][$matricula->aluno->pessoa->pes_nome] = $this->getResultadosMatricula($matricula);
        }
;
        return $resultados;
    }

    private function getResultadosMatricula(Matricula $matricula) : array
    {
        $resultadosMatricula = [];

        foreach ($matricula->matriculasOfertasDisciplinas as $matriculaOfertaDisciplina) {
            $disciplina = $matriculaOfertaDisciplina->getDisciplina();
            $resultado['idDisciplina'] = $disciplina->dis_id;
            $resultado['idOferta'] = $matriculaOfertaDisciplina->mof_ofd_id;
            $resultado['media'] = $matriculaOfertaDisciplina->mof_mediafinal;

            if ($matriculaOfertaDisciplina->ofertaDisciplina->ofd_tipo_avaliacao == 'Conceitual') {
                $resultado['media'] = $matriculaOfertaDisciplina->mof_conceito;
            }

            $modulo = $matriculaOfertaDisciplina->getModuloDisciplina()->modulo;
            $resultadosMatricula[$modulo->mdo_nome][$disciplina->dis_nome] = $resultado;
        }

        // Ordenacao
        foreach ($resultadosMatricula as $key => $resultadoModulo) {
            ksort($resultadoModulo);
            $resultadosMatricula[$key] = $resultadoModulo;
        }

        $resultadosMatricula["idAluno"] = $matricula->mat_alu_id;
        $resultadosMatricula["situacao"] = $matricula->situacao_matricula_curso;
        if($resultadosMatricula["situacao"] == 'Cursando' ){
            $resultadosMatricula["situacao"] = 'Reprovado';
        }
        return $resultadosMatricula;
    }
}
