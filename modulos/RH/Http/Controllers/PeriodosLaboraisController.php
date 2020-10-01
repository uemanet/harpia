<?php

namespace Modulos\RH\Http\Controllers;

use Modulos\RH\Http\Requests\PeriodoLaboralRequest;
use Modulos\RH\Repositories\PeriodoLaboralRepository;
use Modulos\Seguranca\Providers\ActionButton\Facades\ActionButton;
use Modulos\Seguranca\Providers\ActionButton\TButton;
use Modulos\Core\Http\Controller\BaseController;
use Illuminate\Http\Request;

class PeriodosLaboraisController extends BaseController
{
    protected $periodoLaboralRepository;

    public function __construct(PeriodoLaboralRepository $periodoLaboralRepository)
    {
        $this->periodoLaboralRepository = $periodoLaboralRepository;
    }

    public function getIndex(Request $request)
    {

        $btnNovo = new TButton();
        $btnNovo->setName('Novo')->setRoute('rh.periodoslaborais.create')->setIcon('fa fa-plus')->setStyle('btn bg-olive');

        $actionButtons[] = $btnNovo;

        $paginacao = null;
        $tabela = null;

        $tableData = $this->periodoLaboralRepository->paginateRequest($request->all());

        if ($tableData->count()) {
            $tabela = $tableData->columns(array(
                'pel_id' => '#',
                'pel_inicio' => 'Início',
                'pel_termino' => 'Fim',
                'pel_action' => 'Ações'
            ))
                ->modifyCell('pel_action', function () {
                    return array('style' => 'width: 140px;');
                })
                ->means('pel_action', 'pel_id')
                ->modify('pel_action', function ($id) {
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
                                'route' => 'rh.periodoslaborais.edit',
                                'parameters' => ['id' => $id],
                                'label' => 'Editar',
                                'method' => 'get'
                            ],
                            [
                                'classButton' => 'btn-delete text-red',
                                'icon' => 'fa fa-trash',
                                'route' => 'rh.periodoslaborais.delete',
                                'id' => $id,
                                'label' => 'Excluir',
                                'method' => 'post'
                            ]
                        ]
                    ]);
                })
                ->sortable(array('pel_id', 'ban_nome'));

            $paginacao = $tableData->appends($request->except('page'));
        }

        return view('RH::periodoslaborais.index', ['tabela' => $tabela, 'paginacao' => $paginacao, 'actionButton' => $actionButtons]);
    }

    public function getCreate()
    {
        return view('RH::periodoslaborais.create');
    }

    public function postCreate(PeriodoLaboralRequest $request)
    {
        try {

            $periodolaboral = $this->periodoLaboralRepository->create($request->all());

            if (!$periodolaboral) {
                flash()->error('Erro ao tentar salvar.');

                return redirect()->back()->withInput($request->all());
            }

            flash()->success('Período Laboral criado com sucesso.');
            return redirect()->route('rh.periodoslaborais.index');
        } catch (\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            }

            flash()->error('Erro ao tentar salvar. Caso o problema persista, entre em contato com o suporte.');
            return redirect()->back();
        }
    }

    public function getEdit($periodolaboralId)
    {
        $periodolaboral = $this->periodoLaboralRepository->find($periodolaboralId);

        if (!$periodolaboral) {
            flash()->error('Período Laboral não existe.');
            return redirect()->back();
        }

        return view('RH::periodoslaborais.edit', compact('periodolaboral'));
    }

    public function putEdit($periodolaboralId, PeriodoLaboralRequest $request)
    {
        try {
            $periodolaboral = $this->periodoLaboralRepository->find($periodolaboralId);

            if (!$periodolaboral) {
                flash()->error('Período Laboral não existe.');
                return redirect()->route('rh.periodoslaborais.index');
            }

            $requestData = $request->only($this->periodoLaboralRepository->getFillableModelFields());

            if (!$this->periodoLaboralRepository->update($requestData, $periodolaboral->pel_id, 'pel_id')) {
                flash()->error('Erro ao tentar salvar.');
                return redirect()->back()->withInput($request->all());
            }

            flash()->success('Período Laboral atualizado com sucesso.');
            return redirect()->route('rh.periodoslaborais.index');
        } catch (\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            }

            flash()->error('Erro ao tentar atualizar. Caso o problema persista, entre em contato com o suporte.');
            return redirect()->back();
        }
    }

    public function postDelete(Request $request)
    {
        try {
            $periodolaboralId = $request->get('id');

            $this->periodoLaboralRepository->delete($periodolaboralId);

            flash()->success('Período Laboral excluído com sucesso.');

            return redirect()->back();
        } catch (\Illuminate\Database\QueryException $e) {
            flash()->error('Erro ao tentar deletar. O recurso contém dependências no sistema.');
            return redirect()->back();
        } catch (\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            }

            flash()->error('Erro ao tentar excluir. Caso o problema persista, entre em contato com o suporte.');
            return redirect()->back();
        }
    }

}
