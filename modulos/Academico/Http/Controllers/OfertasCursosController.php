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

class CursosController extends BaseController
{
    protected $ofertacursoRepository;
    protected $cursoRepository;
    protected $matrizcurricularRepository;
    protected $modalidadeRepository;

    public function __construct(OfertaCursoRepository $ofertacurso, CursoRepository $curso, MatrizCurricularRepository $matrizcurricular, ModalidadeRepository $modalidade)
    {
        $this->ofertacursoRepository= $ofertacurso;
        $this->cursooRepository = $cursoo;
        $this->matrizcurricularRepository = $matrizcurricular;
        $this->modalidadeRepository = $modalidade;
    }

    public function getIndex(Request $request)
    {
        $btnNovo = new TButton();
        $btnNovo->setName('Novo')->setAction('/academico/ofertascursos/create')->setIcon('fa fa-plus')->setStyle('btn bg-olive');

        $actionButtons[] = $btnNovo;

        $tableData = $this->cursoRepository->paginateRequest($request->all());

        $tabela = $tableData->columns(array(
            'ofc_id' => '#',
            'ofc_ano' => 'Ano',
            'ofc_action' => 'Ações',

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
                        [
                            'classButton' => '',
                            'icon' => 'fa fa-pencil',
                            'action' => '/academico/ofertascursos/edit/' . $id,
                            'label' => 'Editar',
                            'method' => 'get'
                        ],
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
  }
