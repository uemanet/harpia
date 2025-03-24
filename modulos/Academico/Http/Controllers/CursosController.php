<?php

namespace Modulos\Academico\Http\Controllers;

use Modulos\Academico\Models\ConfiguracaoCurso;
use Modulos\Academico\Repositories\CentroRepository;
use Modulos\Academico\Repositories\VinculoRepository;
use Modulos\Seguranca\Providers\ActionButton\Facades\ActionButton;
use Modulos\Seguranca\Providers\ActionButton\TButton;
use Modulos\Core\Http\Controller\BaseController;
use Modulos\Academico\Http\Requests\CursoRequest;
use Illuminate\Http\Request;
use Modulos\Academico\Repositories\CursoRepository;
use Modulos\Academico\Repositories\NivelCursoRepository;
use Modulos\Academico\Repositories\ProfessorRepository;
use Auth;
use DB;

class CursosController extends BaseController
{
    protected $cursoRepository;
    protected $departamentoRepository;
    protected $nivelcursoRepository;
    protected $professorRepository;
    protected $vinculoRepository;
    protected $centroRepository;

    public function __construct(
        CursoRepository $curso,
        NivelCursoRepository $nivelcurso,
        ProfessorRepository $professor,
        VinculoRepository $vinculoRepository,
        CentroRepository $centroRepository
    ) {
        $this->cursoRepository = $curso;
        $this->nivelcursoRepository = $nivelcurso;
        $this->professorRepository = $professor;
        $this->vinculoRepository = $vinculoRepository;
        $this->centroRepository = $centroRepository;
    }

    public function getIndex(Request $request)
    {
        $btnNovo = new TButton();
        $btnNovo->setName('Novo')->setRoute('academico.cursos.create')->setIcon('fa fa-plus')->setStyle('btn bg-olive');

        $actionButtons[] = $btnNovo;

        $paginacao = null;
        $tabela = null;

        $tableData = $this->cursoRepository->paginateRequest($request->all());

        if ($tableData->count()) {
            $tabela = $tableData->columns(array(
                'crs_id' => '#',
                'crs_nome' => 'Curso',
                'crs_sigla' => 'Sigla',
                'crs_prf_diretor' => 'Diretor',
                'crs_descricao' => 'Descrição',
                'crs_action' => 'Ações',
            ))
                ->modifyCell('crs_action', function () {
                    return array('style' => 'width: 140px;');
                })
                ->means('crs_prf_diretor', 'diretor')
                ->modify('crs_prf_diretor', function ($diretor) {
                    return $diretor->pessoa->pes_nome;
                })
                ->means('crs_action', 'crs_id')
                ->modify('crs_action', function ($id) {
                    return ActionButton::grid([
                        'type' => 'SELECT',
                        'config' => [
                            'classButton' => 'btn-default',
                            'label' => 'Selecione'
                        ],
                        'buttons' => [
                            [
                                'classButton' => '',
                                'icon' => 'fa fa-table',
                                'route' => 'academico.cursos.matrizescurriculares.index',
                                'parameters' => ['id' => $id],
                                'label' => 'Matrizes',
                                'method' => 'get'
                            ],
                            [
                                'classButton' => '',
                                'icon' => 'fa fa-pencil',
                                'route' => 'academico.cursos.edit',
                                'parameters' => ['id' => $id],
                                'label' => 'Editar',
                                'method' => 'get'
                            ],
                            [
                                'classButton' => 'btn-delete text-red',
                                'icon' => 'fa fa-trash',
                                'route' => 'academico.cursos.delete',
                                'id' => $id,
                                'label' => 'Excluir',
                                'method' => 'post'
                            ]
                        ]
                    ]);
                })
                ->sortable(array('crs_id', 'crs_nome'));

            $paginacao = $tableData->appends($request->except('page'));
        }
        return view('Academico::cursos.index', ['tabela' => $tabela, 'paginacao' => $paginacao, 'actionButton' => $actionButtons]);
    }

    public function getCreate()
    {
        $centros = $this->centroRepository->lists('cen_id', 'cen_nome');
        $niveiscursos = $this->nivelcursoRepository->lists('nvc_id', 'nvc_nome');
        $professores = $this->professorRepository->lists('prf_id', 'pes_nome', true);

        dd($centros);
        return view('Academico::cursos.create', compact('centros', 'niveiscursos', 'professores'));
    }

    public function postCreate(CursoRequest $request)
    {
        $response = $this->cursoRepository->create($request->all());

        flash()->{$response['status']}($response['message']);

        if ($response['status'] == 'success') {
            return redirect()->route('academico.cursos.index');
        }

        return redirect()->back();
    }

    public function getEdit($cursoId)
    {
        $curso = $this->cursoRepository->find($cursoId);

        if (!$curso) {
            flash()->error('Curso não existe.');
            return redirect()->back();
        }

        $configuracoes = $curso->configuracoes;
        if ($configuracoes) {
            foreach ($configuracoes as $configuracao) {
                $valor = $configuracao->cfc_valor;
                if ($configuracao->cfc_nome == 'conceitos_aprovacao') {
                    $valor = json_decode($configuracao->cfc_valor, true);
                }

                $curso->{$configuracao->cfc_nome} = $valor;
            }
        }

        $centros = $this->centroRepository->lists('cen_id', 'cen_nome');
        $niveiscursos = $this->nivelcursoRepository->lists('nvc_id', 'nvc_nome');
        $professores = $this->professorRepository->lists('prf_id', 'pes_nome', true);

        return view('Academico::cursos.edit', compact('curso', 'centros', 'niveiscursos', 'professores'));
    }

    public function putEdit($id, CursoRequest $request)
    {
        $response = $this->cursoRepository->updateCurso($request->all(), $id);

        flash()->{$response['status']}($response['message']);

        if ($response['status'] == 'success') {
            return redirect()->route('academico.cursos.index');
        }

        return redirect()->back();
    }

    public function postDelete(Request $request)
    {
        $id = $request->get('id');

        $response = $this->cursoRepository->delete($id);

        flash()->{$response['status']}($response['message']);

        return redirect()->back();
    }
}
