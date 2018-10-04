<?php

namespace Modulos\Academico\Repositories;

use DB;
use Validator;
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
        $aproveitadas = [];

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

            // Verifica se o aluno já tem a disciplina aproveitada
            $aproveitada = DB::table('acd_matriculas_ofertas_disciplinas')
                ->join('acd_ofertas_disciplinas', 'mof_ofd_id', '=', 'ofd_id')
                ->join('acd_periodos_letivos', 'ofd_per_id', 'per_id')
                ->join('acd_modulos_disciplinas','ofd_mdc_id', '=', 'mdc_id')
                ->join('acd_disciplinas', 'mdc_dis_id', '=', 'dis_id')
                ->where('mof_ofd_id', $ofertaDisciplina->ofd_id)
                ->where('mof_mat_id', $matricula->mat_id)
                ->where('mof_tipo_matricula', 'aproveitamento')
                ->whereIn('mof_situacao_matricula', ['cursando', 'aprovado_media', 'aprovado_final'])
                ->first();

            //Caso a condição seja verdadeira, adicionar disciplina no array de retorno
            if ($aproveitada) {

                $aproveitadas[] = $aproveitada;
            }


        }

        return ['naomatriculadas' => $naomatriculadas, 'aproveitadas' => $aproveitadas];
    }

    public function getCourseConfiguration($ofertaId)
    {

        $oferta = $this->ofertaDisciplinaRepository->find($ofertaId);

        $turma = $oferta->turma;

        $tipo_avaliacao = $oferta->ofd_tipo_avaliacao;

        $configuracoes = $turma->ofertacurso->curso->configuracoes->where('cfc_nome', '=', 'conceitos_aprovacao')->first();
        $configuracoes = json_decode($configuracoes->cfc_valor);
        $mediaminima = $turma->ofertacurso->curso->configuracoes->where('cfc_nome', '=', 'media_min_aprovacao')->first();
        $mediaminima = $mediaminima->cfc_valor;

        $conf = [];

        foreach ($configuracoes as  $configuracao) {
            $conf[$configuracao] = $configuracao;
        }

        return [
            'avaliacao' => $tipo_avaliacao,
            'turma' => $turma,
            'configuracoes' => $conf,
            'min' => $mediaminima
        ];

    }
    public function aproveitarDisciplina($ofertaId, $matriculaId, $dados)
    {
        $dados['mof_ofd_id'] = $ofertaId;
        $dados['mof_mat_id'] = $matriculaId;
        $dados['mof_tipo_matricula'] = 'aproveitamento';
        $dados['mof_situacao_matricula'] = 'aprovado_media';

        $matriculaoferta = $this->model
            ->where('mof_mat_id', '=', $matriculaId)
            ->where('mof_ofd_id', '=', $ofertaId)
            ->where('mof_situacao_matricula', '<>',  'cancelado')->get();

        if ($matriculaoferta->count()){
            return array("type" => "error", "message" => "Aluno já foi matriculado nessa disciplina");
        }

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

        if ($ofertaDisciplina->getOriginal('ofd_tipo_avaliacao') == 'numerica' && !$ofertaDisciplina->turma->trm_integrada) {
            $rules = [
                'mof_observacao' => 'required',
                'mof_mediafinal' => 'required|numeric'
            ];

            $validator = Validator::make($dados, $rules);

            if ($validator->fails()) {
                return array("type" => "error", "message" => "O cadastro contém erros no formulário");
            }

            $mediaminima = $ofertaDisciplina->turma->ofertacurso->curso->configuracoes->where('cfc_nome', '=', 'media_min_aprovacao')->first();
            $mediaminima = $mediaminima->cfc_valor;
            if ($dados['mof_mediafinal'] < $mediaminima || $dados['mof_mediafinal'] > 10 ){
                return array("type" => "error", "message" => "O cadastro contém erros no formulário");
            }
        } else if ($ofertaDisciplina->getOriginal('ofd_tipo_avaliacao') == 'conceitual' && !$ofertaDisciplina->turma->trm_integrada){
            $rules = [
                'mof_observacao' => 'required',
                'mof_conceito' => 'required'
            ];
            $validator = Validator::make($dados, $rules);

            if ($validator->fails()) {
                return array("type" => "error", "message" => "O cadastro contém erros no formulário");
            }

            $configuracoes = $ofertaDisciplina->turma->ofertacurso->curso->configuracoes->where('cfc_nome', '=', 'conceitos_aprovacao')->first();
            $configuracoes = json_decode($configuracoes->cfc_valor);

            if(!in_array($dados['mof_conceito'], $configuracoes)){
                return array("type" => "error", "message" => "O cadastro contém erros no formulário");
            }
        } else{
            $rules = [
                'mof_observacao' => 'required'
            ];
            $validator = Validator::make($dados, $rules);

            if ($validator->fails()) {
                return array("type" => "error", "message" => "O cadastro contém erros no formulário");
            }
        }

        $matriculaoferta = $this->create($dados);

        return array('type' => 'success', 'message' => 'Aproveitamento de Disciplina Criado com sucesso!');
    }


}
