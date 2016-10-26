<?php

namespace Modulos\Academico\Http\Controllers;

use Modulos\Academico\Http\Requests\VinculoRequest;
use Modulos\Seguranca\Providers\ActionButton\TButton;
use Modulos\Seguranca\Providers\ActionButton\Facades\ActionButton;
use Modulos\Core\Http\Controller\BaseController;
use Illuminate\Http\Request;
use Modulos\Seguranca\Repositories\UsuarioRepository;
use Modulos\Academico\Repositories\VinculoRepository;

class VinculosController extends BaseController
{
    protected $vinculoRepository;
    protected $usuarioRepository;

    public function __construct(VinculoRepository $vinculoRepository, UsuarioRepository $usuarioRepository)
    {
        $this->vinculoRepository = $vinculoRepository;
        $this->usuarioRepository = $usuarioRepository;
    }

    public function getIndex(Request $request)
    {
        $paginacao = null;
        $tabela = null;
        $tableData = null;

        $tableData = $this->usuarioRepository->paginateRequest($request->all());

        if ($tableData->count()) {
            $tabela = $tableData->columns(array(
                'pes_id' => '#',
                'pes_nome' => 'Pessoa',
                'ucr_action' => 'Ações',
            ))->modifyCell('ucr_action', function () {
                return array('style' => 'width: 140px;');
            })
                ->means('ucr_action', 'usr_id')
                ->modify('ucr_action', function ($id) {
                    return ActionButton::grid([
                        'type' => 'SELECT',
                        'config' => [
                            'classButton' => 'btn-default',
                            'label' => 'Selecione',
                        ],
                        'buttons' => [
                            [
                                'classButton' => '',
                                'icon' => 'fa fa-link',
                                'action' => '/academico/usuarioscursos/vinculos/' . $id,
                                'label' => 'Vínculos',
                                'method' => 'get',
                            ],
                        ],
                    ]);
                })->sortable(array('pes_id', 'pes_nome'));
            $paginacao = $tableData->appends($request->except('page'));
        }

        return view('Academico::vinculos.index', ['tabela' => $tabela, 'paginacao' => $paginacao]);
    }

    /**
     * Lista os vinculos do usuario
     * @param $usuarioId
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getVinculos($usuarioId, Request $request)
    {
        $btnNovo = new TButton();
        $btnNovo->setName('Adicionar vínculo')->setAction('/academico/usuarioscursos/create/' . $usuarioId)->setIcon('fa fa-link')->setStyle('btn bg-olive');

        $actionButtons[] = $btnNovo;
        $tabela = null;
        $paginacao = null;

        $data = $this->vinculoRepository->getCursosVinculados($usuarioId);
        $usuario = $this->usuarioRepository->find($usuarioId);

        if ($data->count()) {
            $tabela = $data->columns(array(
                'crs_nome' => 'Curso',
                'crs_sigla' => 'Sigla',
                'crs_action' => 'Ações',
            ))->modifyCell('crs_action', function () {
                return array('style' => 'width: 140px;');
            })->means('crs_action', 'ucr_id')
              ->modify('crs_action', function ($id) {
                    return ActionButton::grid([
                        'type' => 'SELECT',
                        'config' => [
                            'classButton' => 'btn-default',
                            'label' => 'Selecione',
                        ],
                        'buttons' => [
                            [
                                'classButton' => 'btn-delete text-red',
                                'icon' => 'fa fa-unlink',
                                'action' => '/academico/usuarioscursos/delete',
                                'id' => $id,
                                'label' => 'Excluir vínculo',
                                'method' => 'post',
                            ],
                        ],
                    ]);
            })->sortable(array('crs_id', 'crs_nome'));

            $paginacao = $data->appends($request->except('page'));
        }

        return view('Academico::vinculos.vinculos', [
            'tabela' => $tabela,
            'paginacao' => $paginacao,
            'actionButtons' => $actionButtons,
            'usuario' => $usuario,
        ]);
    }

    public function getCreate($usuarioId)
    {
        $cursosDisponiveis = $this->vinculoRepository->getCursosDisponiveis($usuarioId);

        if (count($cursosDisponiveis)) {
            return view('Academico::vinculos.create', [
                'usuario' => $usuarioId,
                'cursos' => $cursosDisponiveis
            ]);
        }

        flash()->error('Não há cursos disponíveis para vincular a este usuário');
        return redirect()->back();
    }

    public function postCreate($usuarioId, VinculoRequest $request)
    {
        $data = $request->get('cursos');

        try {
            foreach ($data as $curso) {
                $vinculo = [
                    'ucr_usr_id' => $usuarioId,
                    'ucr_crs_id' => $curso,
                ];
                $this->vinculoRepository->create($vinculo);
            }

            flash()->success('Vínculos criados com sucesso.');
            return redirect(route('academico.vinculos.vinculos', ['id' => $usuarioId]));
        } catch (\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            }

            flash()->success('Erro ao tentar salvar. Caso o problema persista, entre em contato com o suporte.');
            return redirect()->back();
        }
    }

    public function postDelete(Request $request)
    {
        try {
            $vinculoId = $request->get('id');

            if ($this->vinculoRepository->delete($vinculoId)) {
                flash()->success('Vínculo excluído com sucesso.');
            } else {
                flash()->error('Erro ao tentar excluir o vínculo');
            }

            return redirect()->back();
        } catch (\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            }

            flash()->success('Erro ao tentar excluir. Caso o problema persista, entre em contato com o suporte.');
            return redirect()->back();
        }
    }
}
