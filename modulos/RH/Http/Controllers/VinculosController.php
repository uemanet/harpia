<?php


namespace Modulos\RH\Http\Controllers;

use App\Http\Requests\Request;
use Modulos\Core\Http\Controller\BaseController;
use Modulos\RH\Http\Requests\VinculoRequest;
use Modulos\RH\Repositories\VinculoRepository;
use Modulos\Seguranca\Providers\ActionButton\Facades\ActionButton;
use Modulos\Seguranca\Providers\ActionButton\TButton;

class VinculosController extends BaseController
{
    protected $vinculoRepository;

    public function __construct(VinculoRepository $vinculoRepository)
    {
        $this->vinculoRepository = $vinculoRepository;
    }

    public function getIndex(\Illuminate\Http\Request $request)
    {

        $btnNovo = new TButton();
        $btnNovo->setName('Novo')->setRoute('rh.vinculos.create')->setIcon('fa fa-plus')->setStyle('btn bg-olive');

        $actionButtons[] = $btnNovo;

        $paginacao = null;
        $tabela = null;

        $tableData = $this->vinculoRepository->paginateRequest($request->all());

        if ($tableData->count()) {
            $tabela = $tableData->columns(array(
                'vin_id' => '#',
                'vin_descricao' => 'Descrição',
                'vin_action' => 'Ações'
            ))
                ->modifyCell('vin_action', function () {
                    return array('style' => 'width: 140px;');
                })
                ->means('vin_action', 'vin_id')
                ->modify('vin_action', function ($id) {
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
                                'route' => 'rh.vinculos.edit',
                                'parameters' => ['id' => $id],
                                'label' => 'Editar',
                                'method' => 'get'
                            ],
                            [
                                'classButton' => 'btn-delete text-red',
                                'icon' => 'fa fa-trash',
                                'route' => 'rh.vinculos.delete',
                                'id' => $id,
                                'label' => 'Excluir',
                                'method' => 'post'
                            ]
                        ]
                    ]);
                })
                ->sortable(array('vin_id', 'vin_descricao'));

            $paginacao = $tableData->appends($request->except('page'));
        }

        return view('RH::vinculos.index', ['tabela' => $tabela, 'paginacao' => $paginacao, 'actionButton' => $actionButtons]);
    }

    public function getCreate()
    {
        return view('RH::vinculos.create');
    }

    public function postCreate(VinculoRequest $request)
    {
        try {
            $vinculo = $this->vinculoRepository->create($request->all());

            if (!$vinculo) {
                flash()->error('Erro ao tentar salvar.');

                return redirect()->back()->withInput($request->all());
            }

            flash()->success('Vínculo criado com sucesso.');
            return redirect()->route('rh.vinculos.index');
        } catch (\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            }

            flash()->error('Erro ao tentar salvar. Caso o problema persista, entre em contato com o suporte.');
            return redirect()->back();
        }
    }

    public function getEdit($vinculoId)
    {
        $vinculo = $this->vinculoRepository->find($vinculoId);

        if (!$vinculo) {
            flash()->error('Vínculo não existe.');
            return redirect()->back();
        }

        return view('RH::vinculos.edit', compact('vinculo'));
    }

    public function putEdit($vinculoId, VinculoRequest $request)
    {
        try {
            $vinculo = $this->vinculoRepository->find($vinculoId);

            if (!$vinculo) {
                flash()->error('Vínculo não existe.');
                return redirect()->route('rh.vinculos.index');
            }

            $requestData = $request->only($this->vinculoRepository->getFillableModelFields());

            if (!$this->vinculoRepository->update($requestData, $vinculo->vin_id, 'vin_id')) {
                flash()->error('Erro ao tentar salvar.');
                return redirect()->back()->withInput($request->all());
            }

            flash()->success('Vínculo atualizado com sucesso.');
            return redirect()->route('rh.vinculos.index');
        } catch (\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            }

            flash()->error('Erro ao tentar atualizar. Caso o problema persista, entre em contato com o suporte.');
            return redirect()->back();
        }
    }

    public function postDelete(\Illuminate\Http\Request $request)
    {
        try {
            $vinculoId = $request->get('id');

            $this->vinculoRepository->delete($vinculoId);

            flash()->success('Vínculo excluído com sucesso.');

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