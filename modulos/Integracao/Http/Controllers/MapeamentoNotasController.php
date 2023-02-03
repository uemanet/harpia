<?php

namespace Modulos\Integracao\Http\Controllers;

use Validator;
use Illuminate\Http\Request;
use Modulos\Core\Http\Controller\BaseController;
use Modulos\Academico\Repositories\CursoRepository;
use Modulos\Academico\Repositories\TurmaRepository;
use Modulos\Academico\Repositories\OfertaDisciplinaRepository;
use Modulos\Integracao\Repositories\MapeamentoNotasRepository;
use Modulos\Academico\Repositories\MatriculaOfertaDisciplinaRepository;

class MapeamentoNotasController extends BaseController
{
    protected $cursoRepository;
    protected $mapeamentoNotasRepository;
    protected $turmaRepository;
    protected $ofertaDisciplinaRepository;
    protected $matriculaOfertaDisciplinaRepository;

    public function __construct(
        CursoRepository $cursoRepository,
        TurmaRepository $turmaRepository,
        MapeamentoNotasRepository $mapeamentoNotasRepository,
        OfertaDisciplinaRepository $ofertaDisciplinaRepository,
        MatriculaOfertaDisciplinaRepository $matriculaOfertaDisciplinaRepository
    ) {
        $this->cursoRepository = $cursoRepository;
        $this->mapeamentoNotasRepository = $mapeamentoNotasRepository;
        $this->turmaRepository = $turmaRepository;
        $this->ofertaDisciplinaRepository = $ofertaDisciplinaRepository;
        $this->matriculaOfertaDisciplinaRepository = $matriculaOfertaDisciplinaRepository;
    }

    public function index(Request $request)
    {
        $cursos = $this->cursoRepository->lists('crs_id', 'crs_nome');

        if ($request->getMethod() == 'POST') {
            $rules = [
                'crs_id' => 'required',
                'ofc_id' => 'required',
                'trm_id' => 'required'
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return redirect()->route('integracao.mapeamentonotas.index')->withErrors($validator);
            }

            $turmaId = $request->input('trm_id');

            $turma = $this->turmaRepository->find($turmaId);

            if (!$turma) {
                flash()->error('Turma inexistente!');
                return redirect()->back();
            }

            $ofertas = $this->mapeamentoNotasRepository->getGradeCurricularByTurma($turmaId);

            $turma = $this->turmaRepository->find($turmaId);

            if($turma->trm_tipo_integracao === 'v2'){

                $html = view('Integracao::mapeamentonotas.ajax.disciplinasV2', compact('cursos', 'ofertas', 'turma'))->render();
                return response()->json(['html' => $html]);
            }

            $html = view('Integracao::mapeamentonotas.ajax.disciplinas', compact('cursos', 'ofertas', 'turma'))->render();

            return response()->json(['html' => $html]);
        }

        return view('Integracao::mapeamentonotas.index', compact('cursos'));
    }

