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
                                'icon' => 'fa fa-plus-square',
                                'action' => '/academico/matricularalunocurso/create/' . $id,
                                'label' => 'Nova Matrícula',
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

        if(!$aluno) {
            flash()->error('Aluno não existe!');
            return redirect()->back();
        }

        $cursos = $this->cursoRepository->lists('crs_id', 'crs_nome', true);

        return view('Academico::matricula-curso.create', compact('aluno', 'cursos'));
    }

    public function postCreate($alunoId, MatriculaCursoRequest $request)
    {
        try{

            // verifica se aluno possui matricula na oferta de curso ou na turma
            if($this->matriculaCursoRepository->verifyIfExistsMatriculaByOfertaCursoOrTurma($alunoId, $request->input('ofc_id'), $request->input('mat_trm_id')))
            {
                flash()->error('Aluno já possui matricula na oferta ou turma');
                return redirect()->route('academico.matricularalunocurso.index');
            }

            // verifica se aluno possui matricula ativa no curso, mesmo sendo em ofertas diferentes
            if($this->matriculaCursoRepository->verifyIfExistsMatriculaByCursoAndSituacao($alunoId, $request->input('crs_id')))
            {
                flash()->error('Aluno já possui matricula ativa no curso selecionado');
                return redirect()->route('academico.matricularalunocurso.index');
            }

            $dataMatricula = [
                'mat_alu_id' => $alunoId,
                'mat_trm_id' => $request->input('mat_trm_id'),
                'mat_pol_id' => $request->input('mat_pol_id'),
                'mat_grp_id' => $request->input('mat_grp_id'),
                'mat_situacao' => 'cursando'
            ];

            $this->matriculaCursoRepository->create($dataMatricula);

            flash()->success('Matricula efetuada com sucesso!');
            return redirect()->route('academico.matricularalunocurso.index');

        } catch (\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            }

            flash()->error('Erro ao tentar salvar. Caso o problema persista, entre em contato com o suporte.');
            return redirect()->back();
        }
    }
}