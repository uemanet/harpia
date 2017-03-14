<?php

namespace Modulos\Academico\Http\Controllers;

use Illuminate\Http\Request;
use Modulos\Academico\Repositories\AlunoRepository;
use Modulos\Academico\Repositories\MatriculaCursoRepository;
use Modulos\Academico\Repositories\MatriculaOfertaDisciplinaRepository;
use Modulos\Academico\Repositories\OfertaDisciplinaRepository;
use Modulos\Academico\Repositories\PeriodoLetivoRepository;
use Modulos\Core\Http\Controller\BaseController;
use ActionButton;

class HistoricoParcialController extends BaseController
{
    private $alunoRepository;
    private $ofertaDisciplinaRepository;
    private $matriculaOfertaDisciplinaRepository;
    private $matriculaCursoRepository;
    private $periodoLetivoRepository;

    public function __construct(
        AlunoRepository $alunoRepository,
        OfertaDisciplinaRepository $ofertaDisciplinaRepository,
        MatriculaOfertaDisciplinaRepository $matriculaOfertaDisciplinaRepository,
        MatriculaCursoRepository $matriculaCursoRepository,
        PeriodoLetivoRepository $periodoLetivoRepository
    )
    {
        $this->alunoRepository = $alunoRepository;
        $this->ofertaDisciplinaRepository = $ofertaDisciplinaRepository;
        $this->matriculaOfertaDisciplinaRepository = $matriculaOfertaDisciplinaRepository;
        $this->matriculaCursoRepository = $matriculaCursoRepository;
        $this->periodoLetivoRepository = $periodoLetivoRepository;
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
            $gradesCurriculares[$matricula->mat_id]['periodos_letivos'] = $this->getGradeCurricular($matricula->mat_id);
        }

        return view('Academico::historicoparcial.show', compact('aluno', 'pessoa', 'gradesCurriculares'));
    }

    public function getPrint($matriculaId)
    {
        $matricula = $this->matriculaCursoRepository->find($matriculaId);

        if (!$matricula) {
            flash()->error('Aluno não encontrado');
            return redirect()->back();
        }

        $curso = $matricula->turma->ofertacurso->curso;

        $gradeCurricular = $this->getGradeCurricular($matricula->mat_id);

        $aluno = $matricula->aluno;
        
        $mpdf = new \mPDF();
        $mpdf->mirrorMargins = 1;
        $mpdf->SetTitle('Histórico Parcial - ' . $aluno->pessoa->pes_nome);
        $mpdf->SetFooter('São Luís-MA, ' . date("d/m/y"));
        $mpdf->addPage('P');

        $mpdf->WriteHTML(view('Academico::historicoparcial.print', compact('aluno'))->render());
        $mpdf->Output();
        exit;
    }

    private function getGradeCurricular($matriculaId)
    {
        $matricula = $this->matriculaCursoRepository->find($matriculaId);

        $periodos = $this->periodoLetivoRepository->getAllByTurma($matricula->mat_trm_id);

        $returndata = array();

        foreach ($periodos as $periodo) {
            $reg = array();

            $reg['per_id'] = $periodo->per_id;
            $reg['per_nome'] = $periodo->per_nome;

            $disciplinasCursadas = $this->matriculaOfertaDisciplinaRepository->findBy([
                'mof_mat_id' => $matricula->mat_id,
                'ofd_per_id' => $periodo->per_id
            ], null, ['dis_nome' => 'asc']);

            if (!$disciplinasCursadas->count()) {
                continue;
            }

            $disciplinasPeriodo = array();

            // pegar as matriculas do aluno para as disciplinas desse modulo

            foreach ($disciplinasCursadas as $oferta) {

                $cell = array();

                $cell['mof_id'] = $oferta->mof_id;
                $cell['dis_nome'] = $oferta->dis_nome;
                $cell['mdc_tipo_avaliacao'] = $oferta->mdc_tipo_avaliacao;
                $cell['mdo_nome'] = $oferta->mdo_nome;
                $cell['mof_nota1'] = '---';
                $cell['mof_nota2'] = '---';
                $cell['mof_nota3'] = '---';
                $cell['mof_conceito'] = '---';
                $cell['mof_recuperacao'] = '---';
                $cell['mof_final'] = '---';
                $cell['mof_mediafinal'] = '---';
                $cell['mof_situacao_matricula'] = '---';

                if (!is_null($oferta->mof_nota1)) {
                    $cell['mof_nota1'] = $oferta->mof_nota1;
                }

                if (!is_null($oferta->mof_nota2)) {
                    $cell['mof_nota2'] = $oferta->mof_nota2;
                }

                if (!is_null($oferta->mof_nota3)) {
                    $cell['mof_nota3'] = $oferta->mof_nota3;
                }

                if (!is_null($oferta->mof_conceito)) {
                    $cell['mof_conceito'] = $oferta->mof_conceito;
                }

                if (!is_null($oferta->mof_recuperacao)) {
                    $cell['mof_recuperacao'] = $oferta->mof_recuperacao;
                }

                if (!is_null($oferta->mof_final)) {
                    $cell['mof_final'] = $oferta->mof_final;
                }

                if (!is_null($oferta->mof_mediafinal)) {
                    $cell['mof_mediafinal'] = $oferta->mof_mediafinal;
                }

                $cell['mof_situacao_matricula'] = $oferta->mof_situacao_matricula;

                $disciplinasPeriodo[] = (object)$cell;
            }

            $reg['ofertas_disciplinas'] = $disciplinasPeriodo;

            $returndata[] = $reg;
        }

        return $returndata;
    }
}