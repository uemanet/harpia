<?php

namespace Modulos\Academico\Http\Controllers;

use Illuminate\Http\Request;
use Modulos\Academico\Http\Requests\MatriculaCursoRequest;
use Modulos\Academico\Repositories\AlunoRepository;
use Modulos\Academico\Repositories\CursoRepository;
use Modulos\Academico\Repositories\MatriculaCursoRepository;
use Modulos\Core\Http\Controller\BaseController;
use Modulos\Seguranca\Providers\ActionButton\Facades\ActionButton;

class MatriculaCursoController extends BaseController
{
    protected $matriculaCursoRepository;
    protected $alunoRepository;
    protected $cursoRepository;

    public function __construct(MatriculaCursoRepository $matricula, AlunoRepository $aluno, CursoRepository $curso)
    {
        $this->matriculaCursoRepository = $matricula;
        $this->alunoRepository = $aluno;
        $this->cursoRepository = $curso;
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
                                'action' => '/academico/matricularalunocurso/show/' . $id,
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
            //dd($result);
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

    public function getShow($alunoId)
    {
        $aluno = $this->alunoRepository->find($alunoId);

        if (!$aluno) {
            flash()->error('Aluno não existe!');
            return redirect()->route('academico.matricularalunocurso.index');
        }

        return view('Academico::matricula-curso.show', ['pessoa' => $aluno->pessoa, 'aluno' => $aluno]);
    }
}
