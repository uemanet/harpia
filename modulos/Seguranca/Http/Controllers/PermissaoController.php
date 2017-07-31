<?php

namespace Modulos\Seguranca\Http\Controllers;

use Illuminate\Http\Request;
use Modulos\Core\Http\Controller\BaseController;
use Modulos\Seguranca\Http\Requests\PermissaoRequest;
use Modulos\Seguranca\Providers\ActionButton\Facades\ActionButton;
use Modulos\Seguranca\Providers\ActionButton\TButton;
use Modulos\Seguranca\Repositories\ModuloRepository;
use Modulos\Seguranca\Repositories\PermissaoRepository;

class PermissaoController extends BaseController
{
    protected $permissaoRepository;
    protected $moduloRepository;

    public function __construct(PermissaoRepository $permissaoRepository, ModuloRepository $moduloRepository)
    {
        $this->permissaoRepository = $permissaoRepository;
        $this->moduloRepository = $moduloRepository;
    }

    public function index(Request $request)
    {
        $btnNovo = new TButton();
        $btnNovo->setName('Novo')->setRoute('seguranca.permissoes.create')->setIcon('fa fa-plus')->setStyle('btn bg-olive');

        $actionButtons[] = $btnNovo;

        $paginacao = null;
        $tabela = null;

        $tableData = $this->permissaoRepository->paginateRequest($request->all());

        if ($tableData->count()) {
            $tabela = $tableData->columns(array(
                'prm_id' => '#',
                'prm_nome' => 'Permissao',
                'prm_rota' => 'Rota',
                'prm_modulo' => 'Módulo',
                'prm_descricao' => 'Descrição',
                'prm_action' => 'Ações'
            ))
            ->modifyCell('prm_action', function () {
                return array('style' => 'width: 140px;');
            })
            ->modify('prm_modulo', function ($permissao) {
                return $permissao->modulo()->mod_nome;
            })
            ->modify('prm_action', function ($permissao) {
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
                            'route' => 'seguranca.permissoes.edit',
                            'parameters' => ['id' => $permissao->prm_id],
                            'label' => 'Editar',
                            'method' => 'get'
                        ],
                        [
                            'classButton' => 'btn-delete text-red',
                            'icon' => 'fa fa-trash',
                            'route' =>  'seguranca.permissoes.delete',
                            'id' => $permissao->prm_id,
                            'label' => 'Excluir',
                            'method' => 'post'
                        ]
                    ]
                ]);
            })
            ->sortable(array('prm_id', 'prm_nome'));

            $paginacao = $tableData->appends($request->except('page'));

            return view('Seguranca::permissoes.index', ['tabela' => $tabela, 'paginacao' => $paginacao, 'actionButton' => $actionButtons]);
        }
    }

    public function getCreate()
    {
        $modulos = $this->moduloRepository->lists('mod_slug', 'mod_nome');

        return view('Seguranca::permissoes.create', compact('modulos'));
    }

    public function postCreate(PermissaoRequest $request)
    {
        $return = $this->permissaoRepository->create($request->all());

        flash()->{$return['status']}($return['message']);

        if ($return['status'] == 'error') {
            return redirect()->back()->withInput();
        }

        return redirect()->route('seguranca.permissoes.index');
    }

    public function getEdit($permissaoId)
    {
        $permissao = $this->permissaoRepository->find($permissaoId);

        if (!$permissao) {
            flash()->error('Permissao não existe');
            return redirect()->back();
        }

        $permissao->modulo = $permissao->slugModulo();
        $permissao->recurso = $permissao->recurso();

        $modulos = $this->moduloRepository->lists('mod_slug', 'mod_nome');
        $recursos = $this->permissaoRepository->getRecursosByModulo($permissao->slugModulo());

        return view('Seguranca::permissoes.edit', compact('permissao', 'modulos', 'recursos'));
    }

    public function putEdit($permissaoId, PermissaoRequest $request)
    {
        $permissao = $this->permissaoRepository->find($permissaoId);

        if (!$permissao) {
            flash()->error('Permissao não existe');
            return redirect()->back();
        }

        try {
            $this->permissaoRepository->update($request->all(), $permissaoId);

            flash()->success('Permissão atualizada com sucesso.');
            return redirect()->route('seguranca.permissoes.index');
        } catch (\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            }

            flash()->error('Erro ao tentar atualizar. Entrar em contato com o suporte.');
            return redirect()->route('seguranca.permissoes.index');
        }
    }

    public function postDelete(Request $request)
    {
        try {

            $this->permissaoRepository->delete($request->input('id'));

            flash()->success('Permissão excluída com sucesso.');
            return redirect()->back();
        } catch (\Illuminate\Database\QueryException $e) {
            flash()->error('Erro ao tentar deletar. A permissão contém dependências no sistema.');
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