<?php

namespace Modulos\Academico\Http\Controllers;

use Modulos\Academico\Http\Requests\OfertaDisciplinaRequest;
use Modulos\Academico\Models\OfertaDisciplina;
use Modulos\Academico\Repositories\AlunoRepository;
use Modulos\Academico\Repositories\CursoRepository;
use Modulos\Academico\Repositories\MatriculaCursoRepository;
use Modulos\Academico\Repositories\MatriculaOfertaDisciplinaRepository;
use Modulos\Academico\Repositories\ModuloDisciplinaRepository;
use Modulos\Academico\Repositories\OfertaDisciplinaRepository;
use Modulos\Academico\Repositories\PeriodoLetivoRepository;
use Modulos\Academico\Repositories\ProfessorRepository;
use Modulos\Academico\Repositories\TurmaRepository;
use Modulos\Seguranca\Providers\ActionButton\Facades\ActionButton;
use Modulos\Seguranca\Providers\ActionButton\TButton;
use Modulos\Core\Http\Controller\BaseController;
use Illuminate\Http\Request;

class AproveitamentoEstudosController extends BaseController
{

  protected $ofertadisciplinaRepository;
  protected $modulodisciplinaRepository;
  protected $periodoletivoRepository;
  protected $cursoRepository;
  protected $alunoRepository;
  protected $matriculaRepository;
  protected $matriculaOfertaDisciplinaRepository;

  public function __construct(OfertaDisciplinaRepository $ofertadisciplinaRepository,
                              ModuloDisciplinaRepository $modulodisciplinaRepository,
                              PeriodoLetivoRepository $periodoletivoRepository,
                              CursoRepository $cursoRepository,
                              AlunoRepository $alunoRepository,
                              MatriculaCursoRepository $matriculaCursoRepository,
                              MatriculaOfertaDisciplinaRepository $matriculaOfertaDisciplinaRepository)
  {
      $this->ofertadisciplinaRepository = $ofertadisciplinaRepository;
      $this->modulodisciplinaRepository = $modulodisciplinaRepository;
      $this->periodoletivoRepository = $periodoletivoRepository;
      $this->cursoRepository = $cursoRepository;
      $this->alunoRepository = $alunoRepository;
      $this->matriculaRepository = $matriculaCursoRepository;
      $this->matriculaOfertaDisciplinaRepository = $matriculaOfertaDisciplinaRepository;
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

    public function postAproveitarDisciplina()
    {
        return 0;
    }

}
