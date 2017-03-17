<?php

namespace Modulos\Academico\Http\Controllers;

use Illuminate\Http\Request;
use Modulos\Academico\Repositories\AlunoRepository;
use Modulos\Academico\Repositories\HistoricoParcialRepository;
use Modulos\Academico\Repositories\MatriculaCursoRepository;
use Modulos\Core\Http\Controller\BaseController;
use ActionButton;

class HistoricoParcialController extends BaseController
{
    private $alunoRepository;
    private $matriculaCursoRepository;
    private $historicoParcialRepository;

    public function __construct(
        AlunoRepository $alunoRepository,
        MatriculaCursoRepository $matriculaCursoRepository,
        HistoricoParcialRepository $historicoParcialRepository
    ) {
        $this->alunoRepository = $alunoRepository;
        $this->matriculaCursoRepository = $matriculaCursoRepository;
        $this->historicoParcialRepository = $historicoParcialRepository;
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
            $gradesCurriculares[$matricula->mat_id]['periodos_letivos'] = $this->historicoParcialRepository->getGradeCurricularByMatricula($matricula->mat_id);
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

        $gradeCurricular = $this->historicoParcialRepository->getGradeCurricularByMatricula($matricula->mat_id);

        $aluno = $matricula->aluno;
        $cpf = $aluno->pessoa->documentos()->where('doc_tpd_id', 2)->first();
        $cpf = $cpf->doc_conteudo;

        $aluno->cpf = $cpf;

        $nome = explode(' ', $aluno->pessoa->pes_nome);
        
        $mpdf = new \mPDF();
        $mpdf->mirrorMargins = 1;
        $mpdf->SetTitle('Histórico Parcial - ' . $aluno->pessoa->pes_nome);
        $mpdf->addPage('P');

        $mpdf->WriteHTML(view('Academico::historicoparcial.print', compact('aluno', 'curso', 'gradeCurricular', 'matricula'))->render());
        $mpdf->Output('Historico_Parcial_'.$nome[0].'_'.end($nome).'.pdf', 'I');
    }
}
