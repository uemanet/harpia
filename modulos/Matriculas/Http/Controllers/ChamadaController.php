<?php

namespace Modulos\Matriculas\Http\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Modulos\Matriculas\Http\Requests\ChamadaRequest;
use Modulos\Matriculas\Models\Seletivo;
use Modulos\Matriculas\Repositories\ChamadaRepository;
use Modulos\Matriculas\Repositories\InscricaoRepository;
use Modulos\Seguranca\Providers\ActionButton\Facades\ActionButton;
use Illuminate\Http\Request;
use Modulos\Seguranca\Providers\ActionButton\TButton;

/**
 * Class ChamadaController.
 */
class ChamadaController extends Controller
{
    protected $chamadaRepository;

    public function __construct(ChamadaRepository $chamadaRepository)
    {
        $this->chamadaRepository = $chamadaRepository;
    }

    public function getIndex(Request $request)
    {
        $btnNovo = new TButton();
        $btnNovo->setName('Novo')->setRoute('matriculas.chamadas.create')->setIcon('fa fa-plus')->setStyle('btn bg-olive');

        $actionButtons[] = $btnNovo;

        $paginacao = null;
        $tabela = null;

        $tableData = $this->chamadaRepository->paginateRequest($request->all());

        if ($tableData->count()) {
            $tabela = $tableData->columns(array(
                'id' => '#',
                'nome' => 'Nome',
                'seletivo' => 'Seletivo',
                'inicio_matricula' => 'Início',
                'fim_matricula' => 'Fim',
                'tipo_chamada' => 'Tipo Chamada',
                'action' => 'Ações'
            ))
                ->modifyCell('action', function () {
                    return array('style' => 'width: 140px;');
                })
                ->means('action', 'id')
                ->modify('seletivo', function ($chamada){
                    return $chamada->seletivo->nome;
                })
                ->modify('inicio_matricula', function ($chamada){
                    return Carbon::parse($chamada->inicio_matricula)->format('d/m/Y H:i:s');
                })
                ->modify('fim_matricula', function ($chamada){
                    return Carbon::parse($chamada->fim_matricula)->format('d/m/Y H:i:s');
                })
                ->modify('action', function ($id) {
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
                                'route' => 'matriculas.chamadas.edit',
                                'parameters' => ['id' => $id],
                                'label' => 'Editar',
                                'method' => 'get'
                            ],
                            [
                                'classButton' => 'btn-delete text-red',
                                'icon' => 'fa fa-trash',
                                'route' => 'matriculas.chamadas.delete',
                                'id' => $id,
                                'label' => 'Excluir',
                                'method' => 'post'
                            ]
                        ]
                    ]);
                })
                ->sortable(array('id', 'nome'));

            $paginacao = $tableData->appends($request->except('page'));
        }
        return view('Matriculas::chamadas.index', ['tabela' => $tabela, 'paginacao' => $paginacao, 'actionButton' => $actionButtons]);
    }

    public function getCreate()
    {
        $seletivos = Seletivo::all()->pluck('nome', 'id');
        return view('Matriculas::chamadas.create', ['seletivos' => $seletivos]);
    }

    public function postCreate(ChamadaRequest $request)
    {
        try {
            $chamada = $this->chamadaRepository->create($request->all());

            if (!$chamada) {
                flash()->error('Erro ao tentar salvar.');

                return redirect()->back()->withInput($request->all());
            }

            flash()->success('Chamada criada com sucesso.');
            return redirect()->route('matriculas.chamadas.index');
        } catch (\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            }

            flash()->error('Erro ao tentar salvar. Caso o problema persista, entre em contato com o suporte.');
            return redirect()->back();
        }
    }

    public function getEdit($chamadaId)
    {
        $chamada = $this->chamadaRepository->find($chamadaId);
        $seletivos = Seletivo::all()->pluck('nome', 'id');

        if (!$chamada) {
            flash()->error('Chamada não existe.');
            return redirect()->back();
        }

        return view('Matriculas::chamadas.edit', compact('chamada', 'seletivos'));
    }

    public function putEdit($chamadaId, ChamadaRequest $request)
    {
        try {
            $chamada = $this->chamadaRepository->find($chamadaId);

            if (!$chamada) {
                flash()->error('Chamada não existe.');
                return redirect()->route('matriculas.chamadas.index');
            }

            $requestData = $request->only($this->chamadaRepository->getFillableModelFields());

            if (!$this->chamadaRepository->update($requestData, $chamada->id, 'id')) {
                flash()->error('Erro ao tentar salvar.');
                return redirect()->back()->withInput($request->all());
            }

            flash()->success('Chamada atualizada com sucesso.');
            return redirect()->route('matriculas.chamadas.index');
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
            $chamadaId = $request->get('id');

            $this->chamadaRepository->delete($chamadaId);

            flash()->success('Chamada excluída com sucesso.');

            return redirect()->back();
        } catch (\Illuminate\Database\QueryException $e) {
            flash()->error('Erro ao tentar deletar. A chamada contém dependências no sistema.');
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
