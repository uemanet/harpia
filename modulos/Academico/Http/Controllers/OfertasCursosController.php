<?php

namespace Modulos\Academico\Http\Controllers;

use Modulos\Seguranca\Providers\ActionButton\Facades\ActionButton;
use Modulos\Seguranca\Providers\ActionButton\TButton;
use Modulos\Core\Http\Controller\BaseController;
use Modulos\Academico\Http\Requests\OfertaCursoRequest;
use Illuminate\Http\Request;
use Modulos\Academico\Repositories\OfertaCursoRepository;
use Modulos\Academico\Repositories\CursoRepository;
use Modulos\Academico\Repositories\MatrizCurricularRepository;
use Modulos\Academico\Repositories\ModalidadeRepository;
use Modulos\Academico\Repositories\PoloRepository;
use DB;

class OfertasCursosController extends BaseController
{
    protected $ofertacursoRepository;
    protected $cursoRepository;
    protected $matrizcurricularRepository;
    protected $modalidadeRepository;
    protected $poloRepository;

    public function __construct(OfertaCursoRepository $ofertacursoRepository,
                                CursoRepository $cursoRepository,
                                MatrizCurricularRepository $matrizcurricularRepository,
                                ModalidadeRepository $modalidadeRepository,
                                PoloRepository $poloRepository)
    {
        $this->ofertacursoRepository = $ofertacursoRepository;
        $this->cursoRepository = $cursoRepository;
        $this->matrizcurricularRepository = $matrizcurricularRepository;
        $this->modalidadeRepository = $modalidadeRepository;
        $this->poloRepository = $poloRepository;
    }

    public function getIndex(Request $request)
    {
        $btnNovo = new TButton();
        $btnNovo->setName('Novo')->setAction('/academico/ofertascursos/create')->setIcon('fa fa-plus')->setStyle('btn bg-olive');

        $actionButtons[] = $btnNovo;

        $paginacao = null;
        $tabela = null;

        $tableData = $this->ofertacursoRepository->paginateRequest($request->all());
        if ($tableData->count()) {
            $tabela = $tableData->columns(array(
                'ofc_id' => '#',
                'ofc_ano' => 'Ano',
                'crs_nome' => 'Curso',
                'ofc_mdl_id' => 'Modalidade',
                'ofc_action' => 'Ações'
            ))
                ->modifyCell('ofc_action', function () {
                    return array('style' => 'width: 140px;');
                })
                ->means('ofc_action', 'ofc_id')
                ->means('crs_nome', 'curso')
                ->modify('crs_nome', function ($curso) {
                    return $curso->crs_nome;
                })
                ->means('ofc_mdl_id', 'modalidade')
                ->modify('ofc_mdl_id', function ($modalidade) {
                    return $modalidade->mdl_nome;
                })
                ->modify('ofc_action', function ($id) {
                    return ActionButton::grid([
                        'type' => 'SELECT',
                        'config' => [
                            'classButton' => 'btn-default',
                            'label' => 'Selecione'
                        ],
                        'buttons' => [
                            [
                                'classButton' => '',
                                'icon' => 'fa fa-plus',
                                'action' => '/academico/turmas/index/'.$id,
                                'label' => 'Turmas',
                                'method' => 'get'
                            ]
                        ]
                    ]);
                })
                ->sortable(array('ofc_id', 'ofc_ano', 'crs_nome'));

            $paginacao = $tableData->appends($request->except('page'));
        }

        return view('Academico::ofertascursos.index', ['tabela' => $tabela, 'paginacao' => $paginacao, 'actionButton' => $actionButtons]);
    }

    public function getCreate()
    {
        $cursos = $this->cursoRepository->lists('crs_id', 'crs_nome', true);
        $modalidades = $this->modalidadeRepository->lists('mdl_id', 'mdl_nome');
        $polos = $this->poloRepository->lists('pol_id', 'pol_nome');

        return view('Academico::ofertascursos.create', compact('cursos', 'modalidades', 'polos'));
    }

    public function postCreate(OfertaCursoRequest $request)
    {
        try {
            DB::beginTransaction();

            $ofertacurso = $this->ofertacursoRepository->create($request->all());

            if (!$ofertacurso) {
                DB::rollback();
                flash()->error('Já existe uma oferta com o mesmo ano e modalidade cadastrada.');
                return redirect()->back()->withInput($request->all());
            }

            if (!is_null($request->polos)) {
                foreach ($request->polos as $key => $polo) {
                    $ofertacurso->polos()->attach($polo);
                }
            }
            
            DB::commit();

            flash()->success('Oferta de curso criada com sucesso.');
            return redirect('/academico/ofertascursos/index');
        } catch (\Exception $e) {
            DB::rollback();
            if (config('app.debug')) {
                throw $e;
            }

            flash()->error('Erro ao tentar salvar. Caso o problema persista, entre em contato com o suporte.');
            return redirect()->back();
        }
    }
}
