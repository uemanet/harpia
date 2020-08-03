<?php


namespace Modulos\RH\Http\Controllers;


use Modulos\Core\Http\Controller\BaseController;
use Modulos\RH\Http\Requests\FuncaoRequest;
use Modulos\RH\Repositories\FuncaoRepository;
use Modulos\Seguranca\Providers\ActionButton\Facades\ActionButton;
use Modulos\Seguranca\Providers\ActionButton\TButton;

class FuncoesController extends BaseController
{
    protected $funcaoRepository;

    public function __construct(FuncaoRepository $funcaoRepository)
    {
        $this->funcaoRepository = $funcaoRepository;
    }

    public function getIndex(\Illuminate\Http\Request $request)
    {

        $btnNovo = new TButton();
        $btnNovo->setName('Novo')->setRoute('rh.funcoes.create')->setIcon('fa fa-plus')->setStyle('btn bg-olive');

        $actionButtons[] = $btnNovo;

        $paginacao = null;
        $tabela = null;

        $tableData = $this->funcaoRepository->paginateRequest($request->all());

        if ($tableData->count()) {
            $tabela = $tableData->columns(array(
                'fun_id' => '#',
                'fun_descricao' => 'Descrição',
                'fun_action' => 'Ações'
            ))
                ->modifyCell('fun_action', function () {
                    return array('style' => 'width: 140px;');
                })
                ->means('fun_action', 'fun_id')
                ->modify('fun_action', function ($id) {
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
                                'route' => 'rh.funcoes.edit',
                                'parameters' => ['id' => $id],
                                'label' => 'Editar',
                                'method' => 'get'
                            ],
                            [
                                'classButton' => 'btn-delete text-red',
                                'icon' => 'fa fa-trash',
                                'route' => 'rh.funcoes.delete',
                                'id' => $id,
                                'label' => 'Excluir',
                                'method' => 'post'
                            ]
                        ]
                    ]);
                })
                ->sortable(array('fun_id', 'fun_descricao'));

            $paginacao = $tableData->appends($request->except('page'));
        }

        return view('RH::funcoes.index', ['tabela' => $tabela, 'paginacao' => $paginacao, 'actionButton' => $actionButtons]);
    }

    public function getCreate()
    {
        return view('RH::funcoes.create');
    }

    public function postCreate(FuncaoRequest $request)
    {
        try {
            $funcao = $this->funcaoRepository->create($request->all());

            if (!$funcao) {
                flash()->error('Erro ao tentar salvar.');

                return redirect()->back()->withInput($request->all());
            }

            flash()->success('Função criada com sucesso.');
            return redirect()->route('rh.funcoes.index');
        } catch (\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            }

            flash()->error('Erro ao tentar salvar. Caso o problema persista, entre em contato com o suporte.');
            return redirect()->back();
        }
    }

    public function getEdit($funcaoId)
    {
        $funcao = $this->funcaoRepository->find($funcaoId);

        if (!$funcao) {
            flash()->error('Função não existe.');
            return redirect()->back();
        }

        return view('RH::funcoes.edit', compact('funcao'));
    }

    public function putEdit($funcaoId, FuncaoRequest $request)
    {
        try {
            $funcao = $this->funcaoRepository->find($funcaoId);

            if (!$funcao) {
                flash()->error('Vínculo não existe.');
                return redirect()->route('rh.funcoes.index');
            }

            $requestData = $request->only($this->funcaoRepository->getFillableModelFields());

            if (!$this->funcaoRepository->update($requestData, $funcao->fun_id, 'fun_id')) {
                flash()->error('Erro ao tentar salvar.');
                return redirect()->back()->withInput($request->all());
            }

            flash()->success('Função atualizada com sucesso.');
            return redirect()->route('rh.funcoes.index');
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
            $funcaoId = $request->get('id');

            $this->funcaoRepository->delete($funcaoId);

            flash()->success('Função excluída com sucesso.');

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