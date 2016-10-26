<?php

namespace Modulos\Geral\Http\Controllers;

use Illuminate\Http\Request;
use Harpia\Providers\ActionButton\TButton;
use Modulos\Core\Http\Controller\BaseController;
use Modulos\Geral\Http\Requests\PoloRequest;
use Modulos\Geral\Repositories\PoloRepository;

class PolosController extends BaseController
{
    protected $poloRepository;

    public function __construct(PoloRepository $poloRepository)
    {
        $this->poloRepository = $poloRepository;
    }

    public function getIndex(Request $request)
    {
        $btnNovo = new TButton();
        $btnNovo->setName('Novo')->setAction('/geral/polos/create')->setIcon('fa fa-plus')->setStyle('btn btn-app bg-olive');

        $actionButtons[] = $btnNovo;

        $tableData = $this->poloRepository->paginateRequest($request->all());

        return view('Geral::polos.index', ['tableData' => $tableData, 'actionButton' => $actionButtons]);
    }

    public function getCreate()
    {
        return view('Geral::polos.create');
    }

    public function postCreate(PoloRequest $request)
    {
        try {
            $polo = $this->poloRepository->create($request->all());

            if (!$polo) {
                flash()->error('Erro ao tentar salvar.');

                return redirect()->back()->withInput($request->all());
            }

            flash()->success('Polo criada com sucesso.');

            return redirect('/geral/polos');
        } catch (\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            } else {
                flash()->success('Erro ao tentar salvar. Caso o problema persista, entre em contato com o suporte.');

                return redirect()->back();
            }
        }
    }

    public function getEdit($poloId)
    {
        $polo = $this->poloRepository->find($poloId);

        if (!$polo) {
            flash()->error('Polo não existe.');

            return redirect()->back();
        }

        return view('Geral::polos.edit', compact('polo'));
    }

    public function putEdit($poloId, PoloRequest $request)
    {
        try {
            $polo = $this->poloRepository->find($poloId);

            if (!$polo) {
                flash()->error('Polo não existe.');

                return redirect('/geral/polos');
            }

            $requestData = $request->only($this->poloRepository->getFillableModelFields());

            if (!$this->poloRepository->update($requestData, $polo->pol_id, 'pol_id')) {
                flash()->error('Erro ao tentar salvar.');

                return redirect()->back()->withInput($request->all());
            }

            flash()->success('Polo atualizado com sucesso.');

            return redirect('/geral/polos');
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
            $poloId = $request->get('id');

            if ($this->poloRepository->delete($poloId)) {
                flash()->success('Polo excluído com sucesso.');
            } else {
                flash()->error('Erro ao tentar excluir o polo');
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
