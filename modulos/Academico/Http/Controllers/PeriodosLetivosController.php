<?php

namespace Modulos\Academico\Http\Controllers;

use Modulos\Seguranca\Providers\ActionButton\Facades\ActionButton;
use Modulos\Seguranca\Providers\ActionButton\TButton;
use Modulos\Core\Http\Controller\BaseController;
use Modulos\Academico\Http\Requests\PeriodoLetivoRequest;
use Illuminate\Http\Request;
use Modulos\Academico\Repositories\PeriodoLetivoRepository;

class PeriodosLetivosController extends BaseController
{
    protected $periodoLetivoRepository;

    public function __construct(PeriodoLetivoRepository $periodoLetivoRepository)
    {
        $this->periodoLetivoRepository = $periodoLetivoRepository;

    }

    public function getIndex(Request $request)
    {
        $btnNovo = new TButton();
        $btnNovo->setName('Novo')->setAction('/academico/periodosletivos/create')->setIcon('fa fa-plus')->setStyle('btn bg-olive');

        $actionButtons[] = $btnNovo;

        $tableData = $this->periodoLetivoRepository->paginateRequest($request->all());

        $tabela = $tableData->columns(array(
            'per_id' => '#',
            'per_inicio' => 'Início',
            'per_fim' => 'Fim',
            'per_action' => 'Ações'
        ))
            ->modifyCell('per_action', function () {
                return array('style' => 'width: 140px;');
            })
            ->means('per_action', 'per_id')
            ->modify('per_action', function ($id) {
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
                            'action' => '/academico/periodosletivos/edit/' . $id,
                            'label' => 'Editar',
                            'method' => 'get'
                        ],
                        [
                            'classButton' => 'btn-delete text-red',
                            'icon' => 'fa fa-trash',
                            'action' => '/academico/periodosletivos/delete',
                            'id' => $id,
                            'label' => 'Excluir',
                            'method' => 'post'
                        ]
                    ]
                ]);
            })
            ->sortable(array('per_id', 'per_inicio'));

        $paginacao = $tableData->appends($request->except('page'));

        return view('Academico::periodosletivos.index', ['tabela' => $tabela, 'paginacao' => $paginacao, 'actionButton' => $actionButtons]);
    }

    public function getCreate()
    {
        return view('Academico::periodosletivos.create');
    }

    public function postCreate(PeriodoLetivoRequest $request)
    {
        try {
            $periodoLetivo = $this->periodoLetivoRepository->create($request->all());

            if (!$periodoLetivo) {
                flash()->error('Erro ao tentar salvar.');

                return redirect()->back()->withInput($request->all());
            }

            flash()->success('Período Letivo criado com sucesso.');

            return redirect('/academico/periodosletivos');
        } catch (\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            } else {
                flash()->success('Erro ao tentar salvar. Caso o problema persista, entre em contato com o suporte.');

                return redirect()->back();
            }
        }
    }

    public function getEdit($periodoLetivoId)
    {
        $periodoLetivo = $this->periodoLetivoRepository->find($periodoLetivoId);

        if (!$periodoLetivo) {
            flash()->error('Período Letivo não existe.');

            return redirect()->back();
        }

        return view('Academico::periodosletivos.edit', compact('periodoLetivo'));
    }

    public function putEdit($periodoLetivoId, PeriodoLetivoRequest $request)
    {
        try {
            $periodoLetivo = $this->periodoLetivoRepository->find($periodoLetivoId);

            if (!$periodoLetivo) {
                flash()->error('Período Letivo não existe.');

                return redirect('/academico/periodosletivos');
            }

            $requestData = $request->only($this->periodoLetivoRepository->getFillableModelFields());

            if (!$this->periodoLetivoRepository->update($requestData, $periodoLetivo->per_id, 'per_id')) {
                flash()->error('Erro ao tentar salvar.');

                return redirect()->back()->withInput($request->all());
            }

            flash()->success('Período Letivo atualizado com sucesso.');

            return redirect('/academico/periodosletivos');
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
            $periodoLetivoId = $request->get('id');

            if ($this->periodoLetivoRepository->delete($periodoLetivoId)) {
                flash()->success('Período Letivo excluído com sucesso.');
            } else {
                flash()->error('Erro ao tentar excluir o período letivo');
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
