<?php

namespace Modulos\Academico\Repositories;

use DB;
use Illuminate\Support\Collection;
use Modulos\Academico\Models\Matricula;
use Modulos\Academico\Models\MatriculaOfertaDisciplina;
use Modulos\Academico\Repositories\OfertaDisciplinaRepository;
use Modulos\Core\Repository\BaseRepository;
use Modulos\Academico\Repositories\AlunoRepository;


class AproveitamentoEstudosRepository extends BaseRepository
{
    public function __construct(MatriculaOfertaDisciplina $matriculaofertadisciplina,
                                OfertaDisciplinaRepository $ofertaDisciplinaRepository,
                                AlunoRepository $alunoRepository)
    {
        $this->model = $matriculaofertadisciplina;
        $this->alunoRepository = $alunoRepository;
        $this->ofertaDisciplinaRepository = $ofertaDisciplinaRepository;
    }

    public function getDisciplinesNotEnroledByStudent($alunoId, $turmaId, $periodoId = null)
    {
        // busca as disciplinas ofertadas para a turma e periodo
        $ofertasDisciplinas = DB::table('acd_ofertas_disciplinas')
            ->join('acd_periodos_letivos', 'ofd_per_id', 'per_id')
            ->join('acd_modulos_disciplinas','ofd_mdc_id', '=', 'mdc_id')
            ->join('acd_disciplinas', 'mdc_dis_id', '=', 'dis_id')
            ->where('ofd_trm_id', $turmaId);

        if ($periodoId) {
           $ofertasDisciplinas->where('ofd_per_id', $periodoId);
        }

        $ofertasDisciplinas = $ofertasDisciplinas->get();

        // busca o aluno
        $aluno = $this->alunoRepository->find($alunoId);

        // busca a matricula do aluno na turma
        $matricula = $aluno->matriculas()->where('mat_trm_id', '=', $turmaId)->first();

        $naomatriculadas = [];

        foreach ($ofertasDisciplinas as $ofertaDisciplina) {

            // Verifica se o aluno está cursando ou já foi aprovado na oferta de disciplina
            $matriculaOfertaDisciplina = DB::table('acd_matriculas_ofertas_disciplinas')
                ->where('mof_ofd_id', $ofertaDisciplina->ofd_id)
                ->where('mof_mat_id', $matricula->mat_id)
                ->whereIn('mof_situacao_matricula', ['cursando', 'aprovado_media', 'aprovado_final'])
                ->get();

            //Caso a condição seja falsa, adicionar disciplina no array de retorno
            if (!$matriculaOfertaDisciplina->count()) {
                $naomatriculadas[] = $ofertaDisciplina;
            }

        }

        return $naomatriculadas;
    }

    public function getCourseConfiguration($ofertaId)
    {

        $oferta = $this->ofertaDisciplinaRepository->find($ofertaId);

        $turma = $oferta->turma;

        $tipo_avaliacao = $oferta->ofd_tipo_avaliacao;

        $configuracoes = $turma->ofertacurso->curso->configuracoes->where('cfc_nome', '=', 'conceitos_aprovacao')->first();
        $configuracoes = json_decode($configuracoes->cfc_valor);

        $conf = [];

        foreach ($configuracoes as  $configuracao) {
            $conf[$configuracao] = $configuracao;
        }

        return [
            'avaliacao' => $tipo_avaliacao,
            'turma' => $turma,
            'configuracoes' => $conf
        ];

    }
    public function aproveitarDisciplina($ofertaId, $matriculaId, $dados)
    {

        $matriculaoferta = $this->model
            ->where('mof_mat_id', '=', $matriculaId)
            ->where('mof_ofd_id', '=', $ofertaId)->get();

        if ($matriculaoferta->count()){
            return array("type" => "error", "message" => "Aluno já foi matriculado nessa disciplina");
        }

        $dados['mof_ofd_id'] = $ofertaId;
        $dados['mof_mat_id'] = $matriculaId;
        $dados['mof_tipo_matricula'] = 'aproveitamentointerno';
        $dados['mof_situacao_matricula'] = 'aprovado_media';

        // verifica se o aluno ainda está cursando o curso
        $matricula = Matricula::find($dados['mof_mat_id']);
        if ($matricula->mat_situacao != 'cursando') {
            return array("type" => "error", "message" => "Aluno não está cursando o curso");
        }

        //verifica se a oferta disciplina existe
        $ofertaDisciplina = $this->ofertaDisciplinaRepository->find($dados['mof_ofd_id']);

        if (!$ofertaDisciplina) {
            return array("type" => "error", "message" => "Oferta de disciplina não existe");
        }

        $this->create($dados);

        return array('type' => 'success', 'message' => 'Aproveitamento de Disciplina Criado com sucesso!');
    }


}
