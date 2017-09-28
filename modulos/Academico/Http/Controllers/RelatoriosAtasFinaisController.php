<?php

namespace Modulos\Academico\Http\Controllers;

use Illuminate\Http\Request;
use Modulos\Core\Http\Controller\BaseController;
use Modulos\Academico\Repositories\PoloRepository;
use Modulos\Academico\Repositories\CursoRepository;
use Modulos\Academico\Repositories\TurmaRepository;
use Modulos\Academico\Repositories\OfertaCursoRepository;
use Modulos\Academico\Repositories\MatriculaCursoRepository;

class RelatoriosAtasFinaisController extends BaseController
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
        PoloRepository $poloRepository)
    {
        $this->matriculaCursoRepository = $matricula;
        $this->cursoRepository = $curso;
        $this->turmaRepository = $turmaRepository;
        $this->ofertaCursoRepository = $ofertaCursoRepository;
        $this->poloRepository = $poloRepository;
    }

    public function getIndex(Request $request)
    {
        $cursos = $this->cursoRepository->listsCursosTecnicos();

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
                $ofertasCurso[$oferta->ofc_id] = $oferta->ofc_ano . '('.$oferta->mdl_nome.')';
            }

            $polos = $this->poloRepository->findAllByOfertaCurso($ofc_id)->pluck('pol_nome', 'pol_id');
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
        return view('Academico::relatoriosatasfinais.index', compact('tabela', 'paginacao', 'cursos', 'ofertasCurso', 'turmas', 'polos', 'situacao'));
    }
}
