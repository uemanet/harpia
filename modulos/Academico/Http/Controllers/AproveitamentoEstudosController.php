<?php

namespace Modulos\Academico\Http\Controllers;


use Modulos\Academico\Repositories\AlunoRepository;
use Modulos\Academico\Repositories\CursoRepository;
use Modulos\Academico\Repositories\MatriculaCursoRepository;
use Modulos\Academico\Repositories\MatriculaOfertaDisciplinaRepository;
use Modulos\Academico\Repositories\ModuloDisciplinaRepository;
use Modulos\Academico\Repositories\OfertaDisciplinaRepository;
use Modulos\Academico\Repositories\PeriodoLetivoRepository;
use Modulos\Seguranca\Providers\ActionButton\Facades\ActionButton;
use Modulos\Core\Http\Controller\BaseController;
use Illuminate\Http\Request;
use Modulos\Academico\Repositories\AproveitamentoEstudosRepository;


class AproveitamentoEstudosController extends BaseController
{

  protected $ofertadisciplinaRepository;
  protected $modulodisciplinaRepository;
  protected $periodoletivoRepository;
  protected $cursoRepository;
  protected $alunoRepository;
  protected $matriculaRepository;
  protected $matriculaOfertaDisciplinaRepository;
  protected $aproveitamentoEstudosRepository;

  public function __construct(OfertaDisciplinaRepository $ofertadisciplinaRepository,
                              ModuloDisciplinaRepository $modulodisciplinaRepository,
                              PeriodoLetivoRepository $periodoletivoRepository,
                              CursoRepository $cursoRepository,
                              AlunoRepository $alunoRepository,
                              MatriculaCursoRepository $matriculaCursoRepository,
                              MatriculaOfertaDisciplinaRepository $matriculaOfertaDisciplinaRepository,
                              AproveitamentoEstudosRepository $aproveitamentoEstudosRepository)
  {
      $this->ofertadisciplinaRepository = $ofertadisciplinaRepository;
      $this->modulodisciplinaRepository = $modulodisciplinaRepository;
      $this->periodoletivoRepository = $periodoletivoRepository;
      $this->cursoRepository = $cursoRepository;
      $this->alunoRepository = $alunoRepository;
      $this->matriculaRepository = $matriculaCursoRepository;
      $this->matriculaOfertaDisciplinaRepository = $matriculaOfertaDisciplinaRepository;
      $this->aproveitamentoEstudosRepository = $aproveitamentoEstudosRepository;
  }

    public function getIndex(Request $request)
    {
        $paginacao = null;
        $tabela = null;

        $tableData = $this->alunoRepository->paginateRequest($request->all(), null, true);

        if ($tableData->count()) {
            $tabela = $tableData->columns(array(
                'alu_id' => '#',
                'pes_nome' => 'Nome',
                'pes_email' => 'Email',
                'alu_action' => 'Ações'
            ))
                ->modifyCell('alu_action', function () {
                    return array('style' => 'width: 140px;');
                })
                ->means('alu_action', 'alu_id')
                ->modify('alu_action', function ($id) {
                    return ActionButton::grid([
                        'type' => 'LINE',
                        'buttons' => [
                            [
                                'classButton' => 'btn btn-primary',
                                'icon' => 'fa fa-eye',
                                'route' => 'academico.aproveitamentoestudos.show',
                                'parameters' => ['id' => $id],
                                'label' => '',
                                'method' => 'get'
                            ],
                        ]
                    ]);
                })
                ->sortable(array('alu_id', 'pes_nome'));

            $paginacao = $tableData->appends($request->except('page'));
        }

        return view('Academico::aproveitamentoestudos.index', ['tabela' => $tabela, 'paginacao' => $paginacao]);
    }

    public function getShow($alunoId)
    {
        $aluno = $this->alunoRepository->find($alunoId);

        if (!$aluno) {
            flash()->error('Aluno não existe!');
            return redirect()->route('academico.matriculasofertasdisciplinas.index');
        }

        $matriculas = $this->matriculaRepository->findAllVinculo(['mat_alu_id' => $alunoId, 'mat_situacao' => 'cursando']);

        $periodoletivo = $this->periodoletivoRepository->lists('per_id', 'per_nome');

        return view('Academico::aproveitamentoestudos.show', [
            'pessoa' => $aluno->pessoa,
            'aluno' => $aluno,
            'matriculas' => $matriculas,
            'periodoletivo' => $periodoletivo
        ]);
    }


    public function postAproveitarDisciplina(Request $request,$ofertaId, $matriculaId)
    {
        try {
            $aproveitamento = $this->aproveitamentoEstudosRepository->aproveitarDisciplina($ofertaId, $matriculaId ,$request->except('_token'));

            if (!$aproveitamento) {
                flash()->error('Erro ao tentar salvar.');
                return redirect()->back()->withInput($request->all());
            }

            if ($aproveitamento['type'] == 'error') {
                flash()->error($aproveitamento['message']);
                return redirect()->back()->withInput($request->all());
            };

            flash()->success($aproveitamento['message']);

            $matricula = $this->matriculaRepository->find($matriculaId);
            $aluno = $this->alunoRepository->find($matricula->mat_alu_id);
            $alunoId = $aluno->alu_id;
            $matriculas = $this->matriculaRepository->findAllVinculo(['mat_alu_id' => $alunoId, 'mat_situacao' => 'cursando']);
            $aluno = $this->alunoRepository->find($alunoId);
            $periodoletivo = $this->periodoletivoRepository->lists('per_id', 'per_nome');

            return view('Academico::aproveitamentoestudos.show', [
                'pessoa' => $aluno->pessoa,
                'aluno' => $aluno,
                'matriculas' => $matriculas,
                'periodoletivo' => $periodoletivo
            ]);
        } catch (\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            }

            flash()->error('Erro ao tentar salvar. Caso o problema persista, entre em contato com o suporte.');
            return redirect()->back();
        }
    }

}
