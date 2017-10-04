<?php
declare(strict_types=1);

namespace Modulos\Academico\Repositories;

use Modulos\Academico\Models\Matricula;
use Modulos\Academico\Models\Turma;
use Modulos\Core\Repository\BaseRepository;

class ResultadosFinaisRepository extends BaseRepository
{
    public function __construct(Matricula $matricula)
    {
        parent::__construct($matricula);
    }

    public function create(array $data)
    {
        throw new \Exception("Cannot create entry for " . json_encode($data));
    }

    public function update(array $data, $id, $attribute = null)
    {
        throw new \Exception("Cannot update entry for " . json_encode([$data, $id, $attribute]));
    }

    public function delete($id)
    {
        throw new \Exception("Cannot delete entry for " . $id);
    }

    public function getResultadosFinais(Turma $turma, $polo = null, $situacao = "")
    {
        $resultados = [];

        $matriculas = $turma->matriculas;

        // Retira alunos que ainda estao cursando na turma
        $matriculas = $matriculas->filter(function ($value, $key) {
            return $value->mat_situacao != "cursando";
        });

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
        return $resultadosMatricula;
    }
}
