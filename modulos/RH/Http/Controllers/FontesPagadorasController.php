<?php


namespace Modulos\RH\Http\Controllers;


use Modulos\Core\Http\Controller\BaseController;
use Modulos\RH\Http\Requests\FontePagadoraRequest;
use Modulos\RH\Repositories\FontePagadoraRepository;
use Modulos\Seguranca\Providers\ActionButton\TButton;
use Modulos\Seguranca\Providers\ActionButton\Facades\ActionButton;
use Illuminate\Http\Request;

class FontesPagadorasController extends BaseController
{
    protected $fontePagadoraRepository;

    public function __construct(FontePagadoraRepository $fontePagadoraRepository)
    {
        $this->fontePagadoraRepository = $fontePagadoraRepository;
    }

    public function getIndex(Request $request)
    {

        $btnNovo = new TButton();
        $btnNovo->setName('Novo')->setRoute('rh.fontespagadoras.create')->setIcon('fa fa-plus')->setStyle('btn bg-olive');

        $actionButtons[] = $btnNovo;

        $paginacao = null;
        $tabela = null;

        $tableData = $this->fontePagadoraRepository->paginateRequest($request->all());

        if ($tableData->count()) {
            $tabela = $tableData->columns(array(
                'fpg_id' => '#',
                'fpg_razao_social' => 'Razão Social',
                'fpg_action' => 'Ações'
            ))
                ->modifyCell('fpg_action', function () {
                    return array('style' => 'width: 140px;');
                })
                ->means('fpg_action', 'fpg_id')
                ->modify('fpg_action', function ($id) {
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
                                'route' => 'rh.fontespagadoras.edit',
                                'parameters' => ['id' => $id],
                                'label' => 'Editar',
                                'method' => 'get'
                            ],
                            [
                                'classButton' => 'btn-delete text-red',
                                'icon' => 'fa fa-trash',
                                'route' => 'rh.fontespagadoras.delete',
                                'id' => $id,
                                'label' => 'Excluir',
                                'method' => 'post'
                            ]
                        ]
                    ]);
                })
                ->sortable(array('fpg_id', 'fpg_razao_social'));

            $paginacao = $tableData->appends($request->except('page'));
        }

        return view('RH::fontespagadoras.index', ['tabela' => $tabela, 'paginacao' => $paginacao, 'actionButton' => $actionButtons]);
    }

    public function getCreate()
    {
        return view('RH::fontespagadoras.create');
    }

    public function postCreate(FontePagadoraRequest $request)
    {
        try {
            $fontepagadora = $this->fontePagadoraRepository->create($request->all());

            if (!$fontepagadora) {
                flash()->error('Erro ao tentar salvar.');

                return redirect()->back()->withInput($request->all());
            }

            flash()->success('Fonte pagadora criada com sucesso.');
            return redirect()->route('rh.fontespagadoras.index');
        } catch (\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            }

            flash()->error('Erro ao tentar salvar. Caso o problema persista, entre em contato com o suporte.');
            return redirect()->back();
        }
    }

    public function getEdit($fontepagadoraId)
    {
        $fontepagadora = $this->fontePagadoraRepository->find($fontepagadoraId);

        if (!$fontepagadora) {
            flash()->error('Fonte pagadora não existe.');
            return redirect()->back();
        }

        return view('RH::fontespagadoras.edit', compact('fontepagadora'));
    }

    public function putEdit($fontepagadoraId, FontePagadoraRequest $request)
    {
        try {
            $fontepagadora = $this->fontePagadoraRepository->find($fontepagadoraId);

            if (!$fontepagadora) {
                flash()->error('Fonte pagadora não existe.');
                return redirect()->route('rh.fontespagadoras.index');
            }

            $requestData = $request->only($this->fontePagadoraRepository->getFillableModelFields());

            if (!$this->fontePagadoraRepository->update($requestData, $fontepagadora->fpg_id, 'fpg_id')) {
                flash()->error('Erro ao tentar salvar.');
                return redirect()->back()->withInput($request->all());
            }

            flash()->success('Fonte pagadora atualizada com sucesso.');
            return redirect()->route('rh.fontespagadoras.index');
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
            $fontepagadoraId = $request->get('id');

            $this->fontePagadoraRepository->delete($fontepagadoraId);

            flash()->success('Fonte pagadora excluída com sucesso.');

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