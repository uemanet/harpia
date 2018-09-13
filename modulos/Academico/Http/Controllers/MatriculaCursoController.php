<?php

namespace Modulos\Academico\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use Modulos\Core\Http\Controller\BaseController;
use Modulos\Academico\Repositories\TurmaRepository;
use Modulos\Academico\Events\UpdateGrupoAlunoEvent;
use Modulos\Academico\Events\DeleteGrupoAlunoEvent;
use Modulos\Academico\Repositories\AlunoRepository;
use Modulos\Academico\Repositories\CursoRepository;
use Modulos\Academico\Events\DeleteMatriculaTurmaEvent;
use Modulos\Academico\Events\UpdateMatriculaCursoEvent;
use Modulos\Academico\Events\CreateMatriculaTurmaEvent;
use Modulos\Academico\Http\Requests\MatriculaCursoRequest;
use Modulos\Academico\Repositories\MatriculaCursoRepository;
use Modulos\Integracao\Repositories\AmbienteVirtualRepository;
use Modulos\Seguranca\Providers\ActionButton\Facades\ActionButton;

class MatriculaCursoController extends BaseController
{
    protected $matriculaCursoRepository;
    protected $alunoRepository;
    protected $cursoRepository;
    protected $turmaRepository;
    protected $ambienteVirtualRepository;

    public function __construct(
        MatriculaCursoRepository $matricula,
        AlunoRepository $aluno,
        CursoRepository $curso,
        TurmaRepository $turmaRepository,
        AmbienteVirtualRepository $ambienteVirtualRepository
    ) {
        $this->matriculaCursoRepository = $matricula;
        $this->alunoRepository = $aluno;
        $this->cursoRepository = $curso;
        $this->turmaRepository = $turmaRepository;
        $this->ambienteVirtualRepository = $ambienteVirtualRepository;
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
            'transferencia_interna_para' => 'Transferência Interna Para',
            'outros_tipos_selecao' => 'Outros tipos de seleção',
            'outras_formas_ingresso' => 'Outras formas de ingresso',
            'transferencia_obrigatoria' => 'Transferência Obrigatória',
            'transferencia_ex_oficio' => 'Transferência Ex-Ofício',
            'graduando_interno' => 'Graduando Interno',
            'graduando_externo' => 'Graduando Externo',
            'graduado' => 'Graduado'
        );

        return view('Academico::matricula-curso.create', compact('aluno', 'cursos', 'modosEntrada'));
    }

    public function postCreate($alunoId, MatriculaCursoRequest $request)
    {
        try {
            $result = $this->matriculaCursoRepository->createMatricula($alunoId, $request->all());

            if ($result['type'] == 'success') {
                $matricula = $result['matricula'];

                if ($matricula->turma->trm_integrada) {
                    event(new CreateMatriculaTurmaEvent($matricula));
                }
            }

            flash()->{$result['type']}($result['message']);
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

            $data = [
                'mat_pol_id' => $request->input('mat_pol_id'),
                'mat_grp_id' => ($request->input('mat_grp_id') == '') ? null : $request->input('mat_grp_id'),
            ];

            $matricula->fill($data)->save();

            $observacao = $request->input('observacao');

            $turma = $matricula->turma;

            // caso a turma seja integrada, manda as alterações pro moodle
            if ($turma->trm_integrada) {
                if (($oldMatricula->mat_grp_id != $matricula->mat_grp_id) && ($matricula->mat_grp_id)) {
                    event(new UpdateGrupoAlunoEvent($matricula, $oldMatricula->mat_grp_id));
                }

                if (($oldMatricula->mat_grp_id) && (!$matricula->mat_grp_id)) {
                    event(new DeleteGrupoAlunoEvent($matricula, $oldMatricula->mat_grp_id));
                }
            }

            if ($oldMatricula->mat_grp_id != $matricula->mat_grp_id) {
                event(new UpdateMatriculaCursoEvent($matricula, UpdateMatriculaCursoEvent::GRUPO, $observacao));
            }

            if ($oldMatricula->mat_pol_id != $matricula->mat_pol_id) {
                event(new UpdateMatriculaCursoEvent($matricula, UpdateMatriculaCursoEvent::POLO, $observacao));
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

    public function postDelete(Request $request)
    {
        try {
            $data = $request->except('_token');
            $matricula = $this->matriculaCursoRepository->find($data['id']);

            if (!$matricula) {
                flash()->error('Matricula não existe!');
                return redirect()->route('academico.matricularalunocurso.index');
            }

            DB::beginTransaction();
            $matriculaDelete = $this->matriculaCursoRepository->deleteMatricula($data['id']);
            DB::commit();

            if ($matriculaDelete['type'] == 'error'){
                flash()->error($matriculaDelete['message']);
                return redirect()->route('academico.matricularalunocurso.show', $matricula->mat_alu_id);
            }

            $turma = $matricula->turma;
            if ($turma->trm_integrada) {
                $ambiente = $this->ambienteVirtualRepository->getAmbienteByTurma($turma->trm_id);
                if (!$ambiente) {
                    return array(
                        'type' => 'error',
                        'message' => 'Esta turma é integrada, porém não está vinculada a um ambiente virtual!',
                    );
                }
                event(new DeleteMatriculaTurmaEvent($matricula, $ambiente->amb_id));
            }

            flash()->success($matriculaDelete['message']);
            return redirect()->route('academico.matricularalunocurso.show', $matricula->mat_alu_id);

        } catch (\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            }

            flash()->error('Erro ao tentar atualizar. Caso o problema persista, entre em contato com o suporte.');
            return redirect()->back();
        }
    }
}
