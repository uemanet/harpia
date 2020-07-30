<?php

namespace Modulos\RH\Http\Controllers;

use Modulos\Seguranca\Providers\ActionButton\Facades\ActionButton;
use Modulos\Seguranca\Providers\ActionButton\TButton;
use Modulos\Core\Http\Controller\BaseController;
use Modulos\RH\Http\Requests\AreaConhecimentoRequest;
use Illuminate\Http\Request;
use Modulos\RH\Repositories\AreaConhecimentoRepository;

class AreasConhecimentosController extends BaseController
{
    protected $areaConhecimentoRepository;

    public function __construct(AreaConhecimentoRepository $areaConhecimentoRepository)
    {
        $this->areaConhecimentoRepository = $areaConhecimentoRepository;
    }

    public function getIndex(Request $request)
    {
        $btnNovo = new TButton();
        $btnNovo->setName('Novo')->setRoute('rh.areasconhecimentos.create')->setIcon('fa fa-plus')->setStyle('btn bg-olive');

        $actionButtons[] = $btnNovo;

        $paginacao = null;
        $tabela = null;

        $tableData = $this->areaConhecimentoRepository->paginateRequest($request->all());

        if ($tableData->count()) {
            $tabela = $tableData->columns(array(
                'arc_id' => '#',
                'arc_descricao' => 'Área de Conhecimento',
                'arc_action' => 'Ações'
            ))
                ->modifyCell('arc_action', function () {
                    return array('style' => 'width: 140px;');
                })
                ->means('arc_action', 'arc_id')
                ->modify('arc_action', function ($id) {
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
                                'route' => 'rh.areasconhecimentos.edit',
                                'parameters' => ['id' => $id],
                                'label' => 'Editar',
                                'method' => 'get'
                            ],
                            [
                                'classButton' => 'btn-delete text-red',
                                'icon' => 'fa fa-trash',
                                'route' => 'rh.areasconhecimentos.delete',
                                'id' => $id,
                                'label' => 'Excluir',
                                'method' => 'post'
                            ]
                        ]
                    ]);
                })
                ->sortable(array('arc_id', 'arc_descricao'));

            $paginacao = $tableData->appends($request->except('page'));
        }

        return view('RH::areasconhecimentos.index', ['tabela' => $tabela, 'paginacao' => $paginacao, 'actionButton' => $actionButtons]);
    }

    public function getCreate()
    {
        return view('RH::areasconhecimentos.create');
    }

    public function postCreate(AreaConhecimentoRequest $request)
    {
        try {
            $areaConhecimento = $this->areaConhecimentoRepository->create($request->all());

            if (!$areaConhecimento) {
                flash()->error('Erro ao tentar salvar.');

                return redirect()->back()->withInput($request->all());
            }

            flash()->success('Área de Conhecimento criada com sucesso.');
            return redirect()->route('rh.areasconhecimentos.index');
        } catch (\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            }

            flash()->error('Erro ao tentar salvar. Caso o problema persista, entre em contato com o suporte.');
            return redirect()->back();
        }
    }

    public function getEdit($areaConhecimentoId)
    {
        $areaConhecimento = $this->areaConhecimentoRepository->find($areaConhecimentoId);

        if (!$areaConhecimento) {
            flash()->error('Área de Conhecimento não existe.');
            return redirect()->back();
        }

        return view('RH::areasconhecimentos.edit', compact('areaConhecimento'));
    }

    public function putEdit($areaConhecimentoId, AreaConhecimentoRequest $request)
    {
        try {
            $areaConhecimento = $this->areaConhecimentoRepository->find($areaConhecimentoId);

            if (!$areaConhecimento) {
                flash()->error('Área de Conhecimento não existe.');
                return redirect()->route('rh.areasconhecimentos.index');
            }

            $requestData = $request->only($this->areaConhecimentoRepository->getFillableModelFields());

            if (!$this->areaConhecimentoRepository->update($requestData, $areaConhecimento->arc_id, 'arc_id')) {
                flash()->error('Erro ao tentar salvar.');
                return redirect()->back()->withInput($request->all());
            }

            flash()->success('Área de Conhecimento atualizada com sucesso.');
            return redirect()->route('rh.areasconhecimentos.index');
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
            $areaConhecimentoId = $request->get('id');

            $this->areaConhecimentoRepository->delete($areaConhecimentoId);

            flash()->success('Área de Conhecimento excluído com sucesso.');

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
