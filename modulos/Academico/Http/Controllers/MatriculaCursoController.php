<?php

namespace Modulos\Academico\Http\Controllers;

use Illuminate\Http\Request;
use Modulos\Academico\Events\AlterarGrupoAlunoEvent;
use Modulos\Academico\Events\DeletarGrupoAlunoEvent;
use Modulos\Academico\Events\MatriculaAlunoTurmaEvent;
use Modulos\Academico\Http\Requests\MatriculaCursoRequest;
use Modulos\Academico\Repositories\AlunoRepository;
use Modulos\Academico\Repositories\CursoRepository;
use Modulos\Academico\Repositories\MatriculaCursoRepository;
use Modulos\Academico\Repositories\TurmaRepository;
use Modulos\Core\Http\Controller\BaseController;
use Modulos\Seguranca\Providers\ActionButton\Facades\ActionButton;

class MatriculaCursoController extends BaseController
{
    protected $matriculaCursoRepository;
    protected $alunoRepository;
    protected $cursoRepository;
    protected $turmaRepository;

    public function __construct(MatriculaCursoRepository $matricula, AlunoRepository $aluno, CursoRepository $curso, TurmaRepository $turmaRepository)
    {
        $this->matriculaCursoRepository = $matricula;
        $this->alunoRepository = $aluno;
        $this->cursoRepository = $curso;
        $this->turmaRepository = $turmaRepository;
    }

    public function getIndex(Request $request)
    {
        $paginacao = null;
        $tabela = null;

        $tableData = $this->alunoRepository->paginateRequest($request->all());

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
                                'route' => 'academico.matricularalunocurso.show',
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

        return view('Academico::matricula-curso.index', ['tabela' => $tabela, 'paginacao' => $paginacao]);
    }

    public function getCreate($alunoId)
    {
        $aluno = $this->alunoRepository->find($alunoId);

        if (!$aluno) {
            flash()->error('Aluno não existe!');
            return redirect()->back();
        }

        $cursos = $this->matriculaCursoRepository->listsCursosNotMatriculadoByAluno($alunoId);
        $modosEntrada = array(
            'vestibular' => 'Vestibular',
            'transferencia_externa' => 'Transferência Externa',
            'transferencia_interna_de' => 'Transferência Interna De',
            'transferencia_interna_para' => 'Transferência Interna Para'
        );

        return view('Academico::matricula-curso.create', compact('aluno', 'cursos', 'modosEntrada'));
    }

    public function postCreate($alunoId, MatriculaCursoRequest $request)
    {
        try {
            $result = $this->matriculaCursoRepository->createMatricula($alunoId, $request->all());

            flash()->{$result['type']}($result['message']);

            if ($result['type'] == 'success') {
                $matricula = $result['matricula'];
                $turma = $this->turmaRepository->find($matricula->mat_trm_id);
                if ($turma->trm_integrada) {
                    event(new MatriculaAlunoTurmaEvent($matricula));
                }
            }
            return redirect()->route('academico.matricularalunocurso.show', $alunoId);
        } catch (\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            }

            flash()->error('Erro ao tentar salvar. Caso o problema persista, entre em contato com o suporte.');
            return redirect()->back();
        }
    }

    public function putEdit($matriculaId, MatriculaCursoRequest $request)
    {
        try {
            $matricula = $this->matriculaCursoRepository->find($matriculaId);

            if (!$matricula) {
                flash()->error('Matricula não existe!');
                return redirect()->route('academico.matricularalunocurso.index');
            }

            $oldMatricula = clone $matricula;

            $matricula->mat_pol_id = $request->input('mat_pol_id');
            $matricula->mat_grp_id = ($request->input('mat_grp_id') == '') ? null : $request->input('mat_grp_id');

            $matricula->save();

            $turma = $matricula->turma;

            if (($turma->trm_integrada) && ($oldMatricula->mat_grp_id != $matricula->mat_grp_id) && ($matricula->mat_grp_id)) {
                event(new AlterarGrupoAlunoEvent($matricula, 'UPDATE_GRUPO_ALUNO', $oldMatricula->mat_grp_id));
            }

            if (($turma->trm_integrada) && ($oldMatricula->mat_grp_id) && (!$matricula->mat_grp_id)) {
                event(new DeletarGrupoAlunoEvent($matricula, 'DELETE_GRUPO_ALUNO', $oldMatricula->mat_grp_id));
            }

            flash()->success('Matrícula atualizada com sucesso.');
            return redirect()->route('academico.matricularalunocurso.show', $matricula->mat_alu_id);
        } catch (\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            }

            flash()->error('Erro ao tentar atualizar. Caso o problema persista, entre em contato com o suporte.');
            return redirect()->back();
        }
    }

    public function getShow($alunoId)
    {
        $aluno = $this->alunoRepository->find($alunoId);

        $situacao = [
            'cursando' => 'Cursando',
            'reprovado' => 'Reprovado',
            'evadido' => 'Evadido',
            'trancado' => 'Trancado',
            'desistente' => 'Desistente'
        ];

        if (!$aluno) {
            flash()->error('Aluno não existe!');
            return redirect()->route('academico.matricularalunocurso.index');
        }

        return view('Academico::matricula-curso.show', [
            'pessoa' => $aluno->pessoa,
            'aluno' => $aluno,
            'situacao' => $situacao
        ]);
    }
}
