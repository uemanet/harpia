<?php

namespace Modulos\Academico\Http\Controllers;

use Illuminate\Http\Request;
use Modulos\Academico\Repositories\AlunoRepository;
use Modulos\Academico\Repositories\CursoRepository;
use Modulos\Core\Http\Controller\BaseController;
use Modulos\Seguranca\Providers\ActionButton\Facades\ActionButton;

class MatriculaCursoController extends BaseController
{
    protected $matriculaCursoRepository;
    protected $alunoRepository;
    protected $cursoRepository;

    public function __construct(AlunoRepository $aluno, CursoRepository $curso)
    {
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

    public function postCreate(Request $request)
    {
        
    }
}