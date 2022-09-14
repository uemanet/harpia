<?php

namespace Modulos\RH\Http\Controllers;

use Illuminate\Http\Request;
use Modulos\Geral\Repositories\AnexoRepository;
use Modulos\RH\Http\Requests\JustificativaRequest;
use Modulos\RH\Repositories\HoraTrabalhadaRepository;
use Modulos\RH\Repositories\JustificativaRepository;
use Modulos\Seguranca\Providers\ActionButton\Facades\ActionButton;
use Modulos\Seguranca\Providers\ActionButton\TButton;
use Modulos\Core\Http\Controller\BaseController;

class JustificativasController extends BaseController
{
    protected $justificativaRepository;
    protected $horaTrabalhadaRepository;
    protected $anexoRepository;

    public function __construct(HoraTrabalhadaRepository $horaTrabalhadaRepository,
                                JustificativaRepository $justificativaRepository,
                                AnexoRepository $anexoRepository)
    {
        $this->horaTrabalhadaRepository = $horaTrabalhadaRepository;
        $this->justificativaRepository = $justificativaRepository;
        $this->anexoRepository = $anexoRepository;
    }

    public function getIndex($horaTrabalhadaId, Request $request)
    {
        $horaTrabalhada = $this->horaTrabalhadaRepository->find($horaTrabalhadaId);

        if (!$horaTrabalhada) {
            flash()->error('Hora Trabalhada não existe');

            return redirect()->back();
        }

        $btnNovo = new TButton();
        $btnNovo->setName('Novo')->setRoute('rh.horastrabalhadas.justificativas.create')->setParameters(['id' => $horaTrabalhadaId])->setIcon('fa fa-plus')->setStyle('btn bg-olive');


        $actionButtons[] = $btnNovo;
        $paginacao = null;
        $tabela = null;

        $tableData = $this->justificativaRepository->paginateRequestByHoraTrabalhada($horaTrabalhadaId, $request->all());

        if ($tableData->count()) {
            $tabela = $tableData->columns(array(
                'jus_id' => '#',
                'jus_horas' => 'Horas',
                'jus_data' => 'Data',
                'jus_action' => 'Ações'
            ))
                ->modifyCell('jus_action', function () {
                    return array('style' => 'width: 140px;');
                })
                ->means('jus_action', 'jus_id')
                ->modify('jus_action', function ($id) {
                    return ActionButton::grid([
                        'type' => 'SELECT',
                        'config' => [
                            'classButton' => 'btn-default',
                            'label' => 'Selecione'
                        ],
                        'buttons' => [
                            [
                                'classButton' => '',
                                'icon' => 'fa fa-eye',
                                'route' => 'rh.horastrabalhadas.justificativas.show',
                                'parameters' => [ $id ],
                                'id' => $id,
                                'label' => 'Visualizar',
                                'method' => 'get'
                            ],
                            [
                                'classButton' => 'btn-delete text-red',
                                'icon' => 'fa fa-trash',
                                'route' => 'rh.horastrabalhadas.justificativas.delete',
                                'id' => $id,
                                'label' => 'Excluir',
                                'method' => 'post'
                            ]
                        ]
                    ]);
                })
                ->sortable(array('jus_id', 'jus_data'));

            $paginacao = $tableData->appends($request->except('page'));
        }

        return view('RH::justificativas.index', ['tabela' => $tabela, 'paginacao' => $paginacao, 'actionButton' => $actionButtons, 'horaTrabalhada' => $horaTrabalhada]);
    }

    public function getCreate(Request $request)
    {
        $horaTrabalhadaId = $request->get('id');

        $horaTrabalhada = $this->horaTrabalhadaRepository->find($horaTrabalhadaId);

        if (!$horaTrabalhada) {
            flash()->error('Hora Trabalhada não existe');

            return redirect()->back();
        }

        return view('RH::justificativas.create', compact('horaTrabalhada'));
    }

    public function postCreate(JustificativaRequest $request)
    {
        $dados = $request->all();

        try {
            if ($request->file('jus_file') != null) {
                $anexoDocumento = $request->file('jus_file');
                $anexoCriado = $this->anexoRepository->salvarAnexo($anexoDocumento);
                $dados['jus_anx_id'] = $anexoCriado->anx_id;
            }

            $justificativa = $this->justificativaRepository->create($dados);

            $this->horaTrabalhadaRepository->sincronizarJustificativa($justificativa->horaTrabalhada);

            if (!$justificativa) {
                flash()->error('Erro ao tentar salvar.');
                return redirect()->back()->withInput($request->all());
            }

            flash()->success('Justificativa criada com sucesso.');
            return redirect()->route('rh.horastrabalhadas.justificativas.index', $justificativa->jus_htr_id);
        } catch (\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            }

            flash()->error('Erro ao tentar salvar. Caso o problema persista, entre em contato com o suporte.');
            return redirect()->back();
        }
    }

    public function postDelete(Request $request)
    {
        try {
            $justificativaId = $request->get('id');

            $justificativa = $this->justificativaRepository->find($justificativaId);

            if ($this->justificativaRepository->delete($justificativaId) && $justificativa->jus_anx_id != null) {
                $this->anexoRepository->deletarAnexo($justificativa->jus_anx_id);
            }

            $this->horaTrabalhadaRepository->sincronizarJustificativa($justificativa->horaTrabalhada);

            flash()->success('Justificativa deletada com sucesso.');

            return redirect()->back();
        } catch (\Illuminate\Database\QueryException $e) {
            flash()->error('Erro ao tentar deletar. A justificativa contém dependências no sistema.');
            return redirect()->back();
        } catch (\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            }

            flash()->error('Erro ao tentar excluir. Caso o problema persista, entre em contato com o suporte.');
            return redirect()->back();
        }
    }

    public function getShow($justificativaId)
    {
        try {
            $justificativa = $this->justificativaRepository->find($justificativaId);

            if (!$justificativa) {
                flash()->error('Justificativa não existe');

                return redirect()->back();
            }

            return view('RH::justificativas.show', compact('justificativa'));
        } catch (\Illuminate\Database\QueryException $e) {
            flash()->error('Erro ao tentar deletar. A justificativa contém dependências no sistema.');
            return redirect()->back();
        } catch (\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            }

            flash()->error('Erro ao tentar excluir. Caso o problema persista, entre em contato com o suporte.');
            return redirect()->back();
        }
    }


    public function getAnexo($justificativaId)
    {
        $justificativa = $this->justificativaRepository->find($justificativaId);

        if (!$justificativa) {
            flash()->error('Justificativa não existe.');
            return redirect()->back();
        }

        $anexo = $this->anexoRepository->recuperarAnexo($justificativa->jus_anx_id);

        if ($anexo == 'error_non_existent') {
            flash()->error('Anexo não existe');
            return redirect()->back();
        }

        return $anexo;
    }

}
