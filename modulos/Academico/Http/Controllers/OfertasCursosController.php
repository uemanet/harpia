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

class OfertasCursosController extends BaseController
{
    protected $ofertacursoRepository;
    protected $cursoRepository;
    protected $matrizcurricularRepository;
    protected $modalidadeRepository;

    public function __construct(OfertaCursoRepository $ofertacursoRepository, CursoRepository $cursoRepository, MatrizCurricularRepository $matrizcurricularRepository, ModalidadeRepository $modalidadeRepository)
    {
        $this->ofertacursoRepository = $ofertacursoRepository;
        $this->cursoRepository = $cursoRepository;
        $this->matrizcurricularRepository = $matrizcurricularRepository;
        $this->modalidadeRepository = $modalidadeRepository;
    }

    public function getIndex(Request $request)
    {
        $btnNovo = new TButton();
        $btnNovo->setName('Novo')->setAction('/academico/ofertascursos/create')->setIcon('fa fa-plus')->setStyle('btn bg-olive');

        $actionButtons[] = $btnNovo;

        $tableData = $this->ofertacursoRepository->paginateRequest($request->all());

        $tabela = $tableData->columns(array(
            'ofc_id' => '#',
            'ofc_ano' => 'Ano',
            'ofc_action' => 'Ações'
        ))
            ->modifyCell('ofc_action', function () {
                return array('style' => 'width: 140px;');
            })
            ->means('ofc_action', 'ofc_id')
            ->modify('ofc_action', function ($id) {
                return ActionButton::grid([
                    'type' => 'SELECT',
                    'config' => [
                        'classButton' => 'btn-default',
                        'label' => 'Selecione'
                    ],
                    'buttons' => [
                        // [
                        //     'classButton' => '',
                        //     'icon' => 'fa fa-pencil',
                        //     'action' => '/academico/ofertascursos/edit/' . $id,
                        //     'label' => 'Editar',
                        //     'method' => 'get'
                        // ],
                        [
                            'classButton' => 'btn-delete text-red',
                            'icon' => 'fa fa-trash',
                            'action' => '/academico/ofertascursos/delete',
                            'id' => $id,
                            'label' => 'Excluir',
                            'method' => 'post'
                        ]
                    ]
                ]);
            })
            ->sortable(array('ofc_id', 'ofc_ano'));

        $paginacao = $tableData->appends($request->except('page'));

        return view('Academico::ofertascursos.index', ['tabela' => $tabela, 'paginacao' => $paginacao, 'actionButton' => $actionButtons]);
    }

    public function getCreate()
    {
        $cursos = $this->cursoRepository->lists('crs_id', 'crs_nome');

        $modalidades = $this->modalidadeRepository->lists('mdl_id', 'mdl_nome');

        return view('Academico::ofertascursos.create', compact('cursos', 'modalidades'));
    }

    public function postCreate(OfertaCursoRequest $request)
    {
        try {
            $ofertacurso = $this->ofertacursoRepository->create($request->all());

            if (!$ofertacurso) {
                flash()->error('Erro ao tentar salvar.');

                return redirect()->back()->withInput($request->all());
            }

            flash()->success('Recurso criado com sucesso.');

            return redirect('/academico/ofertascursos');
        } catch (\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            } else {
                flash()->success('Erro ao tentar salvar. Caso o problema persista, entre em contato com o suporte.');

                return redirect()->back();
            }
        }
    }

    public function postDelete(Request $request)
    {
        try {
            $ofertacursoId = $request->get('id');

            if ($this->ofertacursoRepository->delete($ofertacursoId)) {
                flash()->success('Oferta de curso excluída com sucesso.');
            } else {
                flash()->error('Erro ao tentar excluir a oferta de curso');
            }

            return redirect()->back();
        } catch (\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            } else {
                flash()->success('Erro ao tentar salvar. Caso o problema persista, entre em contato com o suporte.');

                return redirect()->back();
            }
        }
    }
  }
