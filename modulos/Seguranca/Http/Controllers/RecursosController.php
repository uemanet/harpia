<?php

namespace Modulos\Seguranca\Http\Controllers;

use Harpia\Providers\ActionButton\TButton;
use Modulos\Core\Http\Controller\BaseController;
use Modulos\Seguranca\Http\Requests\RecursoRequest;
use Modulos\Seguranca\Http\Requests\StoreModuloRequest;
use Illuminate\Http\Request;
use Modulos\Seguranca\Repositories\CategoriaRecursoRepository;
use Modulos\Seguranca\Repositories\ModuloRepository;
use Modulos\Seguranca\Repositories\RecursoRepository;

class RecursosController extends BaseController
{
    protected $recursoRepository;
    protected $moduloRepository;
    protected $categoriaRecursoRepository;

    public function __construct(RecursoRepository $recursoRepository, ModuloRepository $moduloRepository, CategoriaRecursoRepository $categoriaRecursoRepository)
    {
        $this->recursoRepository = $recursoRepository;
        $this->moduloRepository = $moduloRepository;
        $this->categoriaRecursoRepository = $categoriaRecursoRepository;
    }

    public function getIndex(Request $request)
    {
        $btnNovo = new TButton();
        $btnNovo->setName('Novo')->setAction('/seguranca/recursos/create')->setIcon('fa fa-plus')->setStyle('btn btn-app bg-olive');

        $actionButtons[] = $btnNovo;

        $tableData = $this->recursoRepository->paginateRequest($request->all());

        return view('Seguranca::recursos.index', ['tableData' => $tableData, 'actionButton' => $actionButtons]);
    }

    public function getCreate()
    {
        $modulos = $this->moduloRepository->lists('mod_id', 'mod_nome');

        return view('Seguranca::recursos.create', compact('modulos'));
    }

    public function postCreate(RecursoRequest $request)
    {
        try {
            $recurso = $this->recursoRepository->create($request->all());

            if (!$recurso) {
                flash()->error('Erro ao tentar salvar.');

                return redirect()->back()->withInput($request->all());
            }

            flash()->success('Recurso criado com sucesso.');

            return redirect('/seguranca/recursos');
        } catch (\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            } else {
                flash()->success('Erro ao tentar salvar. Caso o problema persista, entre em contato com o suporte.');

                return redirect()->back();
            }
        }
    }

    public function getEdit($recursoId)
    {
        $recurso = $this->recursoRepository->find($recursoId);

        if (!$recurso) {
            flash()->error('Recurso não existe.');

            return redirect()->back();
        }

        $moduloId = $recurso->categoria->ctr_mod_id;

        $recurso->mod_id = $moduloId;

        $modulos = $this->moduloRepository->lists('mod_id', 'mod_nome');
        $categorias = $this->categoriaRecursoRepository->listsAllByModulo($moduloId);


        return view('Seguranca::recursos.edit', compact('recurso', 'modulos', 'categorias'));
    }

    public function putEdit($id, RecursoRequest $request)
    {
        try {
            $recurso = $this->recursoRepository->find($id);

            if (!$recurso) {
                flash()->error('Recurso não existe.');

                return redirect('/seguranca/recursos');
            }

            $requestData = $request->only($this->recursoRepository->getFillableModelFields());

            if (!$this->recursoRepository->update($requestData, $recurso->rcs_id, 'rcs_id')) {
                flash()->error('Erro ao tentar salvar.');

                return redirect()->back()->withInput($request->all());
            }

            flash()->success('Recurso atualizado com sucesso.');

            return redirect('/seguranca/recursos');
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
            $recursoId = $request->get('id');

            if ($this->recursoRepository->delete($recursoId)) {
                flash()->success('Recurso excluído com sucesso.');
            } else {
                flash()->error('Erro ao tentar excluir o recurso');
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
