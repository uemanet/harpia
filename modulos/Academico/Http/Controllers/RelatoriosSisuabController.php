<?php

namespace Modulos\Academico\Http\Controllers;
use Illuminate\Support\Facades\Response;
use App\Exports\MatriculaReportExport;
use Carbon\Traits\Creator;
use Excel;
use Validator;
use Mpdf\Mpdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Modulos\Core\Http\Controller\BaseController;
use Modulos\Academico\Repositories\PoloRepository;
use Modulos\Academico\Repositories\TurmaRepository;
use Modulos\Academico\Repositories\CursoRepository;
use Modulos\Academico\Repositories\OfertaCursoRepository;
use Modulos\Academico\Repositories\MatriculaCursoRepository;

class RelatoriosSisuabController extends BaseController
{
    protected $matriculaCursoRepository;
    protected $cursoRepository;
    protected $turmaRepository;
    protected $poloRepository;
    private $ofertaCursoRepository;

    public function __construct(
        MatriculaCursoRepository $matricula,
        CursoRepository $curso,
        TurmaRepository $turmaRepository,
        OfertaCursoRepository $ofertaCursoRepository,
        PoloRepository $poloRepository
    ) {
        $this->matriculaCursoRepository = $matricula;
        $this->cursoRepository = $curso;
        $this->turmaRepository = $turmaRepository;
        $this->ofertaCursoRepository = $ofertaCursoRepository;
        $this->poloRepository = $poloRepository;
    }

    public function getIndex(Request $request)
    {
        $cursos = $this->cursoRepository->lists('crs_id', 'crs_nome');

        $dados = $request->all();
        $ofertasCurso = [];
        $turmas = [];
        $polos = [];

        if ($dados) {
            $crs_id = $request->input('crs_id');
            $ofc_id = $request->input('ofc_id');

            $sqlOfertas = $this->ofertaCursoRepository->findAllByCurso($crs_id);
            $turmas = $this->turmaRepository->findAllByOfertaCurso($ofc_id)->pluck('trm_nome', 'trm_id');
            foreach ($sqlOfertas as $oferta) {
                $ofertasCurso[$oferta->ofc_id] = $oferta->ofc_ano . '(' . $oferta->mdl_nome . ')';
            }

            $oferta = $this->ofertaCursoRepository->find($ofc_id);
            $polos = $oferta->polos->pluck('pol_nome', 'pol_id');
        }

        $paginacao = null;
        $tabela = null;

        $tableData = $this->matriculaCursoRepository->paginateRequestByOfertaCurso($request->all());

        if ($tableData->count()) {
            $tabela = $tableData->columns(array(
                'mat_id' => '#',
                'pes_nome' => 'Nome',
                'pes_email' => 'Email',
                'pol_nome' => 'Polo',
                'situacao_matricula_curso' => 'Situação Matricula'
            ))
                ->sortable(array('pes_nome', 'mat_id'));

            $paginacao = $tableData->appends($request->except('page'));
        }

        $situacao = [
            "" => "Selecione a situação",
            "cursando" => "Cursando",
            "concluido" => "Concluido",
            "reprovado" => "Reprovado",
            "evadido" => "Evadido",
            "trancado" => "Trancado",
            "desistente" => "Desistente"
        ];
        return view('Academico::relatoriossisuab.index', compact('tabela', 'paginacao', 'cursos', 'ofertasCurso', 'turmas', 'polos', 'situacao'));
    }

    public function postCsv(Request $request)
    {
        $rules = [
            'crs_id' => 'required',
            'ofc_id' => 'required',
            'trm_id' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }
        $turmaId = $request->input('trm_id');
        $situacao = $request->input('mat_situacao');
        $poloId = $request->input('pol_id');

        $matriculas = $this->matriculaCursoRepository->findAllBySitucao(
            ['trm_id' => $turmaId, 'mat_situacao' => $situacao, 'pol_id' => $poloId]);
        $nomecurso = $this->turmaRepository->findCursoByTurma($turmaId);
        $turma = $this->turmaRepository->find($turmaId);

        $date = new Carbon();

        if ($matriculas->count()) {
            $filename = $turma->ofertacurso->curso->crs_nome . ' - '. $turma->trm_nome.'.txt';
            $content = '';

            foreach ($matriculas as $matricula) {
                $line = $matricula->pol_nome.";";
                $line .= $matricula->cpf.";";
                $line .= "cur".";";
                $line .= $matricula->pes_email.";";
                $line .= substr($matricula->pes_telefone, 0, 2).";";
                $line .= substr($matricula->pes_telefone, 2).";";
                $line .= "PR";
                $content .= $line . PHP_EOL;
            }

            header("Content-Description: File Transfer");
            header("Content-Type: text/csv");
            header("Content-disposition: attachment; filename={$filename}");
            header("Content-Length: ".strlen($content));
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header("Expires: 0");

            echo $content;
            exit;
        }
        return redirect()->back();
        exit;
    }
}
