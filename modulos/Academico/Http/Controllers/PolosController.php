<?php

namespace Modulos\Academico\Http\Controllers;

use Modulos\Academico\Repositories\OfertaCursoRepository;
use Modulos\Seguranca\Providers\ActionButton\Facades\ActionButton;
use Modulos\Seguranca\Providers\ActionButton\TButton;
use Modulos\Core\Http\Controller\BaseController;
use Modulos\Academico\Http\Requests\PoloRequest;
use Illuminate\Http\Request;
use Modulos\Academico\Repositories\PoloRepository;

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
        $btnNovo->setName('Novo')->setRoute('academico.polos.create')->setIcon('fa fa-plus')->setStyle('btn bg-olive');

        $actionButtons[] = $btnNovo;

        $paginacao = null;
        $tabela = null;

        $tableData = $this->poloRepository->paginateRequest($request->all());

        if ($tableData->count()) {
            $tabela = $tableData->columns(array(
                'pol_id' => '#',
                'pol_nome' => 'Polo',
                'pol_action' => 'Ações'
            ))
                ->modifyCell('pol_action', function () {
                    return array('style' => 'width: 140px;');
                })
                ->means('pol_action', 'pol_id')
                ->modify('pol_action', function ($id) {
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
                                'route' => 'academico.polos.edit',
                                'parameters' => ['id' => $id],
                                'label' => 'Editar',
                                'method' => 'get'
                            ],
                            [
                                'classButton' => 'btn-delete text-red',
                                'icon' => 'fa fa-trash',
                                'route' => 'academico.polos.delete',
                                'id' => $id,
                                'label' => 'Excluir',
                                'method' => 'post'
                            ]
                        ]
                    ]);
                })
                ->sortable(array('pol_id', 'pol_nome'));

            $paginacao = $tableData->appends($request->except('page'));
        }
        return view('Academico::polos.index', ['tabela' => $tabela, 'paginacao' => $paginacao, 'actionButton' => $actionButtons]);
    }

    public function getCreate()
    {
        return view('Academico::polos.create');
    }

    public function postCreate(PoloRequest $request)
    {
        try {
            $polo = $this->poloRepository->create($request->all());

            if (!$polo) {
                flash()->error('Erro ao tentar salvar.');

                return redirect()->back()->withInput($request->all());
            }

            flash()->success('Polo criado com sucesso.');
            return redirect()->route('academico.polos.index');
        } catch (\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            }

            flash()->error('Erro ao tentar salvar. Caso o problema persista, entre em contato com o suporte.');
            return redirect()->back();
        }
    }

    public function getEdit($poloId)
    {
        $polo = $this->poloRepository->find($poloId);

        if (!$polo) {
            flash()->error('Polo não existe.');
            return redirect()->back();
        }

        return view('Academico::polos.edit', compact('polo'));
    }

    public function putEdit($poloId, PoloRequest $request)
    {
        try {
            $polo = $this->poloRepository->find($poloId);

            if (!$polo) {
                flash()->error('Polo não existe.');
                return redirect()->route('academico.polos.index');
            }

            $requestData = $request->only($this->poloRepository->getFillableModelFields());

            if (!$this->poloRepository->update($requestData, $polo->pol_id, 'pol_id')) {
                flash()->error('Erro ao tentar salvar.');
                return redirect()->back()->withInput($request->all());
            }

            flash()->success('Polo atualizado com sucesso.');
            return redirect()->route('academico.polos.index');
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
            $poloId = $request->get('id');

            $this->poloRepository->delete($poloId);

            flash()->success('Polo excluído com sucesso.');

            return redirect()->back();
        } catch (\Illuminate\Database\QueryException $e) {
            flash()->error('Erro ao tentar deletar. O polo contém dependências no sistema.');
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
