<?php

namespace Modulos\Academico\Http\Controllers;

use Illuminate\Http\Request;
use Modulos\Academico\Repositories\AlunoRepository;
use Modulos\Academico\Repositories\MatriculaOfertaDisciplinaRepository;
use Modulos\Academico\Repositories\OfertaDisciplinaRepository;
use Modulos\Core\Http\Controller\BaseController;
use ActionButton;

class HistoricoParcialController extends BaseController
{
    private $alunoRepository;
    private $ofertaDisciplinaRepository;
    private $matriculaOfertaDisciplinaRepository;

    public function __construct(
        AlunoRepository $alunoRepository,
        OfertaDisciplinaRepository $ofertaDisciplinaRepository,
        MatriculaOfertaDisciplinaRepository $matriculaOfertaDisciplinaRepository
    )
    {
        $this->alunoRepository = $alunoRepository;
        $this->ofertaDisciplinaRepository = $ofertaDisciplinaRepository;
        $this->matriculaOfertaDisciplinaRepository = $matriculaOfertaDisciplinaRepository;
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
                ], ['ofd_id','mdc_id', 'dis_nome', 'mdc_tipo_avaliacao']);

                $disciplinasModulo = array();

                // pegar as matriculas do aluno para as disciplinas desse modulo

                foreach ($disciplinasOfertadas as $oferta) {

                    $cell = array();

                    $cell['mdc_id'] = $oferta->mdc_id;
                    $cell['dis_nome'] = $oferta->dis_nome;
                    $cell['mdc_tipo_avaliacao'] = $oferta->mdc_tipo_avaliacao;
                    $cell['mof_nota1'] = '---';
                    $cell['mof_nota2'] = '---';
                    $cell['mof_nota3'] = '---';
                    $cell['mof_conceito'] = '---';
                    $cell['mof_recuperacao'] = '---';
                    $cell['mof_final'] = '---';
                    $cell['mof_mediafinal'] = '---';
                    $cell['mof_situacao_matricula'] = '---';

                    $result = $this->matriculaOfertaDisciplinaRepository->findBy([
                        'mof_mat_id' => $matricula->mat_id,
                        'mof_ofd_id' => $oferta->ofd_id
                    ])->last();

                    if ($result) {
                        if (!is_null($result->mof_nota1)) {
                           $cell['mof_nota1'] = $result->mof_nota1;
                        }

                        if (!is_null($result->mof_nota2)) {
                            $cell['mof_nota2'] = $result->mof_nota2;
                        }

                        if (!is_null($result->mof_nota3)) {
                            $cell['mof_nota3'] = $result->mof_nota3;
                        }

                        if (!is_null($result->mof_conceito)) {
                            $cell['mof_conceito'] = $result->mof_conceito;
                        }

                        if (!is_null($result->mof_recuperacao)) {
                            $cell['mof_recuperacao'] = $result->mof_recuperacao;
                        }

                        if (!is_null($result->mof_final)) {
                            $cell['mof_final'] = $result->mof_final;
                        }

                        if (!is_null($result->mof_mediafinal)) {
                            $cell['mof_mediafinal'] = $result->mof_mediafinal;
                        }

                        $cell['mof_situacao_matricula'] = $result->mof_situacao_matricula;
                    }

                    $disciplinasModulo[] = (object)$cell;
                }

                $reg['ofertas_disciplinas'] = $disciplinasModulo;

                $gradesCurriculares[$matricula->mat_id]['modulos'][] = $reg;
            }
        }
//        dd($gradesCurriculares);

        return view('Academico::historicoparcial.show', compact('aluno', 'pessoa', 'gradesCurriculares'));
    }
}