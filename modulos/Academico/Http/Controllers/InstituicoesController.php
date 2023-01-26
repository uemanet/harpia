<?php

namespace Modulos\Academico\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modulos\Academico\Http\Requests\InstituicaoRequest;
use Modulos\Academico\Repositories\InstituicaoRepository;
use Modulos\Core\Http\Controller\BaseController;
use Modulos\Seguranca\Providers\ActionButton\Facades\ActionButton;
use Modulos\Seguranca\Providers\ActionButton\TButton;

class InstituicoesController extends BaseController
{
    protected $instituicaoRepository;
    public function __construct(InstituicaoRepository $instituicaoRepository)
    {
        $this->instituicaoRepository = $instituicaoRepository;
    }

    public function getIndex(Request $request)
    {
        $btnNovo = new TButton();
        $btnNovo->setName('Novo')->setRoute('academico.instituicoes.create')->setIcon('fa fa-plus')->setStyle('btn bg-olive');

        $actionButtons[] = $btnNovo;

        $paginacao = null;
        $tabela = null;

        $tableData = $this->instituicaoRepository->paginateRequest($request->all());

        if ($tableData->count()) {
            $tabela = $tableData->columns(array(
                'itt_id' => '#',
                'itt_nome' => 'Instituição',
                'itt_action' => 'Ações'
            ))
                ->modifyCell('itt_action', function () {
                    return array('style' => 'width: 140px;');
                })
                ->means('itt_action', 'itt_id')
                ->modify('itt_action', function ($id) {
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
                                'route' => 'academico.instituicoes.edit',
                                'parameters' => ['id' => $id],
                                'label' => 'Editar',
                                'method' => 'get'
                            ],
                            [
                                'classButton' => 'btn-delete text-red',
                                'icon' => 'fa fa-trash',
                                'route' => 'academico.instituicoes.delete',
                                'id' => $id,
                                'label' => 'Excluir',
                                'method' => 'post'
                            ]
                        ]
                    ]);
                })
                ->sortable(array('itt_id', 'itt_nome'));

            $paginacao = $tableData->appends($request->except('page'));
        }
        return view('Academico::instituicoes.index', ['tabela' => $tabela, 'paginacao' => $paginacao, 'actionButton' => $actionButtons]);
    }

    public function getCreate()
    {
        return view('Academico::instituicoes.create');
    }

    public function postCreate(Request $request)
    {
        try {
            $instituicao = $this->instituicaoRepository->create($request->all());

            if (!$instituicao) {
                flash()->error('Erro ao tentar salvar.');

                return redirect()->back()->withInput($request->all());
            }

            flash()->success('Instituição criada com sucesso.');
            return redirect()->route('academico.instituicoes.index');
        } catch (\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            }

            flash()->error('Erro ao tentar salvar. Caso o problema persista, entre em contato com o suporte.');
            return redirect()->back();
        }
    }

    public function getEdit($instituicaoId)
    {
        $instituicao = $this->instituicaoRepository->find($instituicaoId);

        if (!$instituicao) {
            flash()->error('Instituição não existe.');
            return redirect()->back();
        }

        return view('Academico::instituicoes.edit', compact('instituicao'));
    }
    public function putEdit($instituicaoId, InstituicaoRequest $request)
    {

        try {
            $instituicao = $this->instituicaoRepository->find($instituicaoId);

            if (!$instituicao) {
                flash()->error('Instituição não existe.');
                return redirect()->route('academico.instituicoes.index');
            }

            $requestData = $request->only($this->instituicaoRepository->getFillableModelFields());

            if (!$this->instituicaoRepository->update($requestData, $instituicao->itt_id, 'itt_id')) {
                flash()->error('Erro ao tentar salvar.');
                return redirect()->back()->withInput($request->all());
            }

            flash()->success('Instituição atualizada com sucesso.');
            return redirect()->route('academico.instituicoes.index');
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
            $instituicaoId = $request->get('id');

            $this->instituicaoRepository->delete($instituicaoId);

            flash()->success('Instituição excluída com sucesso.');

            return redirect()->back();
        } catch (\Illuminate\Database\QueryException $e) {
            flash()->error('Erro ao tentar deletar');
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
