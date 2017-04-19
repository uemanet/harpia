<?php

namespace Modulos\Seguranca\Http\Controllers;

use Modulos\Seguranca\Providers\ActionButton\Facades\ActionButton;
use Modulos\Seguranca\Providers\ActionButton\TButton;
use Modulos\Core\Http\Controller\BaseController;
use Modulos\Seguranca\Http\Requests\CategoriaRecursoRequest;
use Modulos\Seguranca\Repositories\CategoriaRecursoRepository;
use Modulos\Seguranca\Repositories\ModuloRepository;
use Modulos\Seguranca\Http\Requests\ModuloRequest;
use Illuminate\Http\Request;

class CategoriasRecursosController extends BaseController
{
    protected $categoriaRecursoRepository;
    protected $moduloRepository;

    public function __construct(CategoriaRecursoRepository $categoriaRecursoRepository, ModuloRepository $moduloRepository)
    {
        $this->categoriaRecursoRepository = $categoriaRecursoRepository;
        $this->moduloRepository = $moduloRepository;
    }

    public function getIndex(Request $request)
    {
        $btnNovo = new TButton();
        $btnNovo->setName('Novo')->setAction('/seguranca/categoriasrecursos/create')->setIcon('fa fa-plus')->setStyle('btn bg-olive');

        $actionButtons[] = $btnNovo;

        $paginacao = null;
        $tabela = null;

        $tableData = $this->categoriaRecursoRepository->paginateRequest($request->all());

        if ($tableData->count()) {
            $tabela = $tableData->columns(array(
                'ctr_id' => '#',
                'ctr_nome' => 'Categoria',
                'ctr_descricao' => 'Descrição',
                'ctr_action' => 'Ações'
            ))
                ->modifyCell('ctr_action', function () {
                    return array('style' => 'width: 140px;');
                })
                ->means('ctr_action', 'ctr_id')
                ->modify('ctr_action', function ($id) {
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
                                'action' => '/seguranca/categoriasrecursos/edit/' . $id,
                                'label' => 'Editar',
                                'method' => 'get'
                            ],
                            [
                                'classButton' => 'btn-delete text-red',
                                'icon' => 'fa fa-trash',
                                'action' =>  '/seguranca/categoriasrecursos/delete',
                                'id' => $id,
                                'label' => 'Excluir',
                                'method' => 'post'
                            ]
                        ]
                    ]);
                })
                ->sortable(array('ctr_id', 'ctr_nome'));

            $paginacao = $tableData->appends($request->except('page'));
        }

        return view('Seguranca::categoriasrecursos.index',
            ['tabela' => $tabela, 'paginacao' => $paginacao, 'actionButton' => $actionButtons]);
    }

    public function getCreate()
    {
        $modulos = $this->moduloRepository->lists('mod_id', 'mod_nome');
        $categorias = $this->categoriaRecursoRepository->lists('ctr_id', 'ctr_nome');

        return view('Seguranca::categoriasrecursos.create', compact('modulos', 'categorias'));
    }

    public function postCreate(CategoriaRecursoRequest $request)
    {
        try {
            $categoriaRecurso = $this->categoriaRecursoRepository->create($request->all());

            if (!$categoriaRecurso) {
                flash()->error('Erro ao tentar salvar.');

                return redirect()->back()->withInput($request->all());
            }

            flash()->success('Categoria criada com sucesso.');

            return redirect('/seguranca/categoriasrecursos/index');
        } catch (\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            } else {
                flash()->error('Erro ao tentar salvar. Caso o problema persista, entre em contato com o suporte.');

                return redirect()->back();
            }
        }
    }

    public function getEdit($categoriaId)
    {
        $categoria = $this->categoriaRecursoRepository->find($categoriaId);

        if (!$categoria) {
            flash()->error('Categoria não existe.');

            return redirect()->back();
        }

        $modulos = $this->moduloRepository->lists('mod_id', 'mod_nome');
        $categorias = $this->categoriaRecursoRepository->lists('ctr_id', 'ctr_nome');

        return view('Seguranca::categoriasrecursos.edit', compact('categoria', 'modulos', 'categorias'));
    }

    public function putEdit($id, CategoriaRecursoRequest $request)
    {
        try {
            $categoria = $this->categoriaRecursoRepository->find($id);

            if (!$categoria) {
                flash()->error('Categoria não existe.');

                return redirect('/seguranca/categoriasrecursos/index');
            }

            $requestData = $request->only($this->categoriaRecursoRepository->getFillableModelFields());

            if (!$this->categoriaRecursoRepository->update($requestData, $categoria->ctr_id, 'ctr_id')) {
                flash()->error('Erro ao tentar salvar.');

                return redirect()->back()->withInput($request->all());
            }

            flash()->success('Categoria atualizada com sucesso.');

            return redirect('/seguranca/categoriasrecursos/index');
        } catch (\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            } else {
                flash()->error('Erro ao tentar salvar. Caso o problema persista, entre em contato com o suporte.');

                return redirect()->back();
            }
        }
    }

    public function postDelete(Request $request)
    {
        try {
            $categoriaId = $request->get('id');

            if ($this->categoriaRecursoRepository->delete($categoriaId)) {
                flash()->success('Categoria excluída com sucesso.');
            } else {
                flash()->error('Erro ao tentar excluir a categoria');
            }

            return redirect()->back();
        } catch (\Illuminate\Database\QueryException $e) {
            flash()->error('Erro ao tentar deletar. A categoria contém dependências no sistema.');
            return redirect()->back();
        } catch (\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            } else {
                flash()->error('Erro ao tentar salvar. Caso o problema persista, entre em contato com o suporte.');

                return redirect()->back();
            }
        }
    }
}
