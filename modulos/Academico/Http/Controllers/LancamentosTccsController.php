<?php

namespace Modulos\Academico\Http\Controllers;

use Modulos\Seguranca\Providers\ActionButton\Facades\ActionButton;
use Modulos\Seguranca\Providers\ActionButton\TButton;
use Modulos\Core\Http\Controller\BaseController;
use Modulos\Academico\Http\Requests\LancamentoTccRequest;
use Illuminate\Http\Request;
use Modulos\Academico\Models\Matricula;
use Modulos\Academico\Repositories\LancamentoTccRepository;
use Modulos\Academico\Repositories\TurmaRepository;
use Modulos\Academico\Repositories\ProfessorRepository;
use Modulos\Academico\Repositories\AlunoRepository;
use Modulos\Academico\Repositories\ModuloDisciplinaRepository;
use Modulos\Academico\Repositories\MatriculaCursoRepository;


class LancamentosTccsController extends BaseController
{
    protected $lancamentotccRepository;
    protected $turmaRepository;
    protected $modulodisciplinaRepository;
    protected $professorRepository;
    protected $alunoRepository;
    protected $matriculacursoRepository;

    public function __construct(LancamentoTccRepository $lancamentotccRepository,
                                TurmaRepository $turmaRepository,
                                ModuloDisciplinaRepository $modulodisciplinaRepository,
                                ProfessorRepository $professorRepository,
                                AlunoRepository $alunoRepository,
                                MatriculaCursoRepository $matriculacursoRepository)
    {
        $this->lancamentotccRepository = $lancamentotccRepository;
        $this->turmaRepository = $turmaRepository;
        $this->modulodisciplinaRepository = $modulodisciplinaRepository;
        $this->professorRepository = $professorRepository;
        $this->alunoRepository = $alunoRepository;
        $this->matriculacursoRepository = $matriculacursoRepository;

    }

    public function getIndex(Request $request)
    {
        $paginacao = null;
        $tabela = null;

        $tableData = $this->modulodisciplinaRepository->paginateRequest($request->all());

        if ($tableData->count()) {
            $tabela = $tableData->columns(array(
                'trm_id' => '#',
                'trm_nome' => 'Turma',
                'ofc_ano' => 'Ano da Oferta',
                'trm_action' => ''

            ))
                ->modifyCell('trm_action', function () {
                    return array('style' => 'width: 140px;');
                })
                ->means('trm_action', 'trm_id')

                ->modify('trm_action', function ($id) {
                    return  ActionButton::grid([
                        'type' => 'LINE',
                        'buttons' => [
                        [
                            'classButton' => 'btn btn-primary',
                            'icon' => 'fa fa-user',
                            'action' => '/academico/lancamentostccs/alunosturma/'.$id,
                            'label' => 'Alunos',
                            'method' => 'get'
                        ]
                    ]
                  ]);
                })
                ->sortable(array('trm_id', 'trm_nome'));

            $paginacao = $tableData->appends($request->except('page'));
        }
        return view('Academico::lancamentostccs.index', ['tabela' => $tabela, 'paginacao' => $paginacao]);
    }

    public function getAlunosTurma($turmaId)
    {
        $turma = $this->turmaRepository->find($turmaId);

        $dados = $this->matriculacursoRepository->findDadosByTurmaId($turmaId);

        if (!$turma) {
          flash()->error('Turma não existe!');
          return redirect()->back();
        }

        $disciplina = $this->lancamentotccRepository->findDisciplinaByTurma($turmaId);


        return view('Academico::lancamentostccs.alunosturma', compact('turma' , 'dados', 'disciplina'));
    }