    public function showAlunos(Request $request)
    {
        $ofertaId = $request->id;

        $ofertaDisciplina = $this->ofertaDisciplinaRepository->find($ofertaId);

        if (!$ofertaDisciplina) {
            flash()->error('Oferta de Disciplina não existe!');
            return redirect()->back();
        }

        $search = $request->all();
        $search['mof_ofd_id'] = $ofertaId;
        $search['mof_situacao_matricula'] = 'cancelado';

        $tableData = $this->matriculaOfertaDisciplinaRepository->paginateRequest($search);

        $tabela = null;
        $paginacao = null;

        if ($tableData->count()) {
            $tabela = $tableData->columns(array(
                'mof_id' => '#',
                'pes_nome' => 'Nome',
                'mof_nota1' => 'Nota 1',
                'mof_nota2' => 'Nota 2',
                'mof_nota3' => 'Nota 3',
                'mof_conceito' => 'Conceito',
                'mof_recuperacao' => 'Recuperação',
                'mof_final' => 'Final',
                'mof_mediafinal' => 'Média Final',
                'mof_situacao_matricula' => 'Situação',
                'mof_action' => 'Ações'
            ))
                ->attributes(array(
                    'class' => 'table table-striped table-bordered'
                ))
                ->modifyCell('mof_id', function () {
                    return array('style' => 'width: 3%;');
                })
                ->modifyCell('mof_nota1', function () {
                    return array('style' => 'width: 5%;', 'class' => 'text-center');
                })
                ->modify('mof_nota1', function ($row) {
                    return is_null($row->mof_nota1) ? '---' : number_format($row->mof_nota1, 2);
                })
                ->modifyCell('mof_nota2', function () {
                    return array('style' => 'width: 5%;', 'class' => 'text-center');
                })
                ->modify('mof_nota2', function ($row) {
                    return is_null($row->mof_nota2) ? '---' : number_format($row->mof_nota2, 2);
                })
                ->modifyCell('mof_nota3', function () {
                    return array('style' => 'width: 5%;', 'class' => 'text-center');
                })
                ->modify('mof_nota3', function ($row) {
                    return is_null($row->mof_nota3) ? '---' : number_format($row->mof_nota3, 2);
                })
                ->modifyCell('mof_conceito', function () {
                    return array('style' => 'width: 5%;', 'class' => 'text-center');
                })
                ->modify('mof_conceito', function ($row) {
                    return is_null($row->mof_conceito) ? '---' : $row->mof_conceito;
                })
                ->modifyCell('mof_recuperacao', function () {
                    return array('style' => 'width: 5%;', 'class' => 'text-center');
                })
                ->modify('mof_recuperacao', function ($row) {
                    return is_null($row->mof_recuperacao) ? '---' : number_format($row->mof_recuperacao, 2);
                })
                ->modifyCell('mof_final', function () {
                    return array('style' => 'width: 5%;', 'class' => 'text-center');
                })
                ->modify('mof_final', function ($row) {
                    return is_null($row->mof_final) ? '---' : number_format($row->mof_final, 2);
                })
                ->modifyCell('mof_mediafinal', function () {
                    return array('style' => 'width: 8%;', 'class' => 'text-center');
                })
                ->modify('mof_mediafinal', function ($row) {
                    return is_null($row->mof_mediafinal) ? '---' : number_format($row->mof_mediafinal, 2);
                })
                ->modifyCell('mof_situacao_matricula', function () {
                    return array('style' => 'width: 5%;');
                })
                ->modify('mof_situacao_matricula', function ($row) {
                    switch ($row->mof_situacao_matricula) {
                        case 'cursando':
                            return "<span class='label label-info'>Cursando</span>";
                            break;
                        case 'aprovado_media':
                            return "<span class='label label-success'>Aprovado por Média</span>";
                            break;
                        case 'aprovado_final':
                            return "<span class='label label-success'>Aprovado por Final</span>";
                            break;
                        case 'reprovado_media':
                            return "<span class='label label-danger'>Reprovado por Média</span>";
                            break;
                        case 'reprovado_final':
                            return "<span class='label label-danger'>Reprovado por Final</span>";
                            break;
                        case 'cancelado':
                            return "<span class='label label-warning'>Cancelado</span>";
                            break;
                    }
                })
                ->modifyCell('mof_action', function () {
                    return array('style' => 'width: 10%');
                })
                ->modify('mof_action', function ($row) {
                    return "<a href='" . route('integracao.mapeamentonotas.aluno', $row->mof_id) . "' class='btn bg-orange'><i class='fa fa-exchange'></i> Migrar Notas</a>";
                })
                ->sortable(array('mof_id', 'pes_nome'));

            $paginacao = $tableData->appends($request->except('page'));
        }

        return view('Integracao::mapeamentonotas.alunos', compact('tabela', 'paginacao', 'ofertaDisciplina'));
    }

    public function mapearNotasAluno($matriculaOfertaId)
    {
        $matriculaOfertaDisciplina = $this->matriculaOfertaDisciplinaRepository->find($matriculaOfertaId);

        if (!$matriculaOfertaDisciplina) {
            flash()->error('Matricula na Oferta de Disciplina não existe.');

            return redirect()->back();
        }

        $ofertaDisciplina = $this->ofertaDisciplinaRepository->find($matriculaOfertaDisciplina->mof_ofd_id);

        if (!$matriculaOfertaDisciplina) {
            flash()->error('Oferta de Disciplina não existe.');

            return redirect()->back();
        }

        // Busca as configurações de notas do curso
        $configuracoesCurso = $ofertaDisciplina->turma->ofertaCurso->curso->configuracoes->pluck('cfc_valor', 'cfc_nome')->toArray();

        if($ofertaDisciplina->turma->trm_tipo_integracao == 'v2'){
            $response = $this->mapeamentoNotasRepository->mapearNotasAlunoV2($ofertaDisciplina, $matriculaOfertaDisciplina, $configuracoesCurso);
        } else {
            $response = $this->mapeamentoNotasRepository->mapearNotasAluno($ofertaDisciplina, $matriculaOfertaDisciplina, $configuracoesCurso);
        }


        flash()->{$response['status']}($response['message']);

        return redirect()->route('integracao.mapeamentonotas.showalunos', $matriculaOfertaDisciplina->mof_ofd_id);
    }
}
