<?php

namespace Modulos\Academico\Http\Controllers;

use Modulos\Seguranca\Providers\ActionButton\Facades\ActionButton;
use Modulos\Seguranca\Providers\ActionButton\TButton;
use Modulos\Core\Http\Controller\BaseController;
use Modulos\Academico\Http\Requests\TutorGrupoRequest;
use Illuminate\Http\Request;
use Modulos\Academico\Repositories\TutorGrupoRepository;
use Modulos\Academico\Repositories\GrupoRepository;

class TutoresGruposController extends BaseController
{
    protected $tutorgrupoRepository;
    protected $grupoRepository;

    public function __construct(TutorGrupoRepository $tutorgrupoRepository, GrupoRepository $grupoRepository)
    {
        $this->tutorgrupoRepository = $tutorgrupoRepository;
        $this->grupoRepository = $grupoRepository;
    }

    public function getIndex($grupoId,Request $request)
    {
        $btnNovo = new TButton();
        $btnNovo->setName('Novo')->setAction('/academico/tutoresgrupos/create/'. $grupoId)->setIcon('fa fa-plus')->setStyle('btn bg-olive');

        $grupo = $this->grupoRepository->find($grupoId);

        $actionButtons[] = $btnNovo;

        $tabela = null;
        $paginacao = null;

        $tableData = $this->tutorgrupoRepository->paginateRequestByGrupo($grupoId, $request->all());

        if ($tableData->count()) {
            $tabela = $tableData->columns(array(
                'ttg_id' => '#',
                'ttg_tut_id' => 'Tutor',
                'ttg_tipo_tutoria' => 'Tipo de Tutoria',
                'ttg_action' => 'Ações'
            ))
                ->modifyCell('ttg_action', function () {
                    return array('style' => 'width: 140px;');
                })
                ->means('ttg_action', 'ttg_id')
                ->means('ttg_tut_id', 'tutor')
                ->modify('ttg_tut_id', function ($tutor) {
                    return $tutor->pessoa->pes_nome;
                })
                ->modify('ttg_action', function ($id) {
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
                                'action' => '/academico/tutoresgrupos/edit/' . $id,
                                'label' => 'Editar',
                                'method' => 'get'
                            ],
                            [
                                'classButton' => 'btn-delete text-red',
                                'icon' => 'fa fa-trash',
                                'action' => '/academico/tutoresgrupos/delete',
                                'id' => $id,
                                'label' => 'Excluir',
                                'method' => 'post'
                            ]
                        ]
                    ]);
                })
                ->sortable(array('ttg_id', 'ttg_tut_id'));

            $paginacao = $tableData->appends($request->except('page'));
        }
        return view('Academico::tutoresgrupos.index', ['tabela' => $tabela, 'paginacao' => $paginacao, 'actionButton' => $actionButtons]);
    }

    public function getCreate($grupoId)
    {
        $grupo = $this->grupoRepository->find($grupoId);

        $grupo = $this->grupoRepository->listsAllById($grupoId);

        $tutores = $this->tutoresRepository->lists('tut_id', 'tut_pes_id');

        return view('Academico::tutoresgrupos.create', ['grupo' => $grupo, 'tutores' => $tutores]);
    }
}