    public function getCreate($alunoId, $turmaId)
    {
        $turma = $this->turmaRepository->find($turmaId);

        if (!$turma) {
          flash()->error('Turma não existe!');
          return redirect()->back();
        }

        $aluno = $this->alunoRepository->find($alunoId);

        $matricula = $this->matriculacursoRepository->findMatriculaIdByTurmaAluno($alunoId, $turmaId);

        if (!$aluno) {
          flash()->error('Aluno não existe!');
          return redirect()->back();
        }

        $professores = $this->professorRepository->lists('prf_id', 'pes_nome',true);
        $tiposdetcc = [
            'artigo' => 'Artigo',
            'monografia' => 'Monografia',
            'estudo_de_caso' => 'Estudo de Caso',
            'revisao_de_bibliografia' => 'Revisão de Bibliografia',
            'pesquisa_de_recepcao' => 'Pesquisa de Recepção',
            'projeto_arquitetonico_urbanistico' => 'Projeto Aquitetônico e Urbanistico',
            'plano_de_negocio' => 'Plano de Negócio'
        ];
        $disciplina = $this->lancamentotccRepository->findDisciplinaByTurma($turmaId);

        return view('Academico::lancamentostccs.create', compact('turma', 'aluno', 'professores', 'disciplina', 'tiposdetcc', 'matricula'));
    }

    public function postCreate($turmaId,LancamentoTccRequest $request)
    {

        try {

            $lancamentotcc = $this->lancamentotccRepository->create($request->all());

            $matricula = Matricula::find($request->mat_id);
            $matricula->mat_ltc_id = $lancamentotcc->ltc_id;
            $matricula->save();

            if (!$lancamentotcc) {
                flash()->error('Erro ao tentar salvar.');
                return redirect()->back()->withInput($request->all());
            }

            flash()->success('Lançamento de TCC criado com sucesso.');
            return redirect('/academico/lancamentostccs/alunosturma/'.$turmaId);
        } catch (\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            }

            flash()->error('Erro ao tentar atualizar. Caso o problema persista, entre em contato com o suporte.');
            return redirect()->back();
        }
    }

    public function getEdit($lancamentoTccId)
    {
        $lancamentoTcc = $this->lancamentotccRepository->find($lancamentoTccId);

        if (!$lancamentoTcc) {
          flash()->error('Este TCC não existe!');
          return redirect()->back();
         }

        $professores = $this->professorRepository->lists('prf_id', 'pes_nome',true);

        $tiposdetcc = [
            'artigo' => 'Artigo',
            'monografia' => 'Monografia',
            'estudo_de_caso' => 'Estudo de Caso',
            'revisao_de_bibliografia' => 'Revisão de Bibliografia',
            'pesquisa_de_recepcao' => 'Pesquisa de Recepção',
            'projeto_arquitetonico_urbanistico' => 'Projeto Aquitetônico e Urbanistico',
            'plano_de_negocio' => 'Plano de Negócio'
        ];

        $disciplina = $this->lancamentotccRepository->findDisciplinaByTurma($lancamentoTcc->matriculaCurso->turma->trm_id);

        return view('Academico::lancamentostccs.edit', compact('lancamentoTcc', 'turma', 'aluno', 'professores', 'disciplina', 'tiposdetcc', 'matricula'));
    }

    public function putEdit($lancamentotccId, LancamentoTccRequest $request)
    {
        try {
            $lancamentotcc = $this->lancamentotccRepository->find($lancamentotccId);

            if (!$lancamentotcc) {
                flash()->error('Lançamento de TCC não existe.');
                return redirect('academico/lancamentotccs/index');
            }

            $requestData = $request->only($this->lancamentotccRepository->getFillableModelFields());

            if (!$this->lancamentotccRepository->update($requestData, $lancamentotcc->ltc_id, 'ltc_id')) {
                flash()->error('Erro ao tentar salvar.');
                return redirect()->back()->withInput($request->all());
            }

            flash()->success('Lançamento de TCC atualizado com sucesso.');
            return redirect('/academico/lancamentostccs/alunosturma/'.$lancamentotcc->matriculaCurso->turma->trm_id);
        } catch (\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            }

            flash()->error('Erro ao tentar salvar. Caso o problema persista, entre em contato com o suporte.');
            return redirect()->back();
        }
    }
}
