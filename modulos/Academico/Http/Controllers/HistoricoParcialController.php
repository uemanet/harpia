<?php

namespace Modulos\Academico\Http\Controllers;

use Illuminate\Http\Request;
use Modulos\Academico\Repositories\AlunoRepository;
use Modulos\Academico\Repositories\OfertaDisciplinaRepository;
use Modulos\Core\Http\Controller\BaseController;
use ActionButton;

class HistoricoParcialController extends BaseController
{
    private $alunoRepository;
    private $ofertaDisciplinaRepository;

    public function __construct(
        AlunoRepository $alunoRepository,
        OfertaDisciplinaRepository $ofertaDisciplinaRepository
    )
    {
        $this->alunoRepository = $alunoRepository;
        $this->ofertaDisciplinaRepository = $ofertaDisciplinaRepository;
    }

    public function getIndex(Request $request)
    {
        $paginacao = null;
        $tabela = null;

        $tableData = $this->alunoRepository->paginateRequest($request->all(), true, true);

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
                    'type' => 'SELECT',
                    'config' => [
                        'classButton' => 'btn-default',
                        'label' => 'Selecione'
                    ],
                    'buttons' => [
                        [
                            'classButton' => '',
                            'icon' => 'fa fa-eye',
                            'action' => '/academico/historicoparcial/show/'.$id,
                            'label' => 'Visualizar',
                            'method' => 'get'
                        ]
                    ]
                ]);
            })
            ->sortable(array('alu_id', 'pes_nome'));

            $paginacao = $tableData->appends($request->except('page'));

            return view('Academico::historicoparcial.index', compact('tabela', 'paginacao'));
        }
    }
    
    public function getShow($alunoId) 
    {
        $aluno = $this->alunoRepository->find($alunoId);

        if (!$aluno) {
            flash()->error('Aluno não encontrado');
            return redirect()->route('academico.historicoparcial.index');
        }

        $pessoa = $aluno->pessoa;

        $gradesCurriculares = array();
        
        foreach ($aluno->matriculas as $matricula) {
            //$curso = $matricula->turma->ofertacurso->curso;
            
            $matrizCurricular = $matricula->turma->ofertacurso->matriz;

            $modulos = $matrizCurricular->modulos;

            foreach ($modulos as $modulo) {
                $reg = array();

                $reg['mdo_id'] = $modulo->mdo_id;
                $reg['mdo_nome'] = $modulo->mdo_nome;

                $disciplinasOfertadas = $this->ofertaDisciplinaRepository->findAll([
                    'mdc_mdo_id' => $modulo->mdo_id,
                    'ofd_trm_id' => $matricula->mat_trm_id
                ], ['mdc_id', 'dis_nome']);

                $reg['ofertas_disciplinas'] = $disciplinasOfertadas;

                $gradesCurriculares[$matricula->mat_id]['modulos'][] = $reg;
            }
        }

        return view('Academico::historicoparcial.show', compact('aluno', 'pessoa', 'gradesCurriculares'));
    }
}